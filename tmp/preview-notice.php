<?php
require __DIR__.'/../vendor/autoload.php';
$app=require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$r=(object)['id'=>123,'created_at'=>'2026-10-14 01:00:00','student_name'=>'Sample Student','sr_code'=>'23-12345','meeting_at'=>'2026-10-16 10:00:00','incident_at'=>'2026-10-13 14:30:00','violation_name'=>'Unbecoming Behavior','location'=>'Main Gate'];
file_put_contents(__DIR__.'/../output/pdf/sample-meeting-notice.pdf',app(App\Services\MeetingNoticePdf::class)->generate($r));
