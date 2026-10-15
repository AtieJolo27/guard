<?php
namespace App\Support;
use Illuminate\Support\Facades\DB;
class AdminInsights {
    public static function data(): array {
        $today = now()->timezone('Asia/Singapore');
        $cases = fn () => DB::table('violation_reports')
            ->join('students', 'students.student_id', '=', 'violation_reports.student_id')
            ->join('violations', 'violations.violation_id', '=', 'violation_reports.violation_id')
            ->select('violation_reports.*', 'students.student_name', 'students.sr_code', 'violations.violation_name');
        return [
            'reviewQueue' => $cases()->whereIn('status', ['Pending Verification', 'Under Review', 'Returned for Clarification', 'Notice Sent'])->oldest('violation_reports.created_at')->limit(5)->get(),
            'returnedCount' => DB::table('violation_reports')->where('status', 'Returned for Clarification')->count(),
            'todayMeetings' => $cases()->where('status', 'Meeting Scheduled')->whereBetween('meeting_at', [$today->copy()->startOfDay()->format('Y-m-d H:i:s'), $today->copy()->endOfDay()->format('Y-m-d H:i:s')])->orderBy('meeting_at')->limit(5)->get(),
            'overdueCount' => DB::table('violation_reports')->where('status', 'Meeting Scheduled')->where('meeting_at', '<', $today->format('Y-m-d H:i:s'))->count(),
            'overdueMeetings' => $cases()->where('status', 'Meeting Scheduled')->where('meeting_at', '<', $today->format('Y-m-d H:i:s'))->orderBy('meeting_at')->limit(3)->get(),
            'deliveryCounts' => DB::table('notification_deliveries')->select('status')->selectRaw('COUNT(*) as total')->groupBy('status')->pluck('total', 'status'),
            'failedDeliveries' => DB::table('notification_deliveries')->latest('updated_at')->where('status', 'failed')->limit(3)->get(),
            'mailIsLog' => in_array(config('mail.default'), ['log', 'array']),
            'reviewCount' => DB::table('violation_reports')->whereIn('status',['Pending Verification','Under Review','Returned for Clarification','Notice Sent'])->count(),
            'todayCount' => DB::table('violation_reports')->where('status','Meeting Scheduled')->whereBetween('meeting_at', [$today->copy()->startOfDay()->format('Y-m-d H:i:s'), $today->copy()->endOfDay()->format('Y-m-d H:i:s')])->count(),
        ];
    }
}
