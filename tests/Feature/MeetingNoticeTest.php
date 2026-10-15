<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingNotice;

uses(RefreshDatabase::class);

function pendingMeetingCase(): int {
    $department=DB::table('departments')->insertGetId(['department_name'=>'Engineering'],'department_id');
    $program=DB::table('programs')->insertGetId(['department_id'=>$department,'program'=>'BS IT'],'program_id');
    $student=DB::table('students')->insertGetId(['program_id'=>$program,'student_name'=>'Test Student','sr_code'=>'23-12345','gsuite'=>'different@example.test','password'=>'unused'],'student_id');
    $guard=DB::table('guards')->insertGetId(['name'=>'Test Guard','email'=>'guard@example.test','password'=>'unused'],'guard_id');
    $violation=DB::table('violations')->insertGetId(['violation_name'=>'Weapons','violation_weight'=>'MAJOR Set C'],'violation_id');
    return DB::table('violation_reports')->insertGetId(['student_id'=>$student,'guard_id'=>$guard,'violation_id'=>$violation,'classification'=>'Major','incident_at'=>'2026-10-13 14:00:00','location'=>'Gate','incident_statement'=>'Test incident','status'=>'Pending Verification','submission_token'=>(string)\Illuminate\Support\Str::uuid(),'created_at'=>now(),'updated_at'=>now()]);
}

test('scheduling sends SR code email with PDF and also notifies the reporting guard',function(){
    Mail::fake(); config(['mail.default'=>'smtp']);
    $id=pendingMeetingCase();
    $date=now()->timezone('Asia/Singapore')->addDay()->format('Y-m-d\TH:i');
    $this->withSession(['svrms_role'=>'admin','svrms_id'=>1])->patch('/admin/cases/'.$id,['action'=>'notice','meeting_at'=>$date])->assertRedirect()->assertSessionHasNoErrors();
    Mail::assertSent(MeetingNotice::class, function($mail){
        return $mail->hasTo('23-12345@g.batstate-u.edu.ph') && !$mail->hasTo('different@example.test')
            && str_starts_with($mail->pdfBytes,'%PDF-') && count($mail->attachments())===1
            && str_contains($mail->render(),'Philippine time');
    });
    Mail::assertSent(MeetingNotice::class,fn($mail)=>$mail->hasTo('guard@example.test'));
    Mail::assertSentCount(2);
    $this->get('/admin/cases/'.$id)->assertOk()->assertSee('23-12345@g.batstate-u.edu.ph')->assertSee('Meeting notification status')->assertSee('Accepted by mail service');
    $this->assertDatabaseHas('violation_reports',['id'=>$id,'status'=>'Meeting Scheduled']);
    $this->assertDatabaseHas('notification_deliveries',['violation_report_id'=>$id,'recipient_type'=>'student','recipient_email'=>'23-12345@g.batstate-u.edu.ph','status'=>'sent','error_message'=>null]);
});

test('a missing or past meeting date cannot schedule or send notices',function(){
    Mail::fake(); $id=pendingMeetingCase();
    $this->withSession(['svrms_role'=>'admin','svrms_id'=>1]);
    foreach([null,now()->timezone('Asia/Singapore')->subDay()->format('Y-m-d\TH:i')] as $date){
        $this->patch('/admin/cases/'.$id,['action'=>'notice','meeting_at'=>$date])->assertSessionHasErrors('meeting_at');
    }
    Mail::assertNothingSent();
    $this->assertDatabaseHas('violation_reports',['id'=>$id,'status'=>'Pending Verification']);
});

test('mail failures are recorded and surfaced instead of claiming notification success',function(){
    $id=pendingMeetingCase();
    Mail::shouldReceive('to')->twice()->andThrow(new RuntimeException('SMTP unavailable'));
    $this->withSession(['svrms_role'=>'admin','svrms_id'=>1])->patch('/admin/cases/'.$id,['action'=>'notice','meeting_at'=>now()->timezone('Asia/Singapore')->addDay()->format('Y-m-d\TH:i')])->assertSessionHasErrors('notification');
    $this->assertDatabaseHas('violation_reports',['id'=>$id,'status'=>'Meeting Scheduled']);
    $this->assertDatabaseHas('notification_deliveries',['violation_report_id'=>$id,'recipient_type'=>'student','status'=>'failed','sent_at'=>null]);
});

test('log mailer records logged notices without a sent timestamp',function(){
    Mail::fake(); config(['mail.default'=>'log']); $id=pendingMeetingCase();
    $this->withSession(['svrms_role'=>'admin','svrms_id'=>1])->patch('/admin/cases/'.$id,['action'=>'notice','meeting_at'=>now()->timezone('Asia/Singapore')->addDay()->format('Y-m-d\TH:i')])->assertSessionHasNoErrors();
    $this->assertDatabaseHas('notification_deliveries',['violation_report_id'=>$id,'recipient_type'=>'student','status'=>'logged','sent_at'=>null]);
});
