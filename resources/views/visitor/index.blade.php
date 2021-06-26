@extends('layouts.front_end.app_front')
@php $pageName = 'Home'; $pageUrl = route('post'); @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_front/slider_asset/mm.css') }}"/>
    <link rel="stylesheet" href="{{ asset('asset_front/slider_asset/owl.carousel.min.css') }}">
@endsection

@php
    function truncate($text, $chars = 25) {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
        return $text;
    }
@endphp

@section('main_content')
    <!--================ Start Home Banner Area =================-->
    {{--<section class="home_banner_area">
        <div class="banner_inner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="banner_content text-center">
                            <p class="text-uppercase">
                                Best online education service In the world
                            </p>
                            <h2 class="text-uppercase mt-4 mb-5">
                                One Step Ahead This Season
                            </h2>
                            <div>
                                <a href="#" class="primary-btn2 mb-3 mb-sm-0">learn more</a>
                                <a href="#" class="primary-btn ml-sm-3 ml-0">see course</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>--}}
    <!--================ End Home Banner Area =================-->

    <section class="welcome-area">

        <div class="welcome-slides owl-carousel">

        @if(count($Sliders) > 0)
            @foreach($Sliders as $Slider)
                <!-- Single Slide -->
                    <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url({{ '/'.$Slider->image }});">
                        <div class="container h-100">
                            <div class="row h-100 align-items-center">
                                <!-- Welcome Text -->
                                <div class="col-12 col-lg-8 col-xl-6">
                                    <div class="welcome-text">
                                        <h2 data-animation="bounceInDown" data-delay="900ms">{{ $Slider->title }}</h2>
                                        <p data-animation="bounceInDown" data-delay="500ms">{{ $Slider->details }}</p>
                                        {{--<div class="hero-btn-group" data-animation="bounceInDown" data-delay="100ms">
                                            --}}{{--<a href="" class="slider_btn btn alime-btn mb-3 mb-sm-0 mr-4" style="color:white;transition:400ms;"></a>--}}{{--
                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{--<svg class="SliderBottom" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1366 140">
            <defs>
                <style>
                    .cls-1 {
                        fill: white;
                    }
                </style>
            </defs>
            <path class="cls-1" d="M0,644.79s175,38.39,347,34.82,386-47.32,477-64.29S1014,568,1135,568s231,47.32,231,47.32V768H0Z" transform="translate(0 -568)" />
        </svg>--}}

    </section>


    <!--================ Start Feature Area =================-->
    <section class="feature_area section_gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class="mb-3">Welcome to Rising sun preparatory school</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single_feature">
                        <div class="icon"><span class="flaticon-student"></span></div>
                        <div class="desc">
                            <h4 class="mt-3 mb-2">Scholarship Facility</h4>
                            <p>
                                One make creepeth, man bearing theira firmament won't great
                                heaven
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="single_feature">
                        <div class="icon"><span class="flaticon-book"></span></div>
                        <div class="desc">
                            <h4 class="mt-3 mb-2">Sell Online Course</h4>
                            <p>
                                One make creepeth, man bearing theira firmament won't great
                                heaven
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="single_feature">
                        <div class="icon"><span class="flaticon-earth"></span></div>
                        <div class="desc">
                            <h4 class="mt-3 mb-2">Global Certification</h4>
                            <p>
                                One make creepeth, man bearing theira firmament won't great
                                heaven
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================ End Feature Area =================-->

    @if(count($Posts) > 0)
    <div class="popular_courses">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class="mb-3">Post</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- single course -->
                <div class="col-lg-12">
                    <div class="row">
                        @foreach($Posts as $Post)
                            <div class="single_course col-12 col-sm-12 col-md-6 col-xl-4 col-lg-6 col-sm-12">
                                <div class="course_head">
                                    @if(count($Post->post_image) > 0)
                                        <div class="postImage">
                                            @foreach($Post->post_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                            <div class="ImageContainer-Post">
                                                <img src="/{{ $Img->thumbnail }}" alt="" class="img-fluid">
                                                <span class="moreImageOverflow ShowMoreImages" onclick="window.location.href = '{{ route('post-details', ['title'=>$Post->title_slug, 'id'=>$Post->id]) }}';">
                                                    <i class="fa fa-images m-r-10"></i> {{ count($Post->post_image) }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="postImage">
                                            <div class="ImageContainer-Post">
                                                <img src="{{ asset('/storage/image/web_layout/Post.jpg') }}" alt="" class="img-fluid">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="course_content">
                                    <h4 class="mb-3">
                                        <a href="{{ route('post-details', ['id'=>$Post->id, 'title'=>$Post->title_slug]) }}">{{ $Post->title }}</a>
                                    </h4>
                                    <div>
                                        {!! truncate(strip_tags($Post->body), 120) !!}
                                    </div>
                                    <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                                        <div class="authr_meta">
                                            <a href="{{ route('post-details', ['id'=>$Post->id, 'title'=>$Post->title_slug]) }}" class="btn btn-sm btn-primary">read more</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="text-center pt-lg-5 pt-3">
                        <a href="{{ route('post') }}" class="event-link" style="color:#fdc632;">
                            More <img src="{{ asset('asset_front/img/next.png') }}" alt="Next" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--================ End Popular Courses Area =================-->

    <!--================ Start Events Area =================-->
    @if(count($Events) > 0)
    <div class="events_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class="mb-3 text-white">Events</h2>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach($Events as $Event)
                    <div class="col-lg-6 col-md-6">
                        <div class="single_event position-relative">
                            <div class="event_thumb">
                                @if(count($Event->event_image) > 0)
                                    @foreach($Event->event_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                    <img src="/{{ $Img->thumbnail }}" alt="" style="height: 100%; width: 100%;object-fit: cover; object-position: center center;">
                                    @endforeach
                                @else
                                    <img src="{{ asset('storage/image/web_layout/bg.png') }}" alt="" style="height: 100%; width: 100%;object-fit: contain; object-position: center center;">
                                @endif
                            </div>
                            <div class="event_details">
                                <div class="d-flex mb-4">
                                    @if($Event->event_start_date != $Event->event_end_date)
                                        <div class="date"><span>{{ date('d', strtotime($Event->event_start_date)) }}</span>
                                            {{ date(' M, Y', strtotime($Event->event_start_date)) }}
                                        </div>
                                        <div class="date" style="border: none; padding-left: 15px;"><span>{{ date('d', strtotime($Event->event_end_date)) }}</span>
                                            {{ date(' M, Y', strtotime($Event->event_end_date)) }}
                                        </div>
                                    @else
                                        <div class="date"><span>{{ date('d', strtotime($Event->event_start_date)) }}</span>
                                            {{ date(' M, Y', strtotime($Event->event_start_date)) }}
                                        </div>
                                    @endif
                                </div>
                                <h5 class="text-white">Event title: {{ $Event->title }}</h5>
                                <a href="{{ route('event-details', ['title'=>$Event->title_slug, 'id'=>$Event->id]) }}" class="primary-btn rounded-0 mt-3">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-lg-12">
                    <div class="text-center pt-lg-5 pt-3">
                        <a href="{{ route('event') }}" class="event-link">
                            View All Event <img src="{{ asset('asset_front/img/next.png') }}" alt="Next" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!--================ End Events Area =================-->

    <!--================ Start Testimonial Area =================-->
    @if(count($GalleryImages) > 0)
        <div class="testimonial_area section_gap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="main_title">
                            <h2 class="mb-3">Gallery</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="testi_slider owl-carousel">
                        @foreach($GalleryImages as $Gallery)
                            @if(count($Gallery->gallery_image) > 0)
                                @foreach($Gallery->gallery_image->slice(0, 6) as $Img)
                                    <div class="testi_item">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <img src="/{{ $Img->thumbnail }}" alt="" style="max-width: ; max-height: ; " />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="text-center pt-lg-5 pt-3">
                        <a href="{{ route('gallery') }}" class="event-link" style="color:#fdc632;">
                            More <img src="{{ asset('asset_front/img/next.png') }}" alt="Next" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--================ End Testimonial Area =================-->

@endsection



@section('page_level_script')
<script src="{{ asset('asset_front/slider_asset/active.js') }}"></script>
@endsection
