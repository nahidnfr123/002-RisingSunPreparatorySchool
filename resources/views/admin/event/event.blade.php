@extends('layouts.admin.app_admin')

@php  $pageName = 'Events'  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_gallery/themes/csslider.default.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/mydatepicker/datepicker.min.css') }}">
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
                            <!-- SEARCH FORM -->
                            <form class="form-inline ml-3 form" method="post" action="{{ route('admin.events.search') }}">
                                @csrf
                                <div class="input-group input-group-sm m-t-10">
                                    <input class="form-control form-control-navbar" type="text" name="search" placeholder="Search" aria-label="Search" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="m-t-10 m-l-10" id="ButtonHolder">
                                <button type="button" class="button Blue_button button-sm m-l-6" data-toggle="modal" data-target=".AddPostModal">
                                    <i class="fa fa-plus p-r-6"> </i> Add New Event
                                </button>

                                {{--@if($subPageName == 'Events')
                                    <a href="{{ route('admin.events') }}" class="btn btn-sm bg-gradient-info m-l-6">
                                        <i class="fas fa-user p-r-6"> </i><i class="fas fa-box p-r-6"> </i> My events
                                    </a>
                                @else
                                    <a href="{{ route('admin.events.all') }}" class="btn btn-sm bg-gradient-info m-l-6">
                                        <i class="fas fa-box p-r-6"> </i> All events
                                    </a>
                                @endif--}}

                                <a href="{{ route('admin.events.trash') }}" class="button Red_button button-sm m-l-6">
                                    <i class="fa fa-trash p-r-6"> </i> Trash
                                </a>
                            </div>

                        </div>
                        <div class="text-muted"> {{ $eventCount }} </div>
                    </div>

                    <hr>

                    <div class="{{--container--}}">
                        <div class="col-12 col-lg-10 col-xl-10 m-auto">
                            @if($subPageName == 'Search')
                                <div class="col-12 py-1">
                                    <div class="text-center">
                                        <h4 class="text-muted text-monospace"> <i class="fa fa-search"></i> Search result for: {{ $Search_text }}</h4>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                @if(count($AllEvents) > 0)
                                    @foreach($AllEvents as $Event)
                                        {{--<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto">--}}
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 m-auto p-0" style="margin-bottom: 20px!important; padding: 0!important; ">
                                            <div class="card p-t-5 p-r-4 p-b-5 p-l-4" style="border: 2px solid rgba(20,20,20, .2); @if(auth()->guard('admin')->id() != $Event->user->id)  background: rgba(120,120,120,.2)!important; @endif">
                                                @if(count($Event->event_image) > 0)
                                                    <div class="card-header">
                                                        @foreach($Event->event_image->slice(0, 1) as $Img)
                                                            <div class="ImageContainer-Post">
                                                                <img src="/{{ $Img->thumbnail }}" alt="">
                                                                <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $Event->id }}"><i class="fa fa-images m-r-10"></i> {{ count($Event->event_image) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="card-body text-justify" style="height: auto;padding: 10px 10px 0 10px !important;">
                                                    <h4>{{ $Event->title }}</h4>
                                                    {{--<p class="text-truncate" style="font-size: 10px;!important;">{!! $Event->body !!}</p>--}}
                                                    {{--{!! \Illuminate\Support\Str::limit($Event->body, 100, '...') !!}--}}
                                                    {{--{!! substr($Event->body, 0, 100) . '...' !!}--}}
                                                    <div style="font-size: 14px;!important;">
                                                        @php
                                                            $string = strip_tags($Event->description);
                                                            if (strlen($string) > 200) {
                                                                // truncate string
                                                                $stringCut = substr($string, 0, 300);
                                                                $endPoint = strrpos($stringCut, ' ');
                                                                //if the string doesn't contain any space then it will cut without word basis.
                                                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                                $string .= '... ';
                                                            }
                                                            echo $string;
                                                        @endphp
                                                    </div>
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
                                                    <div class="btn-group-xs text-right m-l-10">
                                                        <a href="{{ route('admin.events.read-more', ['title' => $Event->title_slug, 'id' => encrypt($Event->id)]) }}" type="button" class="btn btn-xs bg-gradient-success">
                                                            <i class="fab fa-readme p-r-6"></i> Read more
                                                        </a>
                                                        @if(auth()->guard('admin')->id() == $Event->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.events.edit', ['id'=>encrypt($Event->id)]) }}" class="btn btn-xs bg-gradient-teal"><i class="fa fa-edit p-r-6"></i> Edit</a>
                                                            <a type="button" href="{{ route('admin.events.delete', ['id'=>encrypt($Event->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this event?')">
                                                                <i class="fa fa-trash p-r-6"></i> Delete
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @if($subPageName == 'Search')
                                        <div class="col-12 py-1">
                                            <div class="text-center">
                                                <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No results found... </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 py-1">
                                            <div class="text-center">
                                                <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'events' available. </h4>
                                            </div>
                                        </div>
                                    @endif
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

                <div class="modal fade bd-example-modal-lg AddPostModal" id="AddPostModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add new event</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.events.add') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                                <div class="modal-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="post_title" class="col-form-label-sm label"><b>Event Title: <span class="text-danger">*</span></b></label>
                                                <input type="text" name="event_title" class="form-control @error('event_title') invalid @enderror" id="post_title" required value="{{ old('event_title') }}" placeholder="Event title ....">
                                            </div>
                                            @error('event_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-lg-6 m-auto">
                                                    <label for="StartDate" class="FC_999"><b>Start date:  <span class="text-danger">*</span></b></label>
                                                    <div class="form-group input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i> </span></div>
                                                        <?php $date = \Carbon\Carbon::now()->addDays(1)->format('Y-m-d')  ;//date('Y-m') .'-'. (date('d') + 01);?>
                                                        {{--<input type="text" name="Start_Date" id="Start_Date" class="form-control bg-white @error('Start_Date') is-invalid @enderror" required readonly placeholder="Year-month-day" value="{{ old('Start_Date', $date)}}">--}}
                                                        <input id="StartDate" name="Start_Date" type="text" class="form-control clickable input-md bg-white @error('Start_Date') is-invalid @enderror" id="DtChkIn" placeholder="&#xf133;  dd-mm-YYYY" readonly value="{{ old('Start_Date', $date)}}">
                                                        <div class="invalid-tooltip"> Select Start date. </div>
                                                    </div>
                                                    @error('Start_Date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-lg-6 m-auto">
                                                    <label for="EndDate" class="FC_999"><b>End date:  <span class="text-danger">*</span></b></label>
                                                    <div class="row">
                                                        <div class="form-group input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i> </span></div>
                                                            {{--<input type="text" name="End_Date" id="End_Date" class="form-control bg-white @error('End_Date') is-invalid @enderror" required readonly placeholder="dd-mm-yyyy" value="{{ old('End_Date') }}">--}}
                                                            <input id="EndDate" name="End_Date" type="text" class="form-control clickable input-md bg-white @error('Start_Date') is-invalid @enderror" id="DtChkOut" placeholder="&#xf133;  dd-mm-YYYY" readonly value="{{ old('End_Date') }}">
                                                            <div class="invalid-tooltip"> Select End date. </div>
                                                        </div>
                                                    </div>
                                                    @error('End_Date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="form-group">
                                        <input id="StartDate" type="text" class="form-control clickable input-md" id="DtChkIn" placeholder="&#xf133;  Check-In" readonly>
                                    </div>
                                    <div class="form-group">
                                        <input id="EndDate" type="text" class="form-control clickable input-md" id="DtChkOut" placeholder="&#xf133;  Check-Out" readonly>
                                    </div>--}}

                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="post_image" class="label col-form-label-sm"><b>Event Image (max:5):</b></label>
                                                <div class="custom-file">
                                                    <input type="file"  name="event_image[]" id="post_image" value="Upload" multiple="multiple"
                                                           class="custom-file-input @error('event_image') invalid @enderror"
                                                           {{--onchange="document.getElementById('image_preview').src = window.URL.createObjectURL(this.files[0])"--}}>
                                                    <label class="custom-file-label" for="post_image">Choose file ....</label>
                                                </div>
                                            </div>
                                            @error('event_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="col-lg-12 text-center m-t-20" id="preview_file_div">
                                                <div class="ImageDangerWarning p-b-6" style="text-align: center; color: coral; font-size: 12px; font-weight: bold;"></div>
                                                <ul class="ImageSelectStyle">

                                                </ul>
                                            </div>
                                        </div>

                                        <input type="hidden" hidden readonly value="" name="dismissedImage" id="dismissedImage">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="post_body" class="label col-form-label-sm">Event Description: <span class="text-danger">*</span></label>
                                                <textarea class="textarea form-control @error('event_description') invalid @enderror" required placeholder="Write your post details." name="event_description" id="post_body"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                    {{ old('event_description') }}
                                                </textarea>
                                            </div>
                                            @error('event_body')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="button Blue_button pl-4 pr-4"> Submit </button>
                                    </div>
                                </div>
                            </form>
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
                <button class="DeleteSingleImage" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
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
    <script src="{{ asset('commonAsset/mydatepicker/bootstrap-datepicker.min.js') }}"></script>
    {{-- Date picker --}}
    <script>
        let nowTemp = new Date();
        let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate() + 1, 0, 0, 0, 0);
        let checkin = $('#StartDate').datepicker({
            beforeShowDay: function(date) {
                return date.valueOf() > now.valueOf();
            },
            autoclose: true,
            format:"yyyy-mm-dd",
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.datepicker("getDate").valueOf() || !checkout.datepicker("getDate").valueOf()) {
                let newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() /*+ 1 - 1*/);
                checkout.datepicker("update", newDate);
            }else{ // Else is used to make recorrection while selecting date....
                let newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() /*+ 1 - 1*/);
                checkout.datepicker("update", newDate);
            }
            $('#EndDate')[0].focus();
        });

        let checkout = $('#EndDate').datepicker({
            beforeShowDay: function(date) {
                if (!checkin.datepicker("getDate").valueOf()) {
                    return date.valueOf() >= new Date().valueOf();
                } else {
                    return date.valueOf() > checkin.datepicker("getDate").valueOf();
                }
            },
            autoclose: true,
            format:"yyyy-mm-dd",
        }).on('changeDate', function(ev) {});
    </script>


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
            });
        });

        $(function () {
            let input_file = document.getElementById('post_image');
            let deleted_file_ids = [];
            let dynm_id = 0;
            $("#post_image").change(function (event) {
                deleted_file_ids = []; // Clear array data on second file choose ...
                let len = input_file.files.length;
                $('#preview_file_div ul').html("");

                for(let j=0; j<len; j++) {
                    let src = "";
                    let name = event.target.files[j].name;
                    let mime_type = event.target.files[j].type.split("/");
                    if(mime_type[0] == "image") {
                        src = URL.createObjectURL(event.target.files[j]);
                    } else if(mime_type[0] == "video") {
                        src = "{{ asset('storage/image/web_layout/icon/video.png') }}";
                    } else {
                        src = "{{ asset('storage/image/web_layout/icon/file.png') }}";
                    }
                    $('#preview_file_div ul').append("" +
                        "<li id='" + dynm_id + "'>" +
                            "<div class='ic-sing-file position-relative'>" +
                                "<img id='" + dynm_id + "' src='"+src+"' title='"+name+"' alt=''>" +
                                "<i class='fa fa-times closeImg' id='" + dynm_id + "'></i>" +
                            "</div>" +
                        "</li>");
                    dynm_id++;
                }
                if(len > 5){
                    $('.ImageDangerWarning').html('Maximum image limit is 5. Please remove some images.');
                }else{
                    $('.ImageDangerWarning').html('');
                }
            });

            $(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');
                deleted_file_ids.push(id);
                $('li#'+id).remove();
                $('#dismissedImage').val(deleted_file_ids);
                if((input_file.files.length - deleted_file_ids.length) == 0){
                    document.getElementById('post_image').value="";
                    document.getElementById('dismissedImage').value="";
                }
                if((input_file.files.length - deleted_file_ids.length) <= 5){
                    $('.ImageDangerWarning').html('');
                }
            });

        });
    </script>

    <script>
        ClassicEditor
            .create(document.querySelector('#post_body'))
            .catch(error => {
                console.error(error);
            });

        $(function(){
            let nowTemp = new Date();
            let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            let checkin = $('#Start_Date').datepicker({
                onRender: function(date) {
                    return date.valueOf() < now.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function(ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    let newDate = new Date(ev.date);
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('#End_Date')[0].focus();
            }).data('datepicker');
            let checkout = $('#End_Date').datepicker({
                onRender: function(date) {
                    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');
        });


        /*let StartDate = '{{ \Carbon\Carbon::now()->addDays(1)->format('Ymd') }}';
        $('#Start_Date').kronos({
            initDate: StartDate,
            format: 'dd-mm-yyyy',
            button: {
                month : true,
                year : true,
                trigger : true,
                today : true
            },selectYear : {
                start : 0,
                end : 1
            },
            periodFrom: '#Start_Date',
            periodTo: '#End_Date'
        });
        $('#End_Date').kronos({
            format: 'dd-mm-yyyy',
            button: {
                month : true,
                year : true,
                trigger : true,
                today : true
            },selectYear : {
                start : 0,
                end : 1
            },
            periodFrom: '#Start_Date'
        });*/


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
                    url:'{{ route('admin.events.images.show') }}',
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

    @if(session('formError')) // edit for old data show problem ...
    <script>
        $(document).ready(function () {
            $('#AddPostModal').modal('toggle');
        });
    </script>
    @endif

@stop
