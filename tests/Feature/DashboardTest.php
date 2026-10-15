<?php

use App\Support\GuardViolationCatalog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('admin dashboard renders live summary and case links', function () {
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1, 'svrms_name' => 'Administrator'])
        ->get('/admin/dashboard')->assertOk()
        ->assertSee('Discipline Office Dashboard')
        ->assertSee('Recorded Violations')->assertSee('Deadline Monitoring')
        ->assertSee('No violation cases yet')
        ->assertViewHas('stats', fn ($stats) => $stats['confirmed'] === 0 && $stats['due'] === 0);
});

test('guard dashboard keeps incident recorder available', function () {
    $this->withSession(['svrms_role' => 'guard', 'svrms_id' => 1, 'svrms_name' => 'Guard'])
        ->get('/guard/dashboard')->assertOk()->assertSee('Record Violation')
        ->assertSee(route('guard.reports.create'))->assertDontSee('Review Pending Cases');
});

test('dashboard case filters can be opened with no reports', function () {
    foreach (['pending=1', 'due=24h', 'classification=Major&status=Confirmed'] as $filter) {
        $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])
            ->get('/admin/cases?'.$filter)->assertOk()->assertSee('No cases found.');
    }
});

test('reference sidebar destinations are available to administrators', function () {
    foreach (['students', 'categories', 'reports', 'audit', 'settings'] as $page) {
        $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])
            ->get('/admin/office/'.$page)->assertOk();
    }
    $this->get('/admin/reports/create')->assertOk()->assertSee('Reporting Guard');
});

test('admin incident intake attributes report to selected guard and audits administrator', function () {
    $department = DB::table('departments')->insertGetId(['department_name' => 'Engineering'], 'department_id');
    $program = DB::table('programs')->insertGetId(['department_id' => $department, 'program' => 'BS Computer Science'], 'program_id');
    DB::table('students')->insert(['program_id' => $program, 'student_name' => 'Test Student', 'sr_code' => '26-12345', 'gsuite' => 'student@example.test', 'password' => 'unused']);
    $guard = DB::table('guards')->insertGetId(['name' => 'Test Guard', 'email' => 'guard@example.test', 'password' => 'unused'], 'guard_id');
    $violation = DB::table('violations')->insertGetId(['violation_name' => 'Improper Uniform', 'violation_weight' => 'Minor'], 'violation_id');
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 77])->post('/admin/reports', [
        'submission_token' => (string) Str::uuid(), 'guard_id' => $guard, 'sr_code' => '26-12345', 'violation_id' => $violation,
        'incident_at' => '2026-10-09T10:00', 'location' => 'Main gate', 'incident_statement' => 'Student was wearing an incomplete uniform.',
    ])->assertRedirect(route('admin.dashboard'));
    $this->assertDatabaseHas('violation_reports', ['guard_id' => $guard, 'status' => 'Confirmed']);
    $this->assertDatabaseHas('report_actions', ['actor_type' => 'admin', 'actor_id' => 77]);
    $this->get('/admin/dashboard')->assertOk()->assertSee('26-12345')->assertSee('Improper Uniform')
        ->assertViewHas('stats', fn ($stats) => $stats['confirmed'] === 1);
});

test('guard cannot open administrator sidebar destinations', function () {
    $this->withSession(['svrms_role' => 'guard', 'svrms_id' => 1])
        ->get('/admin/office/students')->assertForbidden();
});

test('workflow migration repairs missing supporting tables without replacing reports', function () {
    $before = Schema::getColumnListing('violation_reports');
    Schema::drop('notification_deliveries');
    Schema::drop('report_actions');
    $migration = require database_path('migrations/2026_09_29_000000_create_svrms_workflow_tables.php');
    $migration->up();
    $migration->up();
    expect(Schema::hasTable('notification_deliveries'))->toBeTrue()
        ->and(Schema::hasTable('report_actions'))->toBeTrue()
        ->and(Schema::getColumnListing('violation_reports'))->toBe($before);
});

test('repeated incident submission creates only one case and confirmed record', function () {
    $department = DB::table('departments')->insertGetId(['department_name' => 'Engineering'], 'department_id');
    $program = DB::table('programs')->insertGetId(['department_id' => $department, 'program' => 'BS IT'], 'program_id');
    DB::table('students')->insert(['program_id' => $program, 'student_name' => 'Student', 'sr_code' => '26-12345', 'gsuite' => 'student@example.test', 'password' => 'unused']);
    $guard = DB::table('guards')->insertGetId(['name' => 'Guard', 'email' => 'guard@example.test', 'password' => 'unused'], 'guard_id');
    $violation = DB::table('violations')->insertGetId(['violation_name' => 'Uniform', 'violation_weight' => 'Minor'], 'violation_id');
    $payload = ['submission_token' => (string) Str::uuid(), 'sr_code' => '26-12345', 'violation_id' => $violation, 'incident_at' => '2026-10-13T14:42', 'location' => 'Gate', 'incident_statement' => 'Incomplete uniform.'];
    $this->withSession(['svrms_role' => 'guard', 'svrms_id' => $guard]);
    $this->get('/guard/reports/create')->assertOk()->assertSee('name="submission_token"', false);
    $excluded = DB::table('violations')->insertGetId(['violation_name' => 'Academic Dishonesty - Cheating', 'violation_weight' => 'MAJOR Set A'], 'violation_id');
    foreach (['guard', 'admin'] as $role) {
        $this->withSession(['svrms_role' => $role, 'svrms_id' => $guard]);
        $this->get('/'.$role.'/reports/create')->assertOk()->assertSee('Uniform')->assertDontSee('Academic Dishonesty - Cheating')->assertSee('Other');
        $this->post('/'.$role.'/reports', array_merge($payload, ['violation_id' => $excluded], $role === 'admin' ? ['guard_id' => $guard] : []))->assertSessionHasErrors('violation_id');
    }
    $this->assertDatabaseCount('violation_reports', 0);
    $this->withSession(['svrms_role' => 'guard', 'svrms_id' => $guard]);
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $this->assertDatabaseCount('violation_reports', 1);
    $this->assertDatabaseCount('records', 1);
    $this->assertDatabaseCount('report_actions', 1);
    // A separately opened form can still record a legitimate new incident.
    $payload['submission_token'] = (string) Str::uuid();
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $this->assertDatabaseCount('violation_reports', 2);
    $payload['submission_token'] = (string) Str::uuid();
    $payload['violation_id'] = 'other';
    $this->post('/guard/reports', $payload)->assertSessionHasErrors('other_violation');
    $this->assertDatabaseCount('violation_reports', 2);
    $payload['other_violation'] = 'Unauthorized access to a restricted area';
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $custom = DB::table('violations')->where('violation_name', $payload['other_violation'])->first();
    $this->assertDatabaseHas('violation_reports', ['violation_id' => $custom->violation_id, 'classification' => 'Unclassified', 'status' => 'Pending Verification', 'record_id' => null]);
    $this->assertDatabaseCount('violation_reports', 3);
    $this->assertDatabaseCount('records', 2);
    $this->get('/guard/reports/create')->assertDontSee($payload['other_violation']);
    DB::table('violations')->where('violation_id', $violation)->update(['violation_weight' => 'MAJOR Set A']);
    $payload['violation_id'] = $violation;
    $payload['submission_token'] = (string) Str::uuid();
    $this->post('/guard/reports', $payload)->assertRedirect(route('guard.dashboard'));
    $this->assertDatabaseHas('violation_reports', ['submission_token' => $payload['submission_token'], 'classification' => 'Major', 'status' => 'Pending Verification']);
    $this->assertDatabaseCount('records', 2);
});

test('sidebar highlights notices for meeting and deadline filters only', function () {
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1]);
    foreach (['?status=Meeting%20Scheduled', '?status=Notice%20Sent', '?due=24h', '', '?status=Confirmed'] as $query) {
        $html = $this->get('/admin/cases'.$query)->assertOk()->getContent();
        preg_match_all('/<a class="nav-link is-active"[^>]*>(.*?)<\/a>/s', $html, $matches);
        expect($matches[1])->toHaveCount(1);
        expect(trim(strip_tags($matches[1][0])))->toBe(in_array($query, ['', '?status=Confirmed']) ? 'Violation Cases' : 'Notices &amp; Deadlines');
    }
});

test('guard catalog contains only routine security and visible conduct violations', function () {
    $included = ['Improper attire', 'Unbecoming Behavior', 'Excessive Noise', 'Littering / Spitting', 'Smoking on Campus', 'Possession / Use of Alcohol', 'Possession of Bladed Weapons', 'Unlawful Possession of Firearms', 'Provocation to Fight'];
    $excluded = ['Academic Dishonesty - Cheating', 'Classroom Rules Violation', 'Bullying / Intimidation', 'Defiance of University Authority', 'Drug Possession / Use', 'High Crime (Rape / Arson / Robbery)', 'Involvement in Hazing', 'Lewd / Immoral Conduct', 'Slight Physical Assault', 'Theft of Personal Property', 'Unauthorized Posting', 'Vandalism'];
    foreach (array_merge($included, $excluded) as $name) {
        DB::table('violations')->insert(['violation_name' => $name, 'violation_weight' => 'Minor']);
    }
    $actual = GuardViolationCatalog::query()->pluck('violation_name')->all();
    expect($actual)->toEqualCanonicalizing($included);
    foreach (['guard', 'admin'] as $role) {
        $response = $this->withSession(['svrms_role' => $role, 'svrms_id' => 1])->get('/'.$role.'/reports/create')->assertOk();
        foreach ($included as $name) {
            $response->assertSee($name);
        }
        foreach ($excluded as $name) {
            $response->assertDontSee($name);
        }
    }
});

test('incident intake rejects future dates and accepts today and past dates', function () {
    $this->travelTo(Carbon::parse('2026-10-13 00:30:00', 'Asia/Singapore'));
    $department = DB::table('departments')->insertGetId(['department_name' => 'Engineering'], 'department_id');
    $program = DB::table('programs')->insertGetId(['department_id' => $department, 'program' => 'BS IT'], 'program_id');
    DB::table('students')->insert(['program_id' => $program, 'student_name' => 'Student', 'sr_code' => '26-12345', 'gsuite' => 'student@example.test', 'password' => 'unused']);
    $guard = DB::table('guards')->insertGetId(['name' => 'Guard', 'email' => 'guard@example.test', 'password' => 'unused'], 'guard_id');
    $violation = DB::table('violations')->insertGetId(['violation_name' => 'Uniform', 'violation_weight' => 'Minor'], 'violation_id');
    foreach (['guard', 'admin'] as $role) {
        $this->withSession(['svrms_role' => $role, 'svrms_id' => $guard]);
        $this->get('/'.$role.'/reports/create')->assertOk()->assertSee('max="2026-10-13T23:59"', false);
        $payload = ['submission_token' => (string) Str::uuid(), 'sr_code' => '26-12345', 'violation_id' => $violation, 'incident_at' => '2026-10-14T00:00', 'location' => 'Gate', 'incident_statement' => 'Incomplete uniform.'];
        if ($role === 'admin') {
            $payload['guard_id'] = $guard;
        }
        $this->post('/'.$role.'/reports', $payload)->assertSessionHasErrors('incident_at');
        foreach (['2026-10-13T23:59', '2026-10-12T10:00'] as $date) {
            $payload['incident_at'] = $date;
            $payload['submission_token'] = (string) Str::uuid();
            $this->post('/'.$role.'/reports', $payload)->assertRedirect(route($role.'.dashboard'))->assertSessionHasNoErrors();
        }
    }
    $this->assertDatabaseCount('violation_reports', 4);
    $this->travelBack();
});

test('dashboard prioritizes returned cases and separates today and overdue meetings', function () {
    $this->travelTo(Carbon::parse('2026-10-13 12:00:00', 'Asia/Singapore'));
    $department = DB::table('departments')->insertGetId(['department_name' => 'Engineering'], 'department_id');
    $program = DB::table('programs')->insertGetId(['department_id' => $department, 'program' => 'BS IT'], 'program_id');
    $student = DB::table('students')->insertGetId(['program_id' => $program, 'student_name' => 'Dashboard Student', 'sr_code' => '26-99999', 'gsuite' => 'dashboard@example.test', 'password' => 'unused'], 'student_id');
    $guard = DB::table('guards')->insertGetId(['name' => 'Guard', 'email' => 'guard@example.test', 'password' => 'unused'], 'guard_id');
    $violation = DB::table('violations')->insertGetId(['violation_name' => 'Uniform', 'violation_weight' => 'Minor'], 'violation_id');
    foreach ([['Returned for Clarification', null], ['Meeting Scheduled', '2026-10-13 09:00:00'], ['Meeting Scheduled', '2026-10-13 15:00:00'], ['Confirmed', null]] as [$status,$meeting]) {
        DB::table('violation_reports')->insert(['student_id' => $student, 'guard_id' => $guard, 'violation_id' => $violation, 'classification' => 'Major', 'incident_at' => '2026-10-12 10:00:00', 'location' => 'Gate', 'incident_statement' => 'Test', 'status' => $status, 'meeting_at' => $meeting, 'submission_token' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
    }
    $this->withSession(['svrms_role' => 'admin', 'svrms_id' => 1])->get('/admin/dashboard')->assertOk()
        ->assertSee('Dashboard Student')->assertSee('Monthly Reports')->assertSee('Notification Status')->assertSee('Recent Activity')
        ->assertViewHas('returnedCount', 1)->assertViewHas('overdueCount', 1)
        ->assertViewHas('reviewQueue', fn ($rows) => $rows->count() === 1 && $rows->first()->status === 'Returned for Clarification')
        ->assertViewHas('todayMeetings', fn ($rows) => $rows->count() === 2)
        ->assertViewHas('months', fn ($rows) => $rows->sum('count') === 4)
        ->assertViewHas('stats', fn ($stats) => $stats['pending'] === 3);
    $this->get('/admin/cases?pending=1')->assertOk()->assertViewHas('cases', fn ($rows) => $rows->total() === 3);
    $this->get('/admin/cases?month=2026-10')->assertOk()->assertViewHas('cases', fn ($rows) => $rows->total() === 4);
    $this->get('/admin/cases?month=2026-09')->assertOk()->assertViewHas('cases', fn ($rows) => $rows->total() === 0);
    $this->getJson('/admin/cases?month=2026-99')->assertUnprocessable();
    $this->travelBack();
});
