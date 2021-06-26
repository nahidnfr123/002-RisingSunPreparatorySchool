<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('commonAsset/unit.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/popupMsg.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/animate.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('asset_backend/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a><b> Admin </b> Panel </a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Log in to start your session</p>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control  @if(session()->has('Error')) is-invalid @else @error('email') is-invalid @enderror @endif" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                    @if(session()->has('Error'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ session('Error') }}</strong>
                    </span>
                    @endif

                </div>

                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8" maxlength="60" autocomplete="current-password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="icheck-primary">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-info btn-block">{{ __('Login') }}</button>
                    </div>
                </div>
                <!-- /.col -->
            </form>

            {{--<p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p>--}}
            @if (Route::has('admin.password.request'))
                <div class="col-12 text-center" style="margin-top: 20px;">
                    <a class="btn btn-link" href="{{ route('admin.password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->



<!-- jQuery -->
<script src="{{ asset('asset_backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('asset_backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Wow js -->
<script src="{{ asset('commonAsset/wow.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('asset_backend/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('asset_backend/plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('asset_backend/dist/js/adminlte.min.js') }}"></script>

@component('components.popupMsg')
@endcomponent

</body>
</html>
