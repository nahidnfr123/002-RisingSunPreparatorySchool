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

                <div class="{{--container--}}">
                    <div class="row">
                        <div class="col-12 text-right row">
                            <!-- SEARCH FORM -->
                            <form class="form-inline ml-3 form" method="post" action="{{ route('admin.post.search') }}">
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
                                    <i class="fa fa-plus p-r-6 hideOnPhone"> </i> Add post
                                </button>

                                @if($subPageName == 'All posts') {{-- Show my post button. --}}
                                    <a href="{{ route('admin.post') }}" class="button CyanGreen_button button-sm m-l-6">
                                        <i class="fas fa-user p-r-6 hideOnPhone"> </i><i class="fas fa-box p-r-6 hideOnPhone"> </i> My posts
                                    </a>
                                @else {{-- Show all post button. --}}
                                    <a href="{{ route('admin.post.all') }}" class="button Green_button button-sm m-l-6">
                                        <i class="fas fa-box p-r-6 hideOnPhone"> </i> All posts
                                    </a>
                                @endif

                                <a href="{{ route('admin.post.trash') }}" class="button Red_button button-sm m-l-6">
                                    <i class="fa fa-trash p-r-6 hideOnPhone"> </i> Trash
                                </a>
                            </div>
                        </div>
                        <div class="text-muted"> {{ $postCount }} </div>
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
                                @if(count($AllPost) > 0) {{-- If posts are found. --}}
                                    @foreach($AllPost as $Post) {{-- Loop through the post. --}}
                                        {{--<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 m-auto">--}}
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-10 m-auto p-0" style="margin-bottom: 20px!important; padding: 0!important; ">
                                            <div class="card p-t-5 p-r-4 p-b-5 p-l-4" style="border: 2px solid rgba(20,20,20, .2); @if(auth()->guard('admin')->id() != $Post->user->id)  background: rgba(120,120,120,.2)!important; @endif">
                                                @if(count($Post->post_image) > 0) {{-- Post has Image --}}
                                                    <div class="card-header">
                                                        @foreach($Post->post_image->slice(0, 1) as $Img) {{-- Loop all the images. --}}
                                                            <div class="ImageContainer-Post">
                                                                <img src="/{{ $Img->thumbnail }}" alt="">
                                                                <span class="moreImageOverflow ShowMoreImages" data-post-id="{{ $Post->id }}"><i class="fa fa-images m-r-10"></i> {{ count($Post->post_image) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="card-body text-justify" style="height: auto;padding: 10px 10px 0 10px !important;">
                                                    <h4>{{ $Post->title }}</h4>
                                                    {{--<p class="text-truncate" style="font-size: 10px;!important;">{!! $Post->body !!}</p>--}}
                                                    {{--{!! \Illuminate\Support\Str::limit($Post->body, 100, '...') !!}--}}
                                                    {{--{!! substr($Post->body, 0, 200) . '...' !!}--}}
                                                    <div style="font-size: 14px;!important;">
                                                        @php
                                                            $string = strip_tags($Post->body);
                                                            if (strlen($string) > 200) {
                                                                // truncate string
                                                                $stringCut = substr($string, 0, 260);
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
                                                            @if(auth()->guard('admin')->id() == $Post->user->id) {{-- If post is from current user Show ME --}}
                                                                Me,
                                                            @else
                                                                @if(auth()->user()->roles()->pluck('name')->first() == 'Super Admin') {{-- If Admin is logged on then show profile link --}}
                                                                    <a href="{{ route('admin.users.details', ['id' => encrypt($Post->user->id)]) }}">{{ $Post->user->first_name }}</a>,
                                                                @else
                                                                    {{ $Post->user->first_name }},
                                                                @endif
                                                            @endif
                                                        </span>
                                                        <span>{{ date('d-M-Y', strtotime($Post->publish_date)) }}</span>
                                                        <span>, {{ $Post->visitor }} views</span>
                                                    </div>
                                                    <div class="btn-group-xs  text-right">
                                                        <a href="{{ route('admin.post.read-more', ['id' => encrypt($Post->id), 'title' => $Post->title_slug]) }}" type="button" class="btn btn-xs bg-gradient-success">
                                                            <i class="fab fa-readme p-r-6"></i> Read more
                                                        </a>
                                                        @if(auth()->guard('admin')->id() == $Post->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                                            <a type="button" href="{{ route('admin.post.edit', ['id'=>encrypt($Post->id)]) }}" class="btn btn-xs bg-gradient-teal"><i class="fa fa-edit p-r-6"></i> Edit</a>
                                                            <a type="button" href="{{ route('admin.post.delete', ['id'=>encrypt($Post->id)]) }}" class="btn btn-xs bg-gradient-red text-white" onclick="return confirm('Are you sure you want to delete this post?')">
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
                                                <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No post found... </h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 py-1">
                                            <div class="text-center">
                                                <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'post' available. </h4>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @if(count($AllPost) > 0){{-- Pagination --}}
                                <div class="col-12 m-auto position-relative" style="height: 50px;">
                                    <div style="position: absolute; bottom: -20px; left: 50%; transform: translate(-50%, -50%);">
                                        {{ $AllPost->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Add post modal --}}
                <div class="modal fade bd-example-modal-lg AddPostModal" id="AddPostModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add new post</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.post.add') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                                <div class="modal-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="post_title" class="col-form-label-sm label">Post Title: <span class="text-danger">*</span></label>
                                                <input type="text" name="post_title" class="form-control @error('post_title') invalid @enderror" id="post_title" required value="{{ old('post_title') }}" placeholder="Post title ....">
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
                                                       value="{{ old('publish_date', \Carbon\Carbon::now()->format('d-m-Y')) }}" readonly required>
                                            </div>
                                            @error('publish_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
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

                                                </ul>
                                            </div>
                                        </div>

                                        <input type="hidden" hidden readonly value="" name="dismissedImage" id="dismissedImage">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="post_body" class="label col-form-label-sm">Post Description: <span class="text-danger">*</span></label>
                                                <textarea class="textarea form-control @error('post_body') invalid @enderror" required placeholder="Write your post details." name="post_body" id="post_body"
                                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                    {{ old('post_body') }}
                                                </textarea>
                                            </div>
                                            @error('post_body')
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


        $(document).ready(function () { // Delete Image form A Post ....
            $(".DeleteSingleImage").click(function (e) {
                e.preventDefault();
                let ImgId = 0;
                $("#slider1 input:radio").each(function () {
                    let $this = $(this);
                    if (this.checked) {
                        ImgId = $this.attr('value');
                    }
                });
                if(confirm('Are you sure you want to delete this image?')){ // Confirm deletion .....
                    window.location= "{{ url('admin/post/image/delete') }}"+"/"+ ImgId;
                }
            });
        });

        $(function () { // Add Post Form ....
            let input_file = document.getElementById('post_image'); // Get File input element id ...
            let deleted_file_ids = [];
            let dynm_id = 0;
            $("#post_image").change(function (event) { // Image File Input Change ...
                deleted_file_ids = []; // Clear array data on second file choose ...
                let len = input_file.files.length; // Get the number of file chosen ....
                $('#preview_file_div ul').html(""); // Clear Image holding element ....

                for(let j=0; j<len; j++) { // Loop for each selected file ....
                    let src = ""; // Get file to display as image preview ...
                    let name = event.target.files[j].name; // Get the file name ...
                    let mime_type = event.target.files[j].type.split("/"); // Check file type ...
                    if(mime_type[0] == "image") { // If file is a image ...
                        src = URL.createObjectURL(event.target.files[j]);
                    } else if(mime_type[0] == "video") { // If file is a video ...
                        src = "{{ asset('storage/image/web_layout/icon/video.png') }}";
                    } else { // Any other file ...
                        src = "{{ asset('storage/image/web_layout/icon/file.png') }}";
                    }
                    // Add Each File to the UL element ....
                    $('#preview_file_div ul').append("" +
                        "<li id='" + dynm_id + "'>" +
                            "<div class='ic-sing-file position-relative'>" +
                                "<img id='" + dynm_id + "' src='"+src+"' title='"+name+"' alt=''>" +
                                "<i class='fa fa-times closeImg' id='" + dynm_id + "'></i>" +
                            "</div>" +
                        "</li>");
                    dynm_id++;
                }
                if(len > 5){ // Number of file is greater then 5 then show error.
                    $('.ImageDangerWarning').html('Maximum image limit is 5. Please remove some images.');
                }else{ // else clear warning ....
                    $('.ImageDangerWarning').html('');
                }
            });

            $(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');
                deleted_file_ids.push(id);
                $('li#'+id).remove();

                //console.log(input_file.files.length - deleted_file_ids.length);
                $('#dismissedImage').val(deleted_file_ids);

                if((input_file.files.length - deleted_file_ids.length) == 0){
                    document.getElementById('post_image').value="";
                    document.getElementById('dismissedImage').value="";
                }
                if((input_file.files.length - deleted_file_ids.length) <= 5){
                    $('.ImageDangerWarning').html('');
                }
                /*console.log(id);
                input_file.files[id].remove;
                console.log(input_file.target.files[id]);*/
            });
        });
    </script>


    <script>
        /*$(function () {
            // Summernote
            $('#post_body').summernote({
                placeholder: 'Write post here....',
                height: '100px',
                tabsize: 2,
            });
        });*/

        ClassicEditor
            .create( document.querySelector( '#post_body' ) )
            .catch( error => {
                console.error( error );
            } );

        $(document).ready(function () {
            /*$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });*/
            //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});

            $('.ClosePostImage').click(function () {
                $('#ShowPostImages').hide();
                $("#ShowPostImagesBody").html('<div style="margin: auto; text-align: center;">\n' +
                    '<img src="{{ asset("asset_gallery/img/loading (2).gif") }}" alt="" height="60" style="margin: calc(50vh - 30px) auto; text-align: center;">\n' +
                    '</div>');
            });

            // Show All images using ajax....
            $(".ShowMoreImages").click(function () {
                let Post_id = $(this).attr('data-post-id');
                $('#ShowPostImages').show();
                $.ajax({
                    type:'POST',
                    url:'{{ route('admin.post.images.show') }}',
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

    @if(session('formError')) // edit for old data show problem ...
    <script>
        $(document).ready(function () {
            $('#AddPostModal').modal('toggle');
        });
    </script>
    @endif

@stop
