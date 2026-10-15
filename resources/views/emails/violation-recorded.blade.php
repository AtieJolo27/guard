<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Student Violation Notice</title></head>
<body>
    <p>Dear {{ $studentName }},</p>
    <p>This is to inform you that the following violation has been recorded under your student account.</p>
    <ul>
        <li><strong>SR code:</strong> {{ $srCode }}</li>
        <li><strong>Violation:</strong> {{ $violationName }}</li>
        <li><strong>Severity:</strong> {{ $severity }}</li>
        <li><strong>Date recorded:</strong> {{ $recordedAt }}</li>
        <li><strong>Record number:</strong> {{ $recordId }}</li>
    </ul>
    <p>Please contact the school office responsible for student discipline if you have questions or believe this record is incorrect.</p>
    <p>Guard System</p>
</body>
</html>
