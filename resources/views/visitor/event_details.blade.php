@extends('layouts.front_end.app_front')
@php $pageName = 'Event'; $pageUrl = route('event'); @endphp
@php $subPageName = 'Details'; $subPageUrl = ''; @endphp

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <!--================Blog Area =================-->
    <section class="blog_area single-post-area m-t-40 m-b-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    @if($Event != null)
                        <div class="single-post row">
                            @if(count($Event->event_image) > 0)
                                <div class="postImage row">
                                    @foreach($Event->event_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                    <div class="col-lg-12">
                                        <div class="feature-img" style="min-width: 100%; height: auto;">
                                            <img src="/{{ $Img->image }}" class="img-fluid" alt="" style="object-fit: cover; object-position: center center; width: 100%; height: auto; max-height: 400px;">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="postImage row">
                                    <div class="col-lg-12">
                                        <div class="feature-img" style="min-width: 100%; height: auto;">
                                            <img src="{{ asset('storage/image/web_layout/bg.png') }}" alt="" style="object-fit: cover; object-position: center center; width: 100%; height: auto; max-height: 400px;">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            <div class="col-lg-3  col-md-3">
                                <div class="blog_info text-right">
                                    <ul class="blog_meta list">
                                        <li><a>{{ $Event->user->first_name . ' ' . $Event->user->last_name }}<i class="ti-user"></i></a></li>
                                        @if($Event->event_start_date != $Event->event_end_date)
                                            <li><a>From: {{ date('d M, Y', strtotime($Event->event_start_date)) }} {{--{{ date('g:ia', strtotime($Post->created_at)) }}--}}  <i class="ti-calendar"></i></a></li>
                                            <li><a>To: {{ date('d M, Y', strtotime($Event->event_end_date)) }} {{--{{ date('g:ia', strtotime($Post->created_at)) }}--}}  <i class="ti-calendar"></i></a></li>
                                        @else
                                            <li><a>{{ date('d M, Y', strtotime($Event->event_start_date)) }} {{--{{ date('g:ia', strtotime($Post->created_at)) }}--}}  <i class="ti-calendar"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 blog_details m-t-10">
                                <h2>{{ $Event->title }}</h2>
                                <div class="p-b-20" style="text-align: justify;">{!! $Event->description !!}</div>
                            </div>
                            <div class="col-lg-12 m-t-10">
                                @if(count($Event->event_image) > 1)
                                    <div class="row">
                                        @foreach($Event->event_image->slice(1, 5) as $Img) {{-- Loop all the images. --}}
                                        <div class="col-6">
                                            <img src="/{{ $Img->image }}" alt="" class="m-t-10 img-fluid" style="object-fit: cover; object-position: center center; width: 100%; height: 240px;">
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="col-12 py-1 m-t-40 m-b-20">
                            <div class="text-center">
                                <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> "Events" details not available. </h5>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
    <!--================Blog Area =================-->


@endsection


@section('page_level_script')
    <script>

    </script>
@endsection
