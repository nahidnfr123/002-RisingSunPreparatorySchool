@extends('layouts.admin.app_admin')

@php  $pageName = 'Events'  @endphp
@php  $subPageName = 'Trash'  @endphp

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
                            <a href="{{ route('admin.events') }}" class="btn btn-sm bg-gradient-maroon m-l-6">
                                <i class="fa fa-chevron-left p-r-6"> </i> Go back
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="{{--container--}}">
                        <div class="col-12 col-lg-10 col-xl-10 m-auto">
                            <div class="row">
                                @if(count($AllEvents) > 0)
                                    @foreach($AllEvents as $Event)
                                        {{--<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto">--}}
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 m-auto p-0" style="margin-bottom: 20px!important;">
                                            <div class="card p-t-5 p-r-4 p-b-5 p-l-4" style="border: 2px solid rgba(20,20,20, .2);">
                                                @if(count($Event->event_image_Trashed) > 0)
                                                    <div class="card-header">
                                                        @foreach($Event->event_image_Trashed->slice(0, 1) as $Img)
                                                            <div class="ImageContainer-Post">
                                                                <img src="/{{ $Img->thumbnail }}" alt="">
                                                                <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $Event->id }}"><i class="fa fa-images m-r-10"></i> {{ count($Event->event_image_Trashed) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                    <div class="card-body text-justify" style="height: auto;padding: 10px 10px 0 10px !important;">
                                                        <h4>{{ $Event->title }}</h4>
                                                        {{--<p class="text-truncate" style="font-size: 10px;!important;">{!! $Event->body !!}</p>--}}
                                                        {{--{!! \Illuminate\Support\Str::limit($Event->body, 100, '...') !!}--}}
                                                        {{--{!! substr($Event->body, 0, 100) . '...' !!}--}}
                                                        <p style="font-size: 14px;!important;">
                                                            <!-- Truncate blog description -->
                                                            @php
                                                                $string = strip_tags($Event->description);
                                                                if (strlen($string) > 20) {
                                                                    // truncate string
                                                                    $stringCut = substr($string, 0, 380);
                                                                    $endPoint = strrpos($stringCut, ' ');
                                                                    //if the string doesn't contain any space then it will cut without word basis.
                                                                    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                                    $string .= '... ';
                                                                }
                                                                echo $string;
                                                            @endphp
                                                        </p>
                                                    </div>
                                                <div class="card-footer">
                                                    <div class="float-left text-left text-muted">
                                                        <span>By
                                                            @if(auth()->guard('admin')->id() == $Event->user->id)
                                                                Me,
                                                            @else
                                                                @if(auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                                    <a href="{{ route('admin.users.details', ['id' => encrypt($Event->user->id)]) }}">{{ $Event->user->first_name }}</a>,
                                                                @else
                                                                    {{ $Event->user->first_name }},
                                                                @endif
                                                            @endif
                                                        </span>
                                                        <span>{{ date('d.M.Y', strtotime($Event->event_start_date)) .' - '. date('d.M.Y', strtotime($Event->event_end_date)) }}</span>
                                                    </div>
                                                    <div class="btn-group-xs  text-right">
                                                        <a href="{{ route('admin.events.read-more', ['id' => encrypt($Event->id), 'title' => $Event->title_slug]) }}" type="button" class="btn btn-xs bg-gradient-success">
                                                            <i class="fab fa-readme p-r-6"></i> Read more
                                                        </a>
                                                        @if(auth()->guard('admin')->id() == $Event->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.events.restore', ['id'=>encrypt($Event->id)]) }}" class="btn btn-xs bg-gradient-cyan text-white">
                                                                <i class="fa fa-trash-restore p-r-6"></i> Restore
                                                            </a>
                                                            <a type="button" href="{{ route('admin.events.destroy', ['id'=>encrypt($Event->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
                                                                <i class="fa fa-trash p-r-6"></i> Destroy
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 py-1">
                                        <div class="text-center">
                                            <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Events' available in trash. </h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(count($AllEvents) > 0)
                                <div class="col-12 m-auto position-relative" style="height: 50px;">
                                    <div style="position: absolute; bottom: -20px; left: 50%; transform: translate(-50%, -50%);">
                                        {{ $AllEvents->links() }}
                                    </div>
                                </div>
                            @endif
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
            $("#DownloadImageButton").attr("href", "{{ url('admin/events/image/download') }}"+"/"+ ID);
        }
    </script>


    <script>
        $(document).ready(function () {
            /*$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });*/

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
                    url:'{{ route('admin.events.trashed.images.show') }}',
                    data: { "event_id" :  Event_id, "_token": "{{ csrf_token() }}" },
                    success:function(data) {
                        $("#ShowPostImagesBody").html(data.Output);
                        $("#DownloadImageButton").attr("href", "{{ url('admin/events/image/download') }}"+"/"+ data.ID);
                    }
                });
            });
        });
    </script>


@stop
