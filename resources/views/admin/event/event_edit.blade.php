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

                <div class="container">
                    <div class="row">
                        <div class="col-12 text-right row">
                            <a href="{{ URL::previous() }}" class="btn btn-sm bg-gradient-maroon m-l-6">
                                <i class="fa fa-chevron-left p-r-6"> </i> Go back
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="container">

                        <div class="col-12">
                            <div class="row">
                                @if($Event != null)
                                    <form action="{{ route('admin.events.update', ['id' => encrypt($Event->id)]) }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form" class="p-0 col-12 col-lg-11 col-xl-10 m-auto">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="event_title" class="col-form-label-sm label">Post Title: <span class="text-danger">*</span></label>
                                                    <input type="text" name="event_title" class="form-control @error('event_title') invalid @enderror" id="event_title" required value="{{ old('event_title', $Event->title) }}" placeholder="Post title ....">
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
                                                            {{--<input type="text" name="Start_Date" id="Start_Date" class="form-control bg-white @error('Start_Date') is-invalid @enderror" required placeholder="Year-month-day" value="{{ old('Start_Date', \Carbon\Carbon::parse($Event->event_start_date)->format('d-m-Y'))}}">--}}
                                                            <input id="StartDate" name="Start_Date" type="text" class="form-control clickable input-md bg-white @error('Start_Date') is-invalid @enderror" id="DtChkIn" placeholder="&#xf133;  dd-mm-YYYY" readonly value="{{ old('Start_Date', \Carbon\Carbon::parse($Event->event_start_date)->format('Y-m-d'))}}">
                                                            <div class="invalid-tooltip"> Select start date. </div>
                                                        </div>
                                                        @error('Start_Date')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12 col-lg-6 m-auto">
                                                        <label for="EndDate" class="FC_999"><b>End date:  <span class="text-danger">*</span></b></label>
                                                        <div class="form-group input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i> </span></div>
                                                            {{--<input type="text" name="End_Date" id="End_Date" class="form-control bg-white @error('End_Date') is-invalid @enderror" required placeholder="Year-month-day" value="{{ old('End_Date', \Carbon\Carbon::parse($Event->event_end_date)->format('d-m-Y')) }}">--}}
                                                            <input id="EndDate" name="End_Date" type="text" class="form-control clickable input-md bg-white @error('Start_Date') is-invalid @enderror" id="DtChkOut" placeholder="&#xf133;  dd-mm-YYYY" readonly value="{{ old('End_Date', \Carbon\Carbon::parse($Event->event_end_date)->format('Y-m-d')) }}">
                                                            <div class="invalid-tooltip"> Select End date. </div>
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

                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="event_image" class="label col-form-label-sm">Post Image (max:5):</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="event_image[]" id="post_image" value="Upload" multiple="multiple"
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
                                                        @if(count($Event->event_image) > 0)
                                                            @foreach($Event->event_image as $Img)
                                                                <li id="{{ $Img->id }}">
                                                                    <div class='ic-sing-file position-relative'>
                                                                        <img id='{{ $Img->id }}' src='/{{ $Img->thumbnail }}' title='{{ $Event->title }}' alt=''>
                                                                        <i class='fa fa-times closeImg' id='{{ $Img->id }}' data-id="previousImage"></i>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                            <input type="hidden" hidden readonly value="" name="dismissedImage" id="dismissedImage">
                                            <input type="hidden" hidden readonly value="" name="deletePreviousImage" id="deletePreviousImage">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="post_body" class="label col-form-label-sm">Post Description: <span class="text-danger">*</span></label>
                                                    <textarea class="textarea form-control @error('event_description') invalid @enderror" required placeholder="Write your post details." name="event_description" id="post_body"
                                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                        {{ old('event_description', $Event->description) }}
                                                    </textarea>
                                                </div>
                                                @error('event_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="button Blue_button m-r-6"><i class="fa fa-edit m-r-6"></i> Update Event </button>

                                                <a href="{{ route('admin.events.read-more', ['title' => $Event->title_slug, 'id' => encrypt($Event->id)]) }}" class="button Red_button button-sm">
                                                    <i class="fa fa-times m-r-4"></i>  Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </form>

                                @else
                                    <div class="col-12 py-1">
                                        <div class="text-center">
                                            <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> Event not Fount. </h4>
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


@stop


@section('page_level_script')
    <script src="{{ asset('commonAsset/mydatepicker/bootstrap-datepicker.min.js') }}"></script>
    {{-- Date picker --}}
    <script>
        let nowTemp = new Date();
        let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        let checkin = $('#StartDate').datepicker({
            beforeShowDay: function(date) {
                return date.valueOf() >= now.valueOf();
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
        document.getElementById('post_image').value = null;
        document.getElementById('dismissedImage').value = null;

        $(function () {
            let input_file = document.getElementById('post_image');
            let deleted_file_ids = [];
            let deleted_db_file_ids = [];
            let dynm_id = 0;
            let DB_len = 0;

            @if(count($Event->event_image) > 0)
                DB_len = {{ count($Event->event_image) }};
            @endif

            $("#post_image").change(function (event) {
                deleted_file_ids = []; // Clear array data on second file choose ...
                let len = input_file.files.length;
                $('#preview_file_div ul .selectedImagesToUpload').remove();

                console.log();

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
                        "<li id='" + dynm_id + "' class='selectedImagesToUpload'>" +
                        "<div class='ic-sing-file position-relative'>" +
                        "<img id='" + dynm_id + "' src='"+src+"' title='"+name+"' alt=''>" +
                        "<i class='fa fa-times closeImg' id='" + dynm_id + "'></i>" +
                        "</div>" +
                        "</li>");
                    dynm_id++;
                }

                if((DB_len + len) > 5){
                    $('.ImageDangerWarning').html('Maximum image limit is 5. Please remove some images.');
                }else{
                    $('.ImageDangerWarning').html('');
                }
            });

            $(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');

                if($(this).attr('data-id') == 'previousImage'){
                    deleted_db_file_ids.push(id);
                    $('li#'+id).remove();
                    $('#deletePreviousImage').val(deleted_db_file_ids);
                    console.log($('#deletePreviousImage').val());
                }
                else{
                    deleted_file_ids.push(id);
                    $('li#'+id).remove();
                    $('#dismissedImage').val(deleted_file_ids);
                    console.log($('#dismissedImage').val());
                }
                if($("#preview_file_div ul").children("li").length <= 5){
                    $('.ImageDangerWarning').html('');
                }


                //console.log(input_file.files.length - deleted_file_ids.length);
                //console.log(deleted_file_ids);



                if((input_file.files.length - deleted_file_ids.length) == 0){
                    document.getElementById('post_image').value="";
                    document.getElementById('dismissedImage').value="";
                }

                //console.log((DB_len + input_file.files.length) - deleted_file_ids.length);
                //console.log($('#dismissedImage').val());
                //alert($('#dismissedImage').val());
                /*console.log(id);
                input_file.files[id].remove;
                console.log(input_file.target.files[id]);*/
            });

            /*$("form#form-upload-file").submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                formData.append("deleted_file_ids", deleted_file_ids);
                /!*$.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(data) {
                        $('#preview_file_div ul').html("<li class='text-success'>Files uploaded successfully!</li>");
                        $('#post_image').val("");
                    },
                    error: function(e) {
                        $('#preview_file_div ul').html("<li class='text-danger'>Something wrong! Please try again.</li>");
                    }
                });*!/
            });*/
        });


        ClassicEditor
            .create( document.querySelector( '#post_body' ) )
            .catch( error => {
                console.error( error );
            });
    </script>
@stop
