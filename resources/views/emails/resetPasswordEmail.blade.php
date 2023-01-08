@component('mail::message')
# Reset your password

Please click the link below to reset your password.

The link is valid for 24 hours.

@component('mail::button', ['url' => route('setNewPassword',['verificationCode' => $verificationCode,'email' => $email]),'color' => 'success'])
Reset password
@endcomponent

Best regards,<br>
The Force Track
@endcomponent