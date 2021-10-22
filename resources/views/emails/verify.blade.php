@component('mail::message')
# Verify Email

Click on the button below to Verify Email

@component('mail::button', ['url' => 'http://127.0.0.1:8000/api/email/Verify?token='.$token])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
