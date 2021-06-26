@extends('layouts.front_end.app_front')
@php $pageName = 'Post'; $pageUrl = route('post'); @endphp
@php $subPageName = 'Details'; $subPageUrl = ''; @endphp

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <!--================Blog Area =================-->
    <section class="blog_area single-post-area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    @if($Post != null)
                    <div class="single-post row">
                        @if(count($Post->post_image) > 0)
                            <div class="postImage row">
                                @foreach($Post->post_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                <div class="col-lg-12">
                                    <div class="feature-img" style="min-width: 100%; height: auto;">
                                        <img src="/{{ $Img->image }}" class="img-fluid" alt="" style="object-fit: cover; object-position: center center; width: 100%; height: auto; max-height: 400px;">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                            <hr>
                        <div class="col-lg-3  col-md-3">
                            <div class="blog_info text-right">
                                <ul class="blog_meta list">
                                    <li><a>{{ $Post->user->first_name . ' ' . $Post->user->last_name }}<i class="ti-user"></i></a></li>
                                    <li><a>{{ date('d M, Y', strtotime($Post->publish_date)) }} {{--{{ date('g:ia', strtotime($Post->created_at)) }}--}}  <i class="ti-calendar"></i></a></li>
                                    <li><a>{{ $Post->visitor }} Views<i class="ti-eye"></i></a></li>
                                    {{--<li><a href="#">06 Comments<i class="ti-comment"></i></a></li>--}}
                                </ul>
                                {{--<ul class="social-links">
                                    <li><a href="#"><i class="ti-facebook"></i></a></li>
                                    <li><a href="#"><i class="ti-twitter"></i></a></li>
                                    <li><a href="#"><i class="ti-github"></i></a></li>
                                    <li><a href="#"><i class="ti-linkedin"></i></a></li>
                                </ul>--}}
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 blog_details m-t-10">
                            <h2>{{ $Post->title }}</h2>
                            <div style="text-align: justify;">{!! $Post->body !!}</div>
                        </div>
                        <div class="col-lg-12 m-t-10">
                            @if(count($Post->post_image) > 1)
                                <div class="row">
                                    @foreach($Post->post_image->slice(1, 5) as $Img) {{-- Loop all the images. --}}
                                    <div class="col-6">
                                        <img src="/{{ $Img->image }}" alt="" class="img-fluid m-t-10" style="object-fit: cover; object-position: center center; width: 100%; height: 240px;">
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                        <div class="col-12 py-1 m-t-30 m-b-20">
                            <div class="text-center">
                                <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No "Post" available. </h5>
                            </div>
                        </div>
                    @endif
                    {{--<div class="navigation-area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
                                <div class="thumb">
                                    <a href="#"><img class="img-fluid" src="asset_front/img/blog/prev.jpg" alt=""></a>
                                </div>
                                <div class="arrow">
                                    <a href="#"><i class="text-white ti-arrow-left"></i></a>
                                </div>
                                <div class="detials">
                                    <p>Prev Post</p>
                                    <a href="#">
                                        <h4>Space The Final Frontier</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 nav-right flex-row d-flex justify-content-end align-items-center">
                                <div class="detials">
                                    <p>Next Post</p>
                                    <a href="#">
                                        <h4>Telescopes 101</h4>
                                    </a>
                                </div>
                                <div class="arrow">
                                    <a href="#"><i class="text-white ti-arrow-right"></i></a>
                                </div>
                                <div class="thumb">
                                    <a href="#"><img class="img-fluid" src="asset_front/img/blog/next.jpg" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                </div>

                <div class="col-lg-4">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget search_widget">
                            <form action="{{ route('post-search') }}" method="get">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search Posts" name="search" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="ti-search"></i></button>
                                    </span>
                                </div><!-- /input-group -->
                            </form>
                            <div class="br"></div>
                        </aside>
                        @if(count($PopularPosts) > 0)
                            <aside class="single_sidebar_widget popular_post_widget">
                                <h3 class="widget_title">Popular Posts</h3>
                                @foreach($PopularPosts as $Post)
                                    <div class="media post_item">
                                        @if(count($Post->post_image) > 0)
                                            @foreach($Post->post_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                            <img src="/{{ $Img->thumbnail }}" alt="Post image" height="60" width="70" style="object-fit: cover; object-position: center center;">
                                            @endforeach
                                        @endif
                                        <div class="media-body">
                                            <a href="{{ route('post-details', ['id'=>$Post->id, 'title'=>$Post->title_slug]) }}">
                                                <h3>{{ $Post->title }}</h3>
                                            </a>
                                            <p>{{ $Post->visitor }} views, {{ \Carbon\Carbon::now()->diffForHumans($Post->created_at) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="br"></div>
                            </aside>
                        @endif
                    </div>
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
