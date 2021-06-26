<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Link</title>
</head>
<style>

</style>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card animated fadeInDown">
                <div class="card-header">
                    <h1 class="text-center">Password reset confirmation.</h1>
                </div>
                <div class="card-body">
                    <div><h2>Hello, {{ $User['first_name'] .' '. $User['last_name']}}</h2></div>
                    <div class="p-r-10 p-l-10">
                        <p>We got a new password reset request. Are you sure you want to change the password.
                        Click the link below to confirm password reset.</p>
                        <p>
                            Your password reset link will expire in 30min.
                        </p>
                    </div>
                    <div class="text-center">
                        <a href="{{ url('admin/password/reset/'.$Token) }}" class="btn btn-info">Confirm Password Reset</a>
                    </div>
                </div>
                <div class="card-footer">
                    <br>
                    Thanks,<br>
                    {{ config('app.name') }}
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>




{{--@component('mail::message')
    Hello, {{ $User->first_name .' '. $User->last_name}}!
    @php
        $url= url('admin/password/reset/'.$Token);
    @endphp

    @component('mail::panel')
        <div>
            <p>We got a new password reset request. Are you sure you want to change the password?
                Click the button below to confirm password reset.</p>
            <p>
                Your password reset link will expire in 30min.
            </p>
        </div>
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
@endcomponent--}}


