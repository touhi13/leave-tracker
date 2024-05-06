<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Submitted</title>
</head>
<body>
    <h2>Leave Request Submitted</h2>
    
    <p>Hello {{ $leaveRequest->user->name }},</p>
    
    <p>Your leave request has been successfully submitted.</p>
    
    <p><strong>Leave Details:</strong></p>
    <ul>
        <li><strong>Type:</strong> {{ $leaveRequest->leave_type }}</li>
        <li><strong>Start Date:</strong> {{ $leaveRequest->start_date }}</li>
        <li><strong>End Date:</strong> {{ $leaveRequest->end_date }}</li>
        <li><strong>Reason:</strong> {{ $leaveRequest->reason }}</li>
    </ul>
    
    <p>We will review your request shortly. Thank you.</p>
    
    <p>Best regards,<br> Leave Tracker</p>
</body>
</html>
