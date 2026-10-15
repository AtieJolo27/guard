@php($admin = session('svrms_role') === 'admin')
@php($noticesActive = request()->routeIs('admin.cases.index') && (in_array(request()->query('status'), ['Notice Sent', 'Meeting Scheduled'], true) || request()->query('due') === '24h'))
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Student Discipline Office | BatStateU</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="office-body"><header class="office-header"><a class="university-brand" href="{{ route($admin ? 'admin.dashboard' : 'guard.dashboard') }}"><img src="{{ asset('Batangas_State_Logo.png') }}" alt="Batangas State University"><span><strong>Batangas State University</strong><small>ARASOF-NASUGBU <i></i> STUDENT DISCIPLINE OFFICE</small></span></a><div class="header-tools"><x-office-icon name="calendar"/><time data-office-clock>{{ now()->timezone('Asia/Singapore')->format('l, F d, Y') }}<br>{{ now()->timezone('Asia/Singapore')->format('h:i A') }}</time>@if($admin) @include('admin.insights-menu') @endif<details class="account-menu"><summary aria-label="Account menu"><span class="account-avatar"><x-office-icon name="user"/></span><span class="account-label">{{ $admin ? 'Admin' : 'Guard' }}</span><span class="account-chevron" aria-hidden="true"></span></summary><div class="account-dropdown"><div class="account-profile"><span class="account-avatar"><x-office-icon name="user"/></span><div><p>{{ session('svrms_name') }}</p><span class="account-role">{{ $admin ? 'Administrator' : 'Security Guard' }}</span></div></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><svg class="signout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H5v14h4m5-14 7 7-7 7m-5-7h12"/></svg>Sign out</button></form></div></details></div></header>
<button class="mobile-nav-toggle" type="button" aria-controls="office-sidebar" aria-expanded="false"><x-office-icon name="menu"/> Menu</button>
<aside class="office-sidebar" id="office-sidebar"><nav aria-label="Main navigation"><a class="nav-link {{ request()->routeIs('*.dashboard') ? 'is-active' : '' }}" href="{{ route($admin ? 'admin.dashboard' : 'guard.dashboard') }}"><x-office-icon name="home"/>Dashboard</a>
@if($admin)
<a class="nav-link {{ request()->routeIs('admin.cases.*') && !$noticesActive ? 'is-active' : '' }}" href="{{ route('admin.cases.index') }}"><x-office-icon name="folder"/>Violation Cases</a>
<a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}" href="{{ route('admin.reports.create') }}"><x-office-icon name="plus"/>Record Violation</a>
<a class="nav-link {{ request()->route('page') === 'students' ? 'is-active' : '' }}" href="{{ route('admin.office', 'students') }}"><x-office-icon name="users"/>Students</a>
<a class="nav-link {{ $noticesActive ? 'is-active' : '' }}" href="{{ route('admin.cases.index', ['status'=>'Meeting Scheduled']) }}"><x-office-icon name="mail"/>Notices &amp; Deadlines</a>
<a class="nav-link {{ request()->route('page') === 'categories' ? 'is-active' : '' }}" href="{{ route('admin.office', 'categories') }}"><x-office-icon name="list"/>Violation Categories</a>
<a class="nav-link {{ request()->route('page') === 'reports' ? 'is-active' : '' }}" href="{{ route('admin.office', 'reports') }}"><x-office-icon name="chart"/>Reports &amp; Analytics</a>
<a class="nav-link {{ request()->route('page') === 'audit' ? 'is-active' : '' }}" href="{{ route('admin.office', 'audit') }}"><x-office-icon name="history"/>Audit Logs</a>
<a class="nav-link {{ request()->route('page') === 'settings' ? 'is-active' : '' }}" href="{{ route('admin.office', 'settings') }}"><x-office-icon name="settings"/>System Settings</a>@else<a class="nav-link {{ request()->routeIs('guard.reports.*') ? 'is-active' : '' }}" href="{{ route('guard.reports.create') }}"><x-office-icon name="plus"/>Record Violation</a><a class="nav-link" href="{{ route('guard.dashboard') }}#recent-reports"><x-office-icon name="folder"/>Recent Reports</a>@endif</nav></aside>
<div class="notification-stack" aria-label="Notifications">
@if(session('success'))
<div class="office-toast" role="status" data-notification>
<span class="toast-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 12 4 4 8-8"/></svg></span>
<div class="toast-copy"><strong>Success</strong><p>{{ session('success') }}</p></div>
<button class="toast-dismiss" type="button" aria-label="Dismiss notification" data-dismiss-notification><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="m7 7 10 10M17 7 7 17"/></svg></button>
</div>
@endif
@if($errors->any())
<div class="office-toast toast-error" role="alert" data-notification>
<span class="toast-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 7v6m0 4h.01"/></svg></span>
<div class="toast-copy"><strong>Please check your submission</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
<button class="toast-dismiss" type="button" aria-label="Dismiss notification" data-dismiss-notification><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="m7 7 10 10M17 7 7 17"/></svg></button>
</div>
@endif
</div>
<main class="office-main">{{ $slot }}</main></body></html>