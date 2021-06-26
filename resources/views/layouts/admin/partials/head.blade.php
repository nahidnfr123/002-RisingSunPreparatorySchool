<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rising Sun Preparatory School') }} | {{ $pageName }}</title>

    <link rel="stylesheet" href="{{ asset('commonAsset/unit.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/popupMsg.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/my_main.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_backend/my_style.css') }}">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('asset_backend/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_backend/dist/css/jquery-ui.css') }}">


    {{--<link rel="stylesheet" href="{{ asset('commonAsset/kronos-date-picker.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('commonAsset/datepicker/css/datepicker.css') }}">--}}

    <!-- Google Font: Source Sans Pro -->
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script src="{{ asset('asset_backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset_backend/dist/js/ckeditor.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('commonAsset/datepicker/js/bootstrap-datepicker.js') }}"></script>--}}

    @yield('head')
</head>

<style>
    body{
        font-weight: 500;
    }
    .hideOnPhone{
        display: inline-block!important;
    }
    @media only screen and (max-width: 400px) {
        .hideOnPhone{
            display: none!important;
        }
        .ic-sing-file2{
            width:100%!important;
        }
        .ic-sing-file2 > img{
            height: 80px;
            width: 100%;
            max-width: 100%;
            object-fit: cover;
            object-position: center center;
            border-radius: 4px;
            padding: 2px;
        }
    }
</style>
