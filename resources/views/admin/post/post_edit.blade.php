@extends('layouts.admin.app_admin')

@php  $pageName = 'Post'  @endphp

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
                            <a href="@if($Post != null){{ route('admin.post.read-more', ['id' => encrypt($Post->id), 'title' => $Post->title_slug]) }}@else {{ redirect()->getUrlGenerator()->previous() }} @endif" class="btn btn-sm bg-gradient-maroon m-l-6">
                                <i class="fa fa-chevron-left p-r-6"> </i> Go back
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="container">

                        <div class="col-12">
                            <div class="row">
                                @if($Post != null)

                                    <form action="{{ route('admin.post.update', ['id' => encrypt($Post->id)]) }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form" class="p-0 col-12 col-lg-11 col-xl-10 m-auto">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="post_title" class="col-form-label-sm label">Post Title: <span class="text-danger">*</span></label>
                                                    <input type="text" name="post_title" class="form-control @error('post_title') invalid @enderror" id="post_title" required value="{{ old('post_title', $Post->title) }}" placeholder="Post title ....">
                                                </div>
                                                @error('post_title')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="publish_date" class="label col-form-label-sm">Publish Date (d-m-y): <span class="text-danger">*</span></label>
                                                    <br>
                                                    <input type="text" class="form-control @error('publish_date') invalid @enderror" name="publish_date" id="publish_date"
                                                           value="{{ old('publish_date', \Carbon\Carbon::parse($Post->publish_date)->format('d-m-Y')) }}" readonly required>
                                                </div>
                                                @error('publish_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                                <div class="calenderDatePiker" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10000000;"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label for="post_image" class="label col-form-label-sm">Post Image (max:5):</label>
                                                    <div class="custom-file">
                                                        <input type="file"  name="post_image[]" id="post_image" value="Upload" multiple="multiple"
                                                               class="custom-file-input @error('post_image') invalid @enderror"
                                                               {{--onchange="document.getElementById('image_preview').src = window.URL.createObjectURL(this.files[0])"--}}>
                                                        <label class="custom-file-label" for="post_image">Choose file ....</label>
                                                    </div>
                                                </div>
                                                @error('post_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                <div class="col-lg-12 text-center m-t-20" id="preview_file_div">
                                                    <div class="ImageDangerWarning p-b-6" style="text-align: center; color: coral; font-size: 12px; font-weight: bold;"></div>
                                                    <ul class="ImageSelectStyle">
                                                        @if(count($Post->post_image) > 0)
                                                            @foreach($Post->post_image as $Img)
                                                                <li id="{{ $Img->id }}">
                                                                    <div class='ic-sing-file position-relative'>
                                                                        <img id='{{ $Img->id }}' src='/{{ $Img->thumbnail }}' title='{{ $Post->title }}' alt=''>
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
                                                    <textarea class="textarea form-control @error('post_body') invalid @enderror" required placeholder="Write your post details." name="post_body" id="post_body"
                                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                {{ old('post_body', $Post->body) }}
                                            </textarea>
                                                </div>
                                                @error('post_body')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="button Blue_button m-r-6"><i class="fa fa-edit m-r-6"></i> Update Post </button>

                                                <a href="{{ route('admin.post.read-more', ['id' => encrypt($Post->id), 'title' => $Post->title_slug]) }}" class="button Red_button button-sm">
                                                    <i class="fa fa-times m-r-4"></i>  Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </form>

                                @else
                                    <div class="col-12 py-1">
                                        <div class="text-center">
                                            <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> Post not Fount. </h4>
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
    <script>
        let nowTemp = new Date();
        let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        $('#publish_date').datepicker({
            beforeShowDay: function(date) {
                return date.valueOf() >= now.valueOf();
            },
            autoclose: true,
            format:"dd-mm-yyyy",
        });
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

            @if(count($Post->post_image) > 0)
                DB_len = {{ count($Post->post_image) }};
            @endif

            $("#post_image").change(function (event) {
                deleted_file_ids = []; // Clear array data on second file choose ...
                let len = input_file.files.length;
                $('#preview_file_div ul .selectedImagesToUpload').remove();

                //console.log();

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
                    //console.log($('#deletePreviousImage').val());
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
        });

        ClassicEditor
            .create( document.querySelector( '#post_body' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

@stop
