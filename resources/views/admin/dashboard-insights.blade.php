<div class="insights-grid"><section class="office-panel monthly-panel" data-monthly-chart>
<div class="panel-heading"><h2>Monthly Reports</h2><span class="chart-legend"><i aria-hidden="true"></i> Submitted reports</span></div>
<p class="insight-subtitle">{{ $months->sum('count') }} reports over six months. Hover or select a month to explore.</p>
<div class="interactive-chart" role="group" aria-label="Reports submitted by month">
<div class="chart-axis" aria-hidden="true"><span>{{ $trendMax }}</span><span>{{ round($trendMax / 2, 1) }}</span><span>0</span></div>
<div class="chart-plot">
@foreach($months as $month)
<button type="button" class="month-bar-button {{ $loop->last ? 'is-selected' : '' }}" aria-pressed="{{ $loop->last ? 'true' : 'false' }}" aria-label="{{ $month['full'] }}: {{ $month['count'] }} reports" data-month-label="{{ $month['full'] }}" data-month-count="{{ $month['count'] }}" data-month-url="{{ route('admin.cases.index', ['month'=>$month['period']]) }}">
<span class="month-tooltip" aria-hidden="true">{{ $month['full'] }}<strong>{{ $month['count'] }} {{ $month['count'] === 1 ? 'report' : 'reports' }}</strong></span>
<span class="month-count" aria-hidden="true">{{ $month['count'] }}</span><span class="month-bar-track"><span class="month-bar-fill {{ $month['count'] === 0 ? 'is-empty' : '' }}" style="height:{{ $month['count'] / $trendMax * 100 }}%"></span></span><span class="month-label">{{ $month['label'] }}</span>
</button>
@endforeach
</div></div>
@php($selectedMonth = $months->last())
<div class="chart-selection"><div aria-live="polite" aria-atomic="true"><strong data-chart-month>{{ $selectedMonth['full'] }}</strong><span data-chart-count>{{ $selectedMonth['count'] }} {{ $selectedMonth['count'] === 1 ? 'report submitted' : 'reports submitted' }}</span></div><a data-chart-link href="{{ route('admin.cases.index', ['month'=>$selectedMonth['period']]) }}">View cases <span aria-hidden="true">&rarr;</span></a></div>
</section><section class="office-panel"><div class="panel-heading"><h2>Recent Activity</h2><a href="{{ route('admin.office', 'audit') }}">View audit log</a></div><div class="activity-feed">@forelse($activity as $event)<a class="activity-event" href="{{ route('admin.cases.show', $event->violation_report_id) }}"><span class="activity-dot" aria-hidden="true"></span><div><strong>{{ $event->action }}</strong><span>{{ $event->student_name }} · Case #{{ $event->violation_report_id }} · {{ ucfirst($event->actor_type) }}</span></div><time>{{ \Carbon\Carbon::parse($event->created_at)->diffForHumans() }}</time></a>@empty<p class="insight-empty">Case updates will appear here as reports are processed.</p>@endforelse</div></section></div>