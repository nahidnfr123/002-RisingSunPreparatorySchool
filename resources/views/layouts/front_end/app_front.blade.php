<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="icon" href="{{ asset('asset_front/img/favicon.png" type="image/png') }}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rising Sun Preparatory School') }} | {{ $pageName }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('asset_front/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ asset('asset_front/css/flaticon.css') }}"/>
    <link rel="stylesheet" href="{{ asset('asset_front/css/themify-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('asset_front/vendors/owl-carousel/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('asset_front/vendors/nice-select/css/nice-select.css') }}"/>
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('asset_front/css/style.css') }}"/>

    <link rel="stylesheet" href="{{ asset('commonAsset/unit.css') }}" />
    <link rel="stylesheet" href="{{ asset('asset_backend/docs/assets/plugins/fontawesome-free/css/all.css') }}" />


    <link rel="stylesheet" href="{{ asset('asset_front/css/main.css') }}"/>

    @yield('head')
</head>

<body  id="app">
<div class="col-12 text-center bg-dark text-white text-bold" style="font-size:12px;"> Laravel 6.* Project Demo (Rising sun school), (Project 002) </div>
<!-- Main body container Starts-->
<div id="Wrapper">
<!--================ Start Header Menu Area =================-->
<header class="Top_Header bg-white p-l-20 p-r-20" id="Header">
    <div class="LogoDiv">
        <a href="{{ route('index') }}" title="Home Page">
           <h1 style="line-height:60px;">Rising Sun</h1>
        </a>
    </div>

    {{-- Prevent undefined varriable title! Error. --}}
    @if(!isset($title)) {{$title=''}} @endif
    <div class="NavMenu_Container">
        <nav id="NavMenu" class="NavMenu">
            <ul class="Nav_Ul">
                <li><a href="{{ route('index') }}" class="@if($pageName === 'Home' || $pageName === '/'){{ 'Active' }}@endif"> <i class="fas fa-home"></i> Home </a></li>
                <span class="V-Bar"> | </span>
                <li><a href="{{ route('post') }}" class="@if($pageName === 'Post'){{ 'Active' }}@endif"> <i class="fas fa-"></i> Post </a></li>
                <span class="V-Bar"> | </span>
                <li><a href="{{ route('event') }}" class="@if($pageName === 'Event'){{ 'Active' }}@endif"> <i class="far fa-calendar-alt"></i> Event </a></li>
                <span class="V-Bar"> | </span>
                <li><a href="{{ route('gallery') }}" class="@if($pageName === 'Gallery'){{ 'Active' }}@endif"> <i class="fa fa-images"></i> Gallery </a></li>
                <span class="V-Bar"> | </span>
                <li><a href="{{ route('contact') }}"  class="@if($pageName === 'Contact Us' || $title === '/'){{ 'Active' }}@endif"> <i class="fas fa-address-book"></i> Contact Us </a></li>
                <span class="V-Bar"> | </span>
                <li><a href="{{ route('about') }}" class="@if($pageName === 'About' || $title === '/'){{ 'Active' }}@endif"> <i class="fas fa-question"></i> About </a></li>
            </ul>
        </nav>
        <span class="navTrigger">
            <i></i>
            <i></i>
            <i></i>
        </span>
    </div>
</header>
<!--================ End Header Menu Area =================-->



@yield('main_content')



<!--================ Start footer Area  =================-->
<footer class="footer-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 single-footer-widget pull-right">
                <ul class="footer_Ul">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('post') }}">Post</a></li>
                    <li><a href="{{ route('event') }}">Event</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                </ul>

                <div class="row footer-bottom d-flex justify-content-between">
                    <p class="col-lg-12 col-sm-12 footer-text m-0 text-white" style="font-family: monospace;">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());

                        </script> All rights reserved | This website is made with <i class="ti-heart" aria-hidden="true"></i> by <a href="https://nahidnfr.com/" target="_blank">Nahid Ferdous</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 text-center text-white">
                <div class="contact_info m-auto text-left">
                    @if($ContactDetails !== null)
                        <div class="text-white">
                            <i class="ti-home float-left m-r-15"></i>
                            <h6 class="text-white">{{ $ContactDetails->address }}, {{ $ContactDetails->city }}</h6>
                            <p></p>
                        </div>
                        <div class="text-white">
                            <i class="ti-headphone float-left m-r-15"></i>
                            <h6><a href="tel:{{ $ContactDetails->phone }}">{{ $ContactDetails->phone }}</a></h6>
                            <p>Mon to Fri 9am to 6 pm</p>
                        </div>
                        <div class=" text-white">
                            <i class="ti-email float-left m-r-15"></i>
                            <h6><a href="mailto:{{ $ContactDetails->email }}">{{ $ContactDetails->email }}</a></h6>
                            <p>Send us your query anytime!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<!--================ End footer Area  =================-->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('asset_front/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('asset_front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('asset_front/js/popper.js') }}"></script>
<script src="{{ asset('asset_front/js/stellar.js') }}"></script>
<script src="{{ asset('asset_front/vendors/nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('asset_front/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('asset_front/js/owl-carousel-thumb.min.js') }}"></script>
<script src="{{ asset('asset_front/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('asset_front/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('asset_front/js/mail-script.js') }}"></script>

{{--<!--gmaps Js-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
<script src="{{ asset('asset_front/js/gmaps.min.js') }}"></script>--}}
<script src="{{ asset('asset_front/js/theme.js') }}"></script>

<script>
    $(document).ready(function () {
        // Responsive navigation bar menu ....
        $('.navTrigger').click(function () {
            $(this).toggleClass('active');
            $("#NavMenu").toggleClass("show_list");
            //$("#NavMenu").fadeIn();
            $(".navTrigger").toggleClass("light_up");
        });
    });


    // Sticky Navigation Bar.... //
    window.onscroll = function () {
        StickyNavBar();
    };



    function StickyNavBar() {
        let navbar = document.getElementById("Header");
        let sticky = navbar.offsetTop;

        if ($(window).width() < 600) {
            if (window.pageYOffset > 60) {
                navbar.classList.add("sticky");
            } else {
                navbar.classList.remove("sticky");
            }
        } else {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky");
            } else {
                navbar.classList.remove("sticky");
            }
        }

    }
</script>

@yield('page_level_script')

@include('components.popupMsg')


</body>

</html>
