@component('mail::message')

# Hello, {{ $User->first_name .' '. $User->last_name}}!

@php
    $url= url('admin/password/reset/'.$Token);
@endphp

@component('mail::panel')
    We got a new password reset request. Are you sure you want to change the password?
    Click the button below to confirm password reset.
@endcomponent

@component('mail::panel')
    Your password reset link will expire in 30min.
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
    Confirm Password reset
@endcomponent

If you are watching this by mistake please ignore this email.

@component('mail::panel')
    <p>If you are having trouble with the verify button above you may click the link below.</p>
    {{ $url }}
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent

