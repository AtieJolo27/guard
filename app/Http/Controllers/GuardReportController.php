<?php

namespace App\Http\Controllers;

use App\Support\GuardViolationCatalog;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GuardReportController extends Controller
{
    public function create()
    {
        return view('guard.create-report', ['violations' => GuardViolationCatalog::query()->orderBy('violation_name')->get(), 'submissionToken' => (string) Str::uuid(), 'guards' => session('svrms_role') === 'admin' ? DB::table('guards')->orderBy('name')->get() : collect()]);
    }

    public function lookup(string $code)
    {
        $student = DB::table('students')->join('programs', 'programs.program_id', '=', 'students.program_id')->join('departments', 'departments.department_id', '=', 'programs.department_id')->where('sr_code', $code)->select('student_name', 'sr_code', 'year_level', 'program', 'department_name')->first();

        return $student ? response()->json($student) : response()->json(['message' => 'No student matches this SR-Code.'], 404);
    }

    public function store(Request $r)
    {
        $data = $r->validate(['submission_token' => 'required|uuid', 'guard_id' => session('svrms_role') === 'admin' ? 'required|integer|exists:guards,guard_id' : 'prohibited', 'sr_code' => ['required', 'string', 'regex:/\\A[0-9]{2}-[0-9]{5}\\z/'], 'violation_id' => $r->input('violation_id') === 'other' ? 'required|in:other' : 'required|integer|exists:violations,violation_id', 'other_violation' => 'exclude_unless:violation_id,other|required|string|max:255', 'incident_at' => 'required|date|before_or_equal:'.now()->timezone('Asia/Singapore')->format('Y-m-d').' 23:59:59', 'location' => 'required|string|max:255', 'incident_statement' => 'required|string|max:5000']);
        $student = DB::table('students')->where('sr_code', $data['sr_code'])->first();
        abort_unless($student, 422, 'SR-Code does not match a student.');
        $violation = $data['violation_id'] === 'other' ? null : GuardViolationCatalog::query()->where('violation_id', $data['violation_id'])->first();
        if ($data['violation_id'] !== 'other' && ! $violation) {
            throw ValidationException::withMessages(['violation_id' => 'Select a violation that can be reported by a guard.']);
        } $classification = $violation ? (strtolower($violation->violation_weight) === 'unclassified' ? 'Unclassified' : (str_starts_with(strtolower($violation->violation_weight), 'major') ? 'Major' : 'Minor')) : 'Unclassified';
        $token = $data['submission_token'];
        if (DB::table('violation_reports')->where('submission_token', $token)->exists()) {
            return redirect()->route(session('svrms_role') === 'admin' ? 'admin.dashboard' : 'guard.dashboard')->with('success', 'Report already submitted.');
        } try {
            DB::transaction(function () use ($data, $student, $violation, $classification, $token) {
                if ($violation === null) {
                    $customId = DB::table('violations')->insertGetId(['violation_name' => $data['other_violation'], 'violation_weight' => 'Unclassified', 'created_at' => now(), 'updated_at' => now()], 'violation_id');
                    $violation = (object) ['violation_id' => $customId];
                } $status = $classification !== 'Minor' ? 'Pending Verification' : 'Confirmed';
                $recordId = null;
                if ($classification === 'Minor') {
                    $recordId = DB::table('records')->insertGetId(['student_id' => $student->student_id, 'violation_id' => $violation->violation_id, 'created_at' => now(), 'updated_at' => now()]);
                } $id = DB::table('violation_reports')->insertGetId(['student_id' => $student->student_id, 'guard_id' => session('svrms_role') === 'admin' ? $data['guard_id'] : session('svrms_id'), 'violation_id' => $violation->violation_id, 'record_id' => $recordId, 'classification' => $classification, 'incident_at' => $data['incident_at'], 'location' => $data['location'], 'incident_statement' => $data['incident_statement'], 'status' => $status, 'submission_token' => $token, 'created_at' => now(), 'updated_at' => now()]);
                DB::table('report_actions')->insert(['violation_report_id' => $id, 'action' => $classification !== 'Minor' ? 'Submitted for verification' : 'Minor offense recorded', 'actor_type' => session('svrms_role'), 'actor_id' => session('svrms_id'), 'created_at' => now(), 'updated_at' => now()]);
            });
        } catch (UniqueConstraintViolationException $e) {
            if (! DB::table('violation_reports')->where('submission_token', $token)->exists()) {
                throw $e;
            }

return redirect()->route(session('svrms_role') === 'admin' ? 'admin.dashboard' : 'guard.dashboard')->with('success', 'Report already submitted.');
        }

return redirect()->route(session('svrms_role') === 'admin' ? 'admin.dashboard' : 'guard.dashboard')->with('success',$classification !== 'Minor' ? 'Report submitted for Admin verification.' : 'Minor violation recorded successfully.');
    }
}
