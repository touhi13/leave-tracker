<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Verification</title>
</head>

<body>
    <p>Dear {{ $leaveRequest->name }},</p>

    <p>Your leave request for the following details:</p>

    <ul>
        <li><strong>Leave Type:</strong> {{ $leaveRequest->leave_type }}</li>
        <li><strong>Start Date:</strong> {{ $leaveRequest->start_date }}</li>
        <li><strong>End Date:</strong> {{ $leaveRequest->end_date }}</li>
        <li><strong>Reason:</strong> {{ $leaveRequest->reason }}</li>
    </ul>

    @if ($leaveRequest->status === 'Approved')
        <p>Your leave request has been approved. Please plan accordingly.</p>
    @elseif ($leaveRequest->status === 'Rejected')
        <p>Unfortunately, your leave request has been rejected. Please contact HR for further assistance.</p>
    @else
        <p>Your leave request status has been updated. Please check your account for details.</p>
    @endif

    <p>Thank you for your understanding.</p>

    <p>Best regards,<br>
        Leave Tracker</p>
</body>

</html>
