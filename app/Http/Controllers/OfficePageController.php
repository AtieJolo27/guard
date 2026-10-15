<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class OfficePageController extends Controller
{
    public function show(string $page)
    {
        $pages = [
            'students' => ['Students', 'Student directory', ['SR-Code', 'Student', 'Year', 'Status'], fn () => DB::table('students')->select('sr_code', 'student_name', 'year_level', 'disciplinary_status')->orderBy('student_name')->paginate(15)],
            'categories' => ['Violation Categories', 'University violation catalog', ['Violation', 'Classification'], fn () => DB::table('violations')->select('violation_name', 'violation_weight')->orderBy('violation_name')->paginate(15)],
            'audit' => ['Audit Logs', 'Case activity and decisions', ['Case', 'Action', 'Actor', 'Notes', 'Date'], fn () => DB::table('report_actions')->select('violation_report_id', 'action', 'actor_type', 'notes', 'created_at')->latest('created_at')->paginate(15)],
        ];
        if ($page === 'reports') {
            return view('admin.office-page', ['title' => 'Reports & Analytics', 'subtitle' => 'Violation cases by classification and status', 'columns' => ['Classification', 'Status', 'Cases'], 'rows' => DB::table('violation_reports')->select('classification', 'status')->selectRaw('COUNT(*) as cases')->groupBy('classification', 'status')->orderBy('classification')->paginate(15)]);
        }
        if ($page === 'settings') {
            return view('admin.office-page', ['title' => 'System Settings', 'subtitle' => 'Current office configuration', 'columns' => ['Setting', 'Value'], 'rows' => collect([(object) ['setting' => 'Office', 'value' => 'Student Discipline Office'], (object) ['setting' => 'Campus', 'value' => 'ARASOF-Nasugbu'], (object) ['setting' => 'Display timezone', 'value' => 'Asia/Singapore'], (object) ['setting' => 'Signed in as', 'value' => session('svrms_name')]])]);
        }
        abort_unless(isset($pages[$page]), 404);
        [$title, $subtitle, $columns, $query] = $pages[$page];
        return view('admin.office-page', compact('title', 'subtitle', 'columns') + ['rows' => $query()]);
    }
}
