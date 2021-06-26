@extends('layouts.front_end.app_front')
@php $pageName = 'Post'; $pageUrl = route('post'); @endphp
@php $subPageName = 'Search'; $subPageUrl = ''; @endphp

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <!--================Blog Area =================-->
    <section class="blog_area m-t-40 <!--section_gap-->">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog_left_sidebar">
                        <h2 class="col-12 text-center">Search result for: "{{ $Search_text }}"</h2>
                        <hr>
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

                        @if(count($Posts) > 0)
                            @foreach($Posts as $Post)
                                <article class="row blog_item">
                                    <div class="col-md-3">
                                        <div class="blog_info text-right">
                                            <ul class="blog_meta list">
                                                <li><a>{{ $Post->user->first_name . ' ' . $Post->user->last_name }}<i class="ti-user"></i></a></li>
                                                <li><a>{{ date('d M, Y', strtotime($Post->created_at)) }} {{--{{ date('g:ia', strtotime($Post->created_at)) }}--}}  <i class="ti-calendar"></i></a></li>
                                                <li><a>{{ $Post->visitor }} Views<i class="ti-eye"></i></a></li>
                                                {{--<li><a href="#">06 Comments<i class="ti-comment"></i></a></li>--}}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="blog_post">
                                            {{--<img src="asset_front/img/blog/main-blog/m-blog-2.jpg" alt="">--}}
                                            @if(count($Post->post_image) > 0)
                                                <div class="postImage">
                                                    @foreach($Post->post_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                                    <div class="ImageContainer-Post">
                                                        <img src="/{{ $Img->thumbnail }}" alt="">
                                                        <span class="moreImageOverflow ShowMoreImages" onclick="window.location.href = '{{ route('post-details', ['title'=>$Post->title_slug, 'id'=>$Post->id]) }}';">
                                                                <i class="fa fa-images m-r-10"></i> {{ count($Post->post_image) }}
                                                            </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="blog_details">
                                                <a href="{{ route('post-details', ['id'=>$Post->id, 'title'=>$Post->title_slug]) }}">
                                                    <h2>{{ $Post->title }}</h2>
                                                </a>
                                                <div style="text-align: justify;">{!! truncate(strip_tags($Post->body), 300) !!}</div>
                                                <a href="{{ route('post-details', ['title'=>$Post->title_slug, 'id'=>$Post->id]) }}" class="blog_btn m-t-10">View More</a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        @else
                            <div class="col-12 py-1 m-t-30 m-b-20">
                                <div class="text-center">
                                    <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No "Post" available. </h5>
                                </div>
                            </div>
                        @endif

                        <nav class="blog-pagination justify-content-center d-flex">
                            {{ $Posts->links() }}
                        </nav>
                    </div>
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
