<?php

namespace App\Http\Controllers;

use App\Mail\MeetingNotice;
use App\Services\MeetingNoticePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminCaseController extends Controller
{
    public function index(Request $r)
    {
        $r->validate(['month' => 'nullable|date_format:Y-m', 'search' => 'nullable|string|max:200', 'status' => 'nullable|string|in:Pending Verification,Notice Sent,Meeting Scheduled,Under Review,Confirmed,Dismissed,Returned for Clarification', 'classification' => 'nullable|in:Minor,Major,Unclassified', 'pending' => 'nullable|boolean', 'due' => 'nullable|in:24h', 'page' => 'nullable|integer|min:1|max:100000']);
        $q = DB::table('violation_reports')->join('students', 'students.student_id', '=', 'violation_reports.student_id')->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')->join('guards', 'guards.guard_id', '=', 'violation_reports.guard_id')->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'violations.violation_name', 'guards.name as guard_name');
        if ($r->filled('month')) {
            $month = Carbon::createFromFormat('!Y-m', $r->input('month'), 'Asia/Singapore');
            $q->where('violation_reports.created_at', '>=', $month->copy()->utc())->where('violation_reports.created_at', '<', $month->copy()->addMonth()->utc());
        }
        if ($r->filled('classification') && in_array($r->classification, ['Minor', 'Major'])) {
            $q->where('classification', $r->classification);
        } if ($r->due === '24h') {
            $q->where('status', 'Meeting Scheduled')->whereBetween('meeting_at', [now(), now()->addDay()]);
        } if ($r->boolean('pending')) {
            $q->whereIn('status', ['Pending Verification', 'Notice Sent', 'Meeting Scheduled', 'Under Review', 'Returned for Clarification']);
        } if ($r->filled('status')) {
            $q->where('status', $r->status);
        } if ($r->filled('search')) {
            $q->where(fn ($s) => $s->where('student_name', 'like', '%'.$r->search.'%')->orWhere('sr_code', 'like', '%'.$r->search.'%')->orWhere('violation_name', 'like', '%'.$r->search.'%')->orWhere('guards.name', 'like', '%'.$r->search.'%'));
        }

        return view('admin.cases', ['cases' => $q->latest('violation_reports.incident_at')->paginate(15)->withQueryString()]);
    }

    public function show(int $id)
    {
        $report = DB::table('violation_reports')->join('students', 'students.student_id', '=', 'violation_reports.student_id')->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')->join('guards', 'guards.guard_id', '=', 'violation_reports.guard_id')->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'students.gsuite', 'violations.violation_name', 'guards.name as guard_name', 'guards.email as guard_email')->where('violation_reports.id', $id)->first();
        abort_unless($report, 404);

        return view('admin.case-show', ['report' => $report, 'deliveries' => DB::table('notification_deliveries')->where('violation_report_id', $id)->get(), 'actions' => DB::table('report_actions')->where('violation_report_id', $id)->oldest()->get()]);
    }

    public function update(Request $r, int $id)
    {
        $response = Cache::lock('case-action:'.$id, 120)->get(fn () => $this->applyUpdate($r, $id));
        abort_if($response === false, 409, 'This case is being updated. Please retry shortly.');

        return $response;
    }

    private function applyUpdate(Request $r, int $id)
    {
        $report = DB::table('violation_reports')->where('id', $id)->first();
        abort_unless($report, 404);
        abort_if(in_array($report->status, ['Confirmed', 'Dismissed']), 422, 'Closed cases cannot be changed.');
        $data = $r->validate([
            'action' => 'required|in:notice,review,return,confirm,dismiss',
            'notes' => 'nullable|string|max:5000',
            'meeting_at' => $r->input('action') === 'notice'
                ? 'required|date_format:Y-m-d\\TH:i|after:'.now()->timezone('Asia/Singapore')->format('Y-m-d H:i:s')
                : 'exclude',
        ]);
        $sameMeeting = $data['action'] === 'notice'
            && $report->status === 'Meeting Scheduled'
            && $report->meeting_at
            && Carbon::parse($report->meeting_at)->format('Y-m-d H:i') === Carbon::parse($data['meeting_at'])->format('Y-m-d H:i');
        if ($sameMeeting && DB::table('notification_deliveries')->where('violation_report_id', $id)->where('notification_type', 'meeting')->whereIn('status', ['sent', 'logged'])->count() === 2) {
            return back()->with('success', 'This meeting is already scheduled and its notices have been processed.');
        }
        DB::transaction(function () use ($data, $id) {
            $report = DB::table('violation_reports')->where('id', $id)->lockForUpdate()->first();
            abort_unless($report, 404);
            abort_if(in_array($report->status, ['Confirmed', 'Dismissed']), 422, 'Closed cases cannot be changed.');
            $status = ['notice' => 'Meeting Scheduled', 'review' => 'Under Review', 'return' => 'Returned for Clarification', 'confirm' => 'Confirmed', 'dismiss' => 'Dismissed'][$data['action']];
            $values = [
                'status' => $status,
                'investigation_notes' => $data['notes'] ?? $report->investigation_notes,
                'meeting_at' => $data['action'] === 'notice' ? $data['meeting_at'] : $report->meeting_at,
                'reviewed_by' => session('svrms_id'), 'reviewed_at' => now(), 'updated_at' => now(),
            ];
            if (in_array($data['action'], ['dismiss', 'confirm'])) {
                $values['decision_reason'] = $data['notes'] ?? null;
            }
            if ($data['action'] === 'confirm') {
                $values['record_id'] = DB::table('records')->insertGetId(['student_id' => $report->student_id, 'violation_id' => $report->violation_id, 'created_at' => now(), 'updated_at' => now()]);
            }
            DB::table('violation_reports')->where('id', $id)->update($values);
            DB::table('report_actions')->insert(['violation_report_id' => $id, 'action' => $status, 'actor_type' => 'admin', 'actor_id' => session('svrms_id'), 'notes' => $data['notes'] ?? null, 'created_at' => now(), 'updated_at' => now()]);
        });
        if ($data['action'] === 'notice') {
            $result = $this->sendMeeting($id, (bool) $sameMeeting);
            if ($result['failed'] > 0) {
                return back()->withErrors(['notification' => 'Meeting scheduled, but '.$result['failed'].' email notification(s) could not be sent. Check notification status before retrying.']);
            }

            return back()->with('success', $result['logged'] > 0 ? 'Meeting scheduled. Notices were logged only; no email was delivered.' : 'Meeting scheduled. Email notices with the PDF attachment were accepted by the mail service.');
        }

        return back()->with('success', 'Case updated.');
    }

    private function sendMeeting(int $id, bool $retryOnlyFailed = false): array
    {
        $report = DB::table('violation_reports')
            ->join('students', 'students.student_id', '=', 'violation_reports.student_id')
            ->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')
            ->join('guards', 'guards.guard_id', '=', 'violation_reports.guard_id')
            ->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'violations.violation_name', 'guards.name as guard_name', 'guards.email as guard_email')
            ->where('violation_reports.id', $id)->first();
        $generator = app(MeetingNoticePdf::class);
        $notice = $generator->data($report);
        $result = ['sent' => 0, 'failed' => 0, 'logged' => 0];
        $pdf = null;
        foreach ([['student', $notice['studentEmail'], $report->student_name], ['guard', $report->guard_email, $report->guard_name]] as [$type,$email,$name]) {
            if ($retryOnlyFailed) {
                $previous = DB::table('notification_deliveries')->where('violation_report_id', $id)->where('recipient_type', $type)->where('notification_type', 'meeting')->where('recipient_email', $email)->first();
                if ($previous && in_array($previous->status, ['sent', 'logged'])) {
                    $result[$previous->status]++;

                    continue;
                }
            }
            $delivery = ['recipient_email' => $email ?? '', 'updated_at' => now(), 'created_at' => now(), 'sent_at' => null, 'error_message' => null];
            try {
                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('Recipient email address is invalid.');
                }
                $pdf ??= $generator->generate($report);
                Mail::to($email)->send(new MeetingNotice($notice, $name, $pdf));
                $status = in_array(config('mail.default'), ['log', 'array']) ? 'logged' : 'sent';
                $delivery['status'] = $status;
                $delivery['sent_at'] = $status === 'sent' ? now() : null;
                $result[$status]++;
            } catch (\Throwable $e) {
                $delivery['status'] = 'failed';
                $delivery['error_message'] = 'Notification delivery failed. Check server logs for details.';
                $result['failed']++;
                report($e);
            }
            DB::table('notification_deliveries')->updateOrInsert(
                ['violation_report_id' => $id, 'recipient_type' => $type, 'notification_type' => 'meeting'], $delivery
            );
        }

        return $result;
    }
}
