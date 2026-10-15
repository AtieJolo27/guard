<!doctype html><html><head><meta charset="utf-8"><style>
@font-face{font-family:LetterheadTimes;font-weight:normal;src:url("{{ resource_path('notice-assets/times.ttf') }}")}@font-face{font-family:LetterheadTimes;font-weight:bold;src:url("{{ resource_path('notice-assets/times-bold.ttf') }}")}@page{margin:65px 65px 65px}body{font-family:DejaVu Sans,sans-serif;font-size:10.5px;line-height:1.65;color:#111}header{position:relative;text-align:center;min-height:118px;border-bottom:2px solid #444;padding-bottom:12px;font-family:LetterheadTimes,serif}header table{width:100%;border-collapse:collapse}header td{vertical-align:middle;padding:0;text-align:center}header .seal-cell{width:110px;padding-right:10px}header img{width:110px}header .republic{font-size:12px;font-weight:bold}header>p:first-of-type{font-size:12px;font-weight:bold}header p{margin:0;line-height:1.4}.university{font-size:15px;font-weight:bold;white-space:nowrap}.tagline{color:#d2363b;font-weight:bold;font-size:11px;font-family:DejaVu Sans,sans-serif;margin:3px 0}.campus{font-weight:bold;font-size:11px}.contact{font-size:9px}.office-heading{font-family:LetterheadTimes,serif;font-size:14px;font-weight:bold;text-align:center;margin:8px 0 0}h1{text-align:center;font-size:16px;margin:20px 0 18px}p{margin:12px 0}.meta{width:100%;border-collapse:collapse;margin:18px 0}.meta td{padding:7px 10px;border:1px solid #ddd;vertical-align:top}.meta td:first-child{width:95px;background:#f7f7f7;font-weight:bold}.recipient{margin-top:20px}.signature{margin-top:30px}.small{font-size:9px;color:#555}footer{position:fixed;bottom:-30px;left:0;right:0;text-align:center;font-size:9px;color:#d2363b;border-top:1px solid #ccc;padding-top:8px}
.template-header{position:fixed;left:-72pt;top:-72pt;width:612pt;height:175pt}
.template-footer{position:fixed;left:-72pt;bottom:-46pt;width:612pt;height:40pt}
@page{margin:72pt}body{font-family:LetterheadTimes,serif;font-size:11pt;line-height:1.35}
.office-heading{text-align:left;font-size:11pt;margin:108pt 0 15pt}
h1{text-align:left;font-family:LetterheadTimes,serif;font-size:11pt;margin:14pt 0 5pt}
p{margin:12pt 0}.recipient{margin-top:12pt}.meta{margin:16pt 0 25pt;font-size:11pt;line-height:1.3}.meta td{padding:1px 5px;border:1px solid #333}.meta td:first-child{width:107px;background:white}.signature{margin-top:30px}.small{font-size:8px;margin-top:24px}
</style></head><body>
<img class="template-header" src="{{ $templateHeader }}" alt="Original university letterhead"><div class="office-heading">Student Discipline Office of ARASOF-Nasugbu Campus</div>
<h1>Notice of Meeting</h1>
<p>{{ $issued }}<br><strong>Case reference: {{ $reference }}</strong></p>
<p class="recipient"><strong>{{ $report->student_name }}</strong><br>SR-Code: {{ $report->sr_code }}<br>{{ $studentEmail }}</p>
<p>Dear {{ $report->student_name }},</p>
<p>You are requested to attend a meeting at the Student Discipline Office to clarify a reported incident. Please attend at the date and time below.</p>
<table class="meta"><tr><td>Meeting date</td><td>{{ $meeting->format('l, F j, Y') }}</td></tr><tr><td>Meeting time</td><td>{{ $meeting->format('g:i A') }} (Philippine time, UTC+8)</td></tr><tr><td>Venue</td><td>Student Discipline Office, Batangas State University, ARASOF-Nasugbu Campus</td></tr><tr><td>Reported violation</td><td>{{ $report->violation_name }}</td></tr><tr><td>Incident date</td><td>{{ $incident->format('F j, Y, g:i A') }} (Philippine time)</td></tr><tr><td>Incident location</td><td>{{ $report->location }}</td></tr></table>
<p>This notice is an invitation to clarify the facts and provide your account. It does not constitute a finding that a violation has occurred.</p>
<p>Please bring your student ID and any documents relevant to the incident. If you cannot attend, contact or visit the Student Discipline Office before the scheduled meeting to discuss arrangements. Keep this notice and quote the case reference when making an inquiry.</p>
<p class="signature">Respectfully,<br><strong>Student Discipline Office</strong><br>Batangas State University<br>ARASOF-Nasugbu Campus</p>
<p class="small">System-generated meeting notice. This document is intended for the named recipient.</p>
<img class="template-footer" src="{{ $templateFooter }}" alt="Leading Innovations, Transforming Lives, Building the Nation">
</body></html>
