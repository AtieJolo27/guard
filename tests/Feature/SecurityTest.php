<?php

use App\Mail\MeetingNotice;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function securityCase(): int
{
    $d = DB::table('departments')->insertGetId(['department_name' => 'Engineering'], 'department_id');
    $p = DB::table('programs')->insertGetId(['department_id' => $d, 'program' => 'BS IT'], 'program_id');
    $student = DB::table('students')->insertGetId(['program_id' => $p, 'student_name' => '<script>alert(1)</script>', 'sr_code' => '26-11111', 'gsuite' => 'student@example.test', 'password' => 'unused'], 'student_id');
    $guard = DB::table('guards')->insertGetId(['name' => 'Guard', 'email' => 'guard@example.test', 'password' => 'unused'], 'guard_id');
    $violation = DB::table('violations')->insertGetId(['violation_name' => 'Weapons', 'violation_weight' => 'Major'], 'violation_id');

    return DB::table('violation_reports')->insertGetId(['student_id' => $student, 'guard_id' => $guard, 'violation_id' => $violation, 'classification' => 'Major', 'status' => 'Pending Verification', 'incident_at' => now()->subDay(), 'location' => 'Gate', 'incident_statement' => '<img src=x onerror=alert(1)>', 'submission_token' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
}

test('login is rate limited and returns a generic failure for unknown and known accounts', function () {
    DB::table('admins')->insert(['email' => 'qa@example.test', 'password' => Hash::make('test-password')]);
    foreach (['qa@example.test', 'missing@example.test'] as $email) {
        $this->postJson('/login', ['email' => $email, 'password' => 'wrong'])->assertUnprocessable()->assertJsonValidationErrors('email')->assertJsonFragment(['Invalid credentials.']);
    }
    for ($i = 0; $i < 4; $i++) {
        $this->postJson('/login', ['email' => 'missing@example.test', 'password' => 'wrong'])->assertUnprocessable();
    }
    $this->postJson('/login', ['email' => 'missing@example.test', 'password' => 'wrong'])->assertStatus(429);
});

test('malformed login input is rejected without a server error', function () {
    $this->postJson('/login', ['email' => ['bad'], 'password' => ['bad']])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    $this->postJson('/login', ['email' => str_repeat('a', 255), 'password' => 'wrong'])->assertUnprocessable();
});

test('successful login sets only the server selected role and logout removes access', function () {
    $id = DB::table('admins')->insertGetId(['email' => 'qa@example.test', 'password' => Hash::make('test-password')], 'admin_id');
    $this->post('/login', ['email' => 'qa@example.test', 'password' => 'test-password', 'svrms_role' => 'guard', 'svrms_id' => 999])->assertRedirect(route('admin.dashboard'))->assertSessionHas('svrms_role', 'admin')->assertSessionHas('svrms_id', $id);
    $this->post('/logout')->assertRedirect(route('login'))->assertSessionMissing('svrms_id');
    $this->get('/admin/dashboard')->assertForbidden();
});

test('unauthorized users and guards cannot read or mutate admin cases', function () {
    $id = securityCase();
    foreach ([[], ['svrms_role' => 'guard', 'svrms_id' => 1]] as $session) {
        $this->withSession($session)->get('/admin/cases/'.$id)->assertForbidden();
        $this->patchJson('/admin/cases/'.$id, ['action' => 'confirm'])->assertForbidden();
    }
    $this->assertDatabaseCount('records', 0);
});

test('case confirmations are not replayable and arbitrary updates are ignored', function () {
    $id = securityCase();
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1]);
    $this->patch('/admin/cases/'.$id, ['action' => 'confirm', 'student_id' => 999, 'guard_id' => 999, 'classification' => 'Minor', 'status' => 'Dismissed'])->assertRedirect();
    $this->patchJson('/admin/cases/'.$id, ['action' => 'confirm'])->assertUnprocessable();
    $this->assertDatabaseCount('records', 1);
    $this->assertDatabaseHas('violation_reports', ['id' => $id, 'status' => 'Confirmed', 'classification' => 'Major', 'student_id' => 1]);
});

test('replaying an unchanged meeting schedule does not resend email', function () {
    $id = securityCase();
    Mail::fake();
    config(['mail.default' => 'smtp']);
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1]);
    $payload = ['action' => 'notice', 'meeting_at' => now()->timezone('Asia/Singapore')->addDay()->format('Y-m-d\TH:i')];
    $this->patch('/admin/cases/'.$id, $payload)->assertRedirect();
    $this->patch('/admin/cases/'.$id, $payload)->assertRedirect();
    Mail::assertSentCount(2);
});

test('stored markup is escaped and security headers protect private responses', function () {
    $id = securityCase();
    $response = $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])->get('/admin/cases/'.$id)->assertOk()->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('<img src=x onerror=alert(1)>', false);
    $response->assertHeader('X-Frame-Options', 'DENY')->assertHeader('X-Content-Type-Options', 'nosniff');
    expect($response->headers->get('Cache-Control'))->toContain('no-store');
    expect($response->headers->get('Content-Security-Policy'))->toContain("script-src 'self' 'nonce-")->toContain("object-src 'none'")->toContain("frame-ancestors 'none'");
});

test('tampered filters and route identifiers return validation or not found responses', function () {
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1]);
    $this->getJson('/admin/cases?search[]=bad')->assertUnprocessable();
    $this->get('/admin/cases/not-a-number')->assertNotFound();
    $this->get('/admin/students/lookup/invalid')->assertNotFound();
});

test('requests without CSRF cannot log in or change a case', function () {
    // Laravel bypasses CSRF by default during tests. Enable the real token check here.
    $this->app->bind(PreventRequestForgery::class, function ($app) {
        return new class($app, $app['encrypter']) extends PreventRequestForgery
        {
            protected function runningUnitTests()
            {
                return false;
            }
        };
    });
    $this->postJson('/login', ['email' => 'qa@example.test', 'password' => 'wrong'])->assertStatus(419);
    $id = securityCase();
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])->patchJson('/admin/cases/'.$id, ['action' => 'confirm'])->assertStatus(419);
    $this->assertDatabaseCount('records', 0);
});

test('a guard cannot impersonate another reporting guard in a direct request', function () {
    securityCase();
    $this->withSession(['svrms_role' => 'guard', 'svrms_id' => 1])->postJson('/guard/reports', [
        'submission_token' => (string) Str::uuid(), 'guard_id' => 999, 'sr_code' => '26-11111', 'violation_id' => 'other', 'other_violation' => 'Visible misconduct', 'incident_at' => now()->subDay()->format('Y-m-d\TH:i'), 'location' => 'Gate', 'incident_statement' => 'Test',
    ])->assertUnprocessable()->assertJsonValidationErrors('guard_id');
    $this->assertDatabaseCount('violation_reports', 1);
});

test('an in-progress case lock rejects concurrent mutations', function () {
    $id = securityCase();
    $lock = Cache::lock('case-action:'.$id, 120);
    expect($lock->get())->toBeTrue();
    try {
        $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])->patchJson('/admin/cases/'.$id, ['action' => 'confirm'])->assertStatus(409);
    } finally {
        $lock->release();
    }
    $this->assertDatabaseCount('records', 0);
});

test('retrying a partially failed meeting only resends the failed recipient', function () {
    $id = securityCase();
    Mail::fake();
    config(['mail.default' => 'smtp']);
    $date = now()->timezone('Asia/Singapore')->addDay()->format('Y-m-d\TH:i');
    DB::table('violation_reports')->where('id', $id)->update(['status' => 'Meeting Scheduled', 'meeting_at' => str_replace('T', ' ', $date).':00']);
    foreach ([['student', 'failed', '26-11111@g.batstate-u.edu.ph'], ['guard', 'sent', 'guard@example.test']] as [$recipient,$status,$email]) {
        DB::table('notification_deliveries')->insert(['violation_report_id' => $id, 'recipient_type' => $recipient, 'notification_type' => 'meeting', 'recipient_email' => $email, 'status' => $status, 'created_at' => now(), 'updated_at' => now()]);
    }
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])->patch('/admin/cases/'.$id, ['action' => 'notice', 'meeting_at' => $date])->assertRedirect()->assertSessionHasNoErrors();
    Mail::assertSentCount(1);
    Mail::assertSent(MeetingNotice::class, fn ($mail) => $mail->hasTo('26-11111@g.batstate-u.edu.ph'));
});

test('SQL injection does not authenticate and declared oversized bodies are rejected', function () {
    $this->postJson('/login', ['email' => "' OR 1=1 --", 'password' => 'wrong'])->assertUnprocessable()->assertSessionMissing('svrms_role');
    $this->postJson('/login', ['email' => str_repeat('a', 132000), 'password' => 'wrong'])->assertStatus(413);
});
