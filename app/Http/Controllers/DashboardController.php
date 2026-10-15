<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function guard()
    {
        return view('guard.dashboard', ['recent' => DB::table('violation_reports')
            ->join('students', 'students.student_id', '=', 'violation_reports.student_id')
            ->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')
            ->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'violations.violation_name')
            ->where('guard_id', session('svrms_id'))->latest('violation_reports.created_at')->limit(6)->get()]);
    }

    public function admin()
    {
        $today = now()->timezone('Asia/Singapore');
        $months = collect(range(5, 0))->map(function ($offset) use ($today) {
            $start = $today->copy()->startOfMonth()->subMonths($offset);
            return ['label' => $start->format('M'), 'full' => $start->format('F Y'), 'period' => $start->format('Y-m'), 'count' => DB::table('violation_reports')
                ->where('created_at', '>=', $start->copy()->utc())
                ->where('created_at', '<', $start->copy()->addMonth()->utc())->count()];
        });
        return view('admin.dashboard', [
            ...\App\Support\AdminInsights::data(),
            'activity' => DB::table('report_actions')->join('violation_reports', 'violation_reports.id', '=', 'report_actions.violation_report_id')->join('students', 'students.student_id', '=', 'violation_reports.student_id')->select('report_actions.*', 'students.student_name')->latest('report_actions.created_at')->limit(5)->get(),
            'months' => $months,
            'trendMax' => max(1, $months->max('count')),
            'stats' => [
                'total' => DB::table('violation_reports')->count(),
                'minor' => DB::table('violation_reports')->where('classification', 'Minor')->where('status', 'Confirmed')->count(),
                'major' => DB::table('violation_reports')->where('classification', 'Major')->where('status', 'Confirmed')->count(),
                'confirmed' => DB::table('violation_reports')->where('status', 'Confirmed')->count(),
                'pending' => DB::table('violation_reports')->whereIn('status', ['Pending Verification', 'Notice Sent', 'Meeting Scheduled', 'Under Review', 'Returned for Clarification'])->count(),
                'notices' => DB::table('notification_deliveries')->where('recipient_type', 'student')->where('status', 'sent')->count(),
                'scheduled' => DB::table('violation_reports')->where('status', 'Meeting Scheduled')->count(),
                'due' => DB::table('violation_reports')->where('status', 'Meeting Scheduled')->whereBetween('meeting_at', [now(), now()->addDay()])->count(),
            ],
            'recent' => DB::table('violation_reports')
                ->join('students', 'students.student_id', '=', 'violation_reports.student_id')
                ->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')
                ->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'violations.violation_name')
                ->latest('violation_reports.created_at')->limit(3)->get(),
        ]);
    }
}
