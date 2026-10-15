Dear {{ $recipientName }},

A meeting has been scheduled regarding case {{ $notice['reference'] }}.

Student: {{ $notice['report']->student_name }}
SR-Code: {{ $notice['report']->sr_code }}
Reported incident: {{ $notice['report']->violation_name }}
Meeting: {{ $notice['meeting']->format('l, F j, Y, g:i A') }} (Philippine time, UTC+8)
Venue: Student Discipline Office, Batangas State University, ARASOF-Nasugbu Campus

Please read the attached PDF meeting notice for the complete details. Bring your student ID and relevant documents. If you cannot attend, contact or visit the Student Discipline Office before the meeting.

This meeting is to clarify a reported incident; it is not a finding of a violation.

Student Discipline Office
Batangas State University, ARASOF-Nasugbu Campus
