@extends('layouts.front_end.app_front')
@php $pageName = 'Gallery'; $pageUrl = route('gallery'); @endphp

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300i,400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset_gallery/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/lightgallery.min.css') }}">


    <link rel="stylesheet" href="{{ asset('asset_gallery/fonts/flaticon/font/flaticon.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/swiper.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/style.css') }}">
@stop

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">



                <div class="{{--container--}}"  data-aos="fade">
                    <div class="container-fluid">
                        <div class="row" id="lightgallery">
                            @if(count($GalleryImages) > 0)
                                @foreach($GalleryImages as $Gallery)
                                    <div class="col-12 m-t-20">
                                        <h4><span>{{ $Gallery->gallery_title }} </span>
                                            <span style="font-size: 12px"> ({{ \Carbon\Carbon::parse($Gallery->created_at)->format('Y-m-d h:i a') }} )</span></h4>
                                        <hr>
                                    </div>
                                    @if(count($Gallery->gallery_image) > 0)
                                        @foreach($Gallery->gallery_image as $Img)
                                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3 item text-white" data-aos="fade" data-src="/{{ $Img->image }}"
                                                 data-sub-html="<h4>{{ $Gallery->gallery_title }}</h4>">
                                                <a href="#"><img src="/{{ $Img->thumbnail }}" alt="IMage" class="img-fluid"></a>
                                            </div>
                                        @endforeach
                                        {{--<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 text-center" data-aos="fade" style="display: flex; align-content: center; vertical-align: center; ">
                                            <a><i class="fa fa-plus" style="font-size: 30px;"></i></a>
                                        </div>--}}
                                    @else
                                        <div class="col-12 py-1">
                                            <div class="text-center">
                                                <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Image' in this gallery. </h5>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="col-12 py-1 m-t-20 m-b-20">
                                    <div class="text-center">
                                        <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Gallery' available. </h5>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @if(count($GalleryImages) > 0)
                    <div class="col-12 m-auto position-relative" style="height: 60px;">
                        <div style="position: absolute; bottom: -20px; left: 50%; transform: translate(-50%, -50%);">
                            {{ $GalleryImages->links() }}
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </section>

@endsection


@section('page_level_script')
    <script src="{{ asset('asset_gallery/js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/swiper.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/aos.js') }}"></script>

    <script src="{{ asset('asset_gallery/js/picturefill.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.mousewheel.min.js') }}"></script>

    <script src="{{ asset('asset_gallery/js/main.js') }}"></script>

    <script src="{{ asset('asset_gallery/bootstrap_fileinput.js') }}"></script>
    <script src="{{ asset('asset_gallery/bootstrap_fileinput_themes.js') }}"></script>

    <script>
        $(document).ready(function(){
            $('#lightgallery').lightGallery({
                pager: true,
                selector: '.item'
            });
        });
    </script>

@stop
