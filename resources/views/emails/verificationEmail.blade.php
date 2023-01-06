@component('mail::message')
# Activate your account

Please click the link below to activate your account.

The link is valid for 48 hours.

@component('mail::button', ['url' => route('activateAccount',$verificationCode),'color' => 'success'])
Activate account
@endcomponent

Best regards,<br>
The Force Track
@endcomponent