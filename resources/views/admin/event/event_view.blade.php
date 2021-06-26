@extends('layouts.admin.app_admin')

@php  $pageName = 'Events'  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_gallery/themes/csslider.default.css') }}">
@stop

@section('admin_content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">

                <div class="{{--container--}}">
                    <div class="row">
                        <div class="col-12 text-right row">
                            <div class="m-t-10 m-l-10" id="ButtonHolder">
                                <a href="{{ URL::previous() }}" class="btn btn-sm bg-gradient-maroon m-r-6">
                                    <i class="fa fa-chevron-left p-r-6"> </i> Go back
                                </a>

                                <a href="{{ route('admin.events') }}" class="button Green_button button-sm m-l-6">
                                    <i class="fa fa-calendar-alt p-r-6"> </i> All Events
                                </a>

                                <a href="{{ route('admin.events.trash') }}" class="button Red_button button-sm m-l-6">
                                    <i class="fa fa-trash p-r-6"> </i> Trash
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="{{--container--}}">
                        <div class="col-12 col-lg-12 col-xl-12 m-auto">
                            <div class="row">
                                @if($EventView != null)
                                    {{--<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto">--}}
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 m-auto" style="margin-bottom: 20px!important;">
                                        <div class="card p-t-5 p-r-6 p-b-5 p-l-6" style="border: 2px solid rgba(20,20,20, .2);">
                                            @if(count($EventView->event_image) > 0)
                                                <div class="card-header">
                                                    @foreach($EventView->event_image->slice(0, 1) as $Img)
                                                        <div class="ImageContainer-Post">
                                                            <img src="/{{ $Img->thumbnail }}" alt="">
                                                            <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $EventView->id }}"><i class="fa fa-images m-r-10"></i> {{ count($EventView->event_image) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif(count($EventView->event_image_Trashed) > 0)
                                                <div class="card-header">
                                                    @foreach($EventView->event_image_Trashed->slice(0, 1) as $Img)
                                                        <div class="ImageContainer-Post">
                                                            <img src="/{{ $Img->thumbnail }}" alt="">
                                                            <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $EventView->id }}"><i class="fa fa-images m-r-10"></i> {{ count($EventView->event_image_Trashed) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="card-body m-b-16 text-justify" style="height: auto;padding: 10px 10px 0 10px !important;">
                                                <h4>{{ $EventView->title }}</h4>
                                                <div style="font-size: 14px;!important;">{!! $EventView->description !!}</div>
                                                {{--{!! \Illuminate\Support\Str::limit($Post->body, 100, '...') !!}--}}
                                                {{--{!! substr($Post->body, 0, 100) . '...' !!}--}}
                                            </div>
                                            <div class="card-footer">
                                                <div class="float-left text-left text-muted">
                                                    <span>By
                                                        @if(auth()->guard('admin')->id() == $EventView->user->id)
                                                            Me,
                                                        @else
                                                            @if(auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                                <a href="{{ route('admin.users.details', ['id' => encrypt($EventView->user->id)]) }}">{{ $EventView->user->first_name }}</a>,
                                                            @else
                                                                {{ $EventView->user->first_name }},
                                                            @endif
                                                        @endif
                                                    </span>
                                                    <span>{{ date('d.M.Y', strtotime($EventView->event_start_date)) .' - '. date('d.M.Y', strtotime($EventView->event_end_date)) }}</span>
                                                </div>
                                                <div class="btn-group-xs  text-right">
                                                    @if($EventView->deleted_at != null)
                                                        @if(auth()->guard('admin')->id() == $EventView->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.events.restore', ['id'=>encrypt($EventView->id)]) }}" class="btn btn-xs bg-gradient-cyan text-white">
                                                                <i class="fa fa-trash-restore p-r-6"></i> Restore
                                                            </a>
                                                            <a type="button" href="{{ route('admin.events.destroy', ['id'=>encrypt($EventView->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
                                                                <i class="fa fa-trash p-r-6"></i> Destroy
                                                            </a>
                                                        @endif
                                                    @else
                                                        @if(auth()->guard('admin')->id() == $EventView->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.events.edit', ['id'=>encrypt($EventView->id)]) }}" class="btn btn-xs bg-gradient-teal"><i class="fa fa-edit p-r-6"></i> Edit</a>
                                                            <a type="button" href="{{ route('admin.events.delete', ['id'=>encrypt($EventView->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
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
                                            <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> Event not found! </h4>
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
                @if($EventView->deleted_at == null)
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
            $("#DownloadImageButton").attr("href", "{{ url('admin/events/image/download') }}"+"/"+ ID);
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
                    window.location= "{{ url('admin/events/image/delete') }}"+"/"+ ImgId;
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
                let Event_id = $(this).attr('data-post-id');
                $('#ShowPostImages').show();
                //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
                $.ajax({
                    type:'POST',
                    url:'@if($EventView->deleted_at == null){{ route('admin.events.images.show') }}@else{{ route('admin.events.trashed.images.show') }}@endif',
                    data: { "event_id" :  Event_id, "_token": "{{ csrf_token() }}" },
                    success:function(data) {
                        $("#ShowPostImagesBody").html(data.Output);

                        $(".DeleteSingleImage").attr("href", data.ID);
                        $("#DownloadImageButton").attr("href", "{{ url('admin/events/image/download') }}"+"/"+ data.ID);
                    }
                });
            });
        });
    </script>
@stop
