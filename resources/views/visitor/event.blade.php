@extends('layouts.front_end.app_front')
@php $pageName = 'Event'; $pageUrl = route('event'); @endphp
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
    @include('layouts.front_end.partials.banner')

    <!--================ Start Events Area =================-->

    @if(count($UpcomingEvents) > 0)
    <div class="blog_area m-t-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class=" text-dark">Upcoming Events</h2>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($UpcomingEvents as $Event)
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
            </div>

            <nav class="blog-pagination justify-content-center d-flex m-b-20">
                {{ $UpcomingEvents->links() }}
            </nav>
        </div>
    </div>
    @endif



    @if(count($OngoingEvents) > 0)
    <div class="blog_area m-t-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class=" text-dark">Ongoing Events</h2>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($OngoingEvents as $Event)
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
            </div>

            <nav class="blog-pagination justify-content-center d-flex m-b-20">
                {{ $OngoingEvents->links() }}
            </nav>
        </div>
    </div>
    @endif




    @if(count($PreviousEvents) > 0)
    <div class="blog_area m-t-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class=" text-dark">Previous Events</h2>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($PreviousEvents as $Event)
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
            </div>

            <nav class="blog-pagination justify-content-center d-flex m-b-20">
                {{ $PreviousEvents->links() }}
            </nav>
        </div>
    </div>
    @endif
    <!--================ End Events Area =================-->

    @if(count($UpcomingEvents) <= 0 && count($OngoingEvents) <= 0 && count($PreviousEvents) <= 0)
        <div class="col-12 py-1 m-t-40 m-b-20">
            <div class="text-center">
                <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No "Events" available. </h5>
            </div>
        </div>
    @endif

@endsection


@section('page_level_script')
    <script>

    </script>
@endsection
