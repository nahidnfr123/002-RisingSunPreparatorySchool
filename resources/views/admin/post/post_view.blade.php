@extends('layouts.admin.app_admin')

@php  $pageName = 'Post'  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_gallery/themes/csslider.default.css') }}">
@stop

@section('admin_content')
    <!-- Main content -->
    <section class="">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">

                <div class="container">
                    <div class="row">
                        <div class="col-12 text-right row">
                            <div class="m-t-10">
                                <a href="{{ redirect()->getUrlGenerator()->previous() }}" class="btn btn-sm bg-gradient-maroon m-l-6">
                                    <i class="fa fa-chevron-left p-r-6"> </i> Go back
                                </a>
                            </div>
                            <div class="m-t-10" id="ButtonHolder">
                                <a href="{{ route('admin.post') }}" class="button CyanGreen_button button-sm m-l-6">
                                    <i class="fas fa-user p-r-6 hideOnPhone"> </i><i class="fas fa-box p-r-6 hideOnPhone"> </i> My posts
                                </a>
                                <a href="{{ route('admin.post.all') }}" class="button Green_button button-sm m-l-6">
                                    <i class="fas fa-box p-r-6 hideOnPhone"> </i> All posts
                                </a>
                                <a href="{{ route('admin.post.trash') }}" class="button Red_button button-sm m-l-6">
                                    <i class="fa fa-trash p-r-6 hideOnPhone"> </i> Trash
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="{{--container--}}">

                        <div class="col-12">
                            <div class="row">
                                @if($PostView != null)
                                    {{--<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto">--}}
                                    <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 m-auto" style="margin-bottom: 20px!important;">
                                        <div class="card p-t-5 p-r-6 p-b-5 p-l-6" style="border: 2px solid rgba(20,20,20, .2);">
                                            @if(count($PostView->post_image) > 0)
                                                <div class="card-header">
                                                    @foreach($PostView->post_image->slice(0, 1) as $Img)
                                                        <div class="ImageContainer-Post">
                                                            <img src="/{{ $Img->thumbnail }}" alt="">
                                                            <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $PostView->id }}"><i class="fa fa-images m-r-10"></i> {{ count($PostView->post_image) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif(count($PostView->post_image_Trashed) > 0)
                                                <div class="card-header">
                                                    @foreach($PostView->post_image_Trashed->slice(0, 1) as $Img)
                                                        <div class="ImageContainer-Post">
                                                            <img src="/{{ $Img->thumbnail }}" alt="">
                                                            <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $PostView->id }}"><i class="fa fa-images m-r-10"></i> {{ count($PostView->post_image_Trashed) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="card-body m-b-16 text-justify" style="height: auto;padding: 10px 10px 0 10px !important;">
                                                <h4>{{ $PostView->title }}</h4>
                                                <div style="font-size: 14px;!important;">{!! $PostView->body !!}</div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="float-left text-left text-muted">
                                                    <span>By
                                                        @if(auth()->guard('admin')->id() == $PostView->user->id)
                                                            Me,
                                                        @else
                                                            @if(auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                                <a href="{{ route('admin.users.details', ['id' => encrypt($PostView->user->id)]) }}">{{ $PostView->user->first_name }}</a>,
                                                            @else
                                                                {{ $PostView->user->first_name }},
                                                            @endif
                                                        @endif
                                                    </span>
                                                    <span>{{ date('d-M-Y', strtotime($PostView->publish_date)) }}</span>
                                                    <span>, {{ $PostView->visitor }} views</span>
                                                </div>
                                                <div class="btn-group-xs  text-right">
                                                    @if($PostView->deleted_at != null)
                                                        @if(auth()->guard('admin')->id() == $PostView->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.post.restore', ['id'=>encrypt($PostView->id)]) }}" class="btn btn-xs bg-gradient-cyan text-white">
                                                                <i class="fa fa-trash-restore p-r-6"></i> Restore
                                                            </a>
                                                            <a type="button" href="{{ route('admin.post.destroy', ['id'=>encrypt($PostView->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
                                                                <i class="fa fa-trash p-r-6"></i> Destroy
                                                            </a>
                                                        @endif
                                                    @else
                                                        @if(auth()->guard('admin')->id() == $PostView->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.post.edit', ['id'=>encrypt($PostView->id)]) }}" class="btn btn-xs bg-gradient-teal"><i class="fa fa-edit p-r-6"></i> Edit</a>
                                                            <a type="button" href="{{ route('admin.post.delete', ['id'=>encrypt($PostView->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
                                                                <i class="fa fa-trash p-r-6"></i> Delete
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12 py-1">
                                        <div class="text-center">
                                            <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> Post not found! </h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <div id="ShowPostImages">
        <div id="ShowPostImagesHead">
            <div class="text-right TopButtons">
                <a href="" class="text-white" id="DownloadImageButton" target="_blank" title="Download">
                    <i class="fa fa-download"></i>
                </a>
                @if($PostView->deleted_at == null)
                    <button class="DeleteSingleImage" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
                <button class="ClosePostImage myCloseBtn" title="Close"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div id="ShowPostImagesBody">
            <div style="margin: auto; text-align: center;">
                <img src="{{ asset('asset_gallery/img/loading (2).gif') }}" alt="" height="60" width="60" style="margin: calc(50vh - 30px) auto; text-align: center;">
            </div>
        </div>
        <div id="ShowPostImagesFooter">

        </div>
    </div>

@stop


@section('page_level_script')
    <script>

        function GetId(ID){
            /*let ImgId = 0;
            $("#slider1 input:radio").each(function () {
                let $this = $(this);
                if (this.checked) {
                    ImgId = $this.attr('value');
                }
            });*/

            $(".DeleteSingleImage").attr("href", ID);
            $("#DownloadImageButton").attr("href", "{{ url('admin/post/image/download') }}"+"/"+ ID);
        }


        $(document).ready(function () {
            $(".DeleteSingleImage").click(function (e) {
                e.preventDefault();
                let ImgId = 0;
                $("#slider1 input:radio").each(function () {
                    let $this = $(this);
                    if (this.checked) {
                        ImgId = $this.attr('value');
                    }
                });
                if(confirm('Are you sure you want to delete this image?')){
                    window.location= "{{ url('admin/post/image/delete') }}"+"/"+ ImgId;
                }
                //alert(ImgId);
            });
        });


        $(document).ready(function () {
            $('.ClosePostImage').click(function () {
                $('#ShowPostImages').hide();
                $("#ShowPostImagesBody").html('<div style="margin: auto; text-align: center;">\n' +
                    '                <img src="{{ asset("asset_gallery/img/loading (2).gif") }}" alt="" height="60" style="margin: calc(50vh - 30px) auto; text-align: center;">\n' +
                    '            </div>');
            });

            // Show All images using ajax....
            $(".ShowMoreImages").click(function () {
                let Post_id = $(this).attr('data-post-id');
                $('#ShowPostImages').show();
                //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
                $.ajax({
                    type:'POST',
                    url:'@if($PostView->deleted_at == null){{ route('admin.post.images.show') }}@else{{ route('admin.post.trashed.images.show') }}@endif',
                    data: { "post_id" :  Post_id, "_token": "{{ csrf_token() }}" },
                    success:function(data) {
                        $("#ShowPostImagesBody").html(data.Output);

                        $(".DeleteSingleImage").attr("href", data.ID);
                        $("#DownloadImageButton").attr("href", "{{ url('admin/post/image/download') }}"+"/"+ data.ID);
                    }
                });
            });
        });
    </script>
@stop
