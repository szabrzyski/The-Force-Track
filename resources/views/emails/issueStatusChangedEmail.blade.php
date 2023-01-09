@component('mail::message')
# Issue status update

The status of your issue has been updated to: {{ $newStatus }}

Click the link below to view your issue.

@component('mail::button', ['url' => route('showIssue',['issue' => $issueId]),'color' => 'success'])
View issue
@endcomponent

Best regards,<br>
The Force Track
@endcomponent