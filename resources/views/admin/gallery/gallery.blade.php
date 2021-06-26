@extends('layouts.admin.app_admin')

@php  $pageName = 'Gallery'; @endphp
@if(!isset($subPageName)) {{ $subPageName = '' }} @endif

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300i,400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset_gallery/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset_gallery/css/owl.theme.default.min.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/lightgallery.min.css') }}">


    <link rel="stylesheet" href="{{ asset('asset_gallery/fonts/flaticon/font/flaticon.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/swiper.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('asset_gallery/css/style.css') }}">
@stop

@section('admin_content')

    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">

                <div class="{{--container--}}">
                    <div class="row">
                        <div class="col-12 text-right row">
                            <!-- SEARCH FORM -->
                            <form class="form-inline ml-3 form" method="post" action="{{ route('admin.gallery.search') }}">
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
                                <!-- Button trigger modal -->
                                <button type="button" class="button Blue_button button-sm m-l-6" data-toggle="modal" data-target="#CreateGalleryModal">
                                    <i class="fa fa-plus m-r-6 hideOnPhone"></i>  Create Gallery
                                </button>
                                @if($subPageName == 'Search')
                                    <a href="{{ route('admin.gallery') }}" class="button button-sm CyanGreen_button m-l-6">
                                        <i class="fas fa-image p-r-6 hideOnPhone"> </i> Show All Galleries
                                    </a>
                                @endif
                                {{--<button type="button" class="btn btn-sm bg-gradient-maroon text-right">Delete Images</button>--}}
                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <div class="{{--container--}}"  data-aos="fade">
                    <div class="container-fluid">
                        @if($subPageName == 'Search')
                            <div class="col-12 py-1">
                                <div class="text-center">
                                    <h5 class="text-muted text-monospace"> <i class="fa fa-search"></i> Search result for: {{ $Search_text }}</h5>
                                </div>
                            </div>
                        @endif
                        <div class="row" id="lightgallery">
                            @if(count($GalleryImages) > 0)
                                @foreach($GalleryImages as $Gallery)
                                    <div class="col-12 m-t-20">
                                        <h4><span>{{ $Gallery->gallery_title }} </span> <span style="font-size: 12px"> ({{ $Gallery->user->first_name .' '.$Gallery->user->last_name. ', ' . \Carbon\Carbon::parse($Gallery->created_at)->format('Y-m-d h:i a') }} )</span></h4>
                                        @if(auth()->guard('admin')->id() == $Gallery->user->id || auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                                        <div class="btn-group-xs">
                                            <button type="button" class="btn btn-xs btn-info rounded AddMoreImageToGallery" title="Add Image"
                                                    data-toggle="modal" data-target="#EditGallery"
                                                    data-title="{{ $Gallery->gallery_title }}" data-id="{{ encrypt($Gallery->id)  }}">
                                                <i class="fa fa-plus p-r-4"></i>  Edit Gallery
                                            </button>
                                            {{--<a href="{{ route('admin.gallery.image.edit', ['id' => encrypt($Gallery->id)]) }}" class="btn btn-xs bg-gradient-pink rounded" title="Edit Gallery">
                                                <i class="fa fa-edit p-r-4"></i> Edit Gallery
                                            </a>--}}
                                            <a href="{{ route('admin.gallery.destroy', ['id' => encrypt($Gallery->id)]) }}" class="btn btn-xs btn-danger rounded" title="Delete Gallery"
                                               onclick="return confirm('Are you sure you want to delete this gallery?')">
                                                <i class="fa fa-trash p-r-4"></i> Delete Gallery
                                            </a>
                                        </div>
                                        @endif
                                        {{--<div class="clearfix"></div>--}}
                                        <hr>
                                    </div>
                                    @if(count($Gallery->gallery_image) > 0)
                                        @foreach($Gallery->gallery_image as $Img)
                                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 item" data-aos="fade" data-src="/{{ $Img->image }}"
                                                 data-sub-html="<h4>{{ $Gallery->gallery_title }}</h4>">
                                                <a href="#"><img src="/{{ $Img->thumbnail }}" alt="IMage" class="img-fluid"></a>
                                            </div>
                                        @endforeach
                                        {{--<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 text-center" data-aos="fade" style="display: flex; align-content: center; vertical-align: center; ">
                                            <a><i class="fa fa-plus" style="font-size: 30px;"></i></a>
                                        </div>--}}
                                    @else
                                        <div class="col-12 py-1">
                                            <div class="text-center">
                                                <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Image' in this gallery. </h5>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @if($subPageName == 'Search')
                                    <div class="col-12 py-1">
                                        <div class="text-center">
                                            <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No results found... </h5>
                                        </div>
                                    </div>
                                @else
                                <div class="col-12 py-1">
                                    <div class="text-center">
                                        <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Gallery' available. </h5>
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
                @if(count($GalleryImages) > 0)
                    <div class="col-12 m-auto position-relative" style="height: 60px;">
                        <div style="position: absolute; bottom: -20px; left: 50%; transform: translate(-50%, -50%);">
                            {{ $GalleryImages->links() }}
                        </div>
                    </div>
                @endif

                {{-- Create gallery Modal --}}
                <!-- Modal -->
                <div class="modal fade" id="CreateGalleryModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Create Gallery</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=" return clearAll();">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.gallery.image.upload') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                                <div class="modal-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="gallery_title" class="col-form-label-sm label"><b>Gallery Title:</b></label>
                                                <div class="custom-file">
                                                    <input type="text" name="gallery_title" class="form-control @error('gallery_title') invalid @enderror" id="gallery_title" value="{{ old('gallery_title') }}" placeholder="Event title ....">
                                                </div>
                                            </div>
                                            @error('gallery_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="gallery_image" class="label col-form-label-sm"><b>Gallery Image (max:20): <span class="text-danger">*</span></b></label>
                                                <div class="custom-file">
                                                    <input type="file"  name="gallery_image[]" id="post_image" value="Upload" multiple="multiple" required
                                                           class="custom-file-input @error('gallery_image') invalid @enderror"
                                                        {{--onchange="document.getElementById('image_preview').src = window.URL.createObjectURL(this.files[0])"--}}>
                                                    <label class="custom-file-label" for="gallery_image">Choose file ....</label>
                                                </div>
                                            </div>
                                            @error('gallery_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-lg-12 text-center m-t-20" id="preview_file_div">
                                                <div class="ImageDangerWarning p-b-6" style="text-align: center; color: coral; font-size: 12px; font-weight: bold;"></div>
                                                <ul class="ImageSelectStyle2" id="CreateGalleryPreviewImage">

                                                </ul>
                                            </div>
                                        </div>

                                        <input type="hidden" hidden readonly value="" name="dismissedImage" id="dismissedImage">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="button Blue_button pl-4 pr-4"><i class="fa fa-upload m-r-6"></i> Upload </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- Edit Gallery --}}
                <div class="modal fade bd-example-modal-lg" id="EditGallery" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Gallery</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return clearAll();">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-files" role="form">
                                <div class="modal-body">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="edit_gallery_title" class="col-form-label-sm label"><b>Gallery Title:</b></label>
                                                <div class="custom-file">
                                                    <input type="text" name="gallery_title" class="form-control @error('gallery_title') invalid @enderror" id="edit_gallery_title" value="{{ old('gallery_title') }}" placeholder="Event title ....">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="gallery_image" class="label col-form-label-sm"><b>Gallery Image (max:20): <span class="text-danger">*</span></b></label>
                                                <div class="custom-file">
                                                    <input type="file"  name="gallery_image[]" id="gallery_image" value="Upload" multiple="multiple"
                                                           class="custom-file-input @error('gallery_image') invalid @enderror"
                                                        {{--onchange="document.getElementById('image_preview').src = window.URL.createObjectURL(this.files[0])"--}}>
                                                    <label class="custom-file-label" for="gallery_image">Choose file ....</label>
                                                </div>
                                            </div>
                                            @error('gallery_image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 text-center m-t-20" id="preview_file_div2">
                                            <div class="ImageDangerWarning2 p-b-6" style="text-align: center; color: coral; font-size: 12px; font-weight: bold;"></div>
                                            <div class="col-12 text-muted fs-12" id="NewImageText" style="display: none;"><b>New Image: </b></div>
                                            <ul class="ImageSelectStyle2" id="EditGalleryPreviewImage">

                                            </ul>
                                        </div>
                                        <input type="hidden" hidden readonly value="" name="dismissedImage" id="dismissedImage2">
                                        <input type="hidden" hidden readonly value="" name="deletePreviousImage" id="deletePreviousImage">
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12 text-muted fs-12"><b>Uploaded Image:</b></div>
                                        {{-- Image upload progress bar --}}
                                        <div class="col-12" id="FormLoadImageProgress">
                                            {{--<progress id="progress" class="col-12" value="0"></progress>--}}
                                            <div class="progress col-12 p-0">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress" role="progressbar"
                                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="ShowUploadedImage" style="width: 100%; max-height:200px; overflow-y: scroll; padding-top: 5px; position: relative;">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="button Blue_button pl-4 pr-4"> Update Gallery </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop


@section('page_level_script')
    <script src="{{ asset('asset_gallery/js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/swiper.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/aos.js') }}"></script>

    <script src="{{ asset('asset_gallery/js/picturefill.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('asset_gallery/js/jquery.mousewheel.min.js') }}"></script>

    <script src="{{ asset('asset_gallery/js/main.js') }}"></script>

    <script src="{{ asset('asset_gallery/bootstrap_fileinput.js') }}"></script>
    <script src="{{ asset('asset_gallery/bootstrap_fileinput_themes.js') }}"></script>

    <script>
        $(document).ready(function(){
            $('#lightgallery').lightGallery({
                pager: true,
                selector: '.item'
            });
        });
    </script>


    <script>
        function clearAll(){
            document.getElementById('gallery_title').value = '';
            document.getElementById('edit_gallery_title').value = '';
            document.getElementById('dismissedImage').value = '';
            document.getElementById('dismissedImage2').value = '';
            document.getElementById('deletePreviousImage').value = '';
            document.getElementById('ShowUploadedImage').innerHTML = '';

            document.getElementById('EditGalleryPreviewImage').innerHTML = '';
            document.getElementById('CreateGalleryPreviewImage').innerHTML = '';

            document.querySelector('#preview_file_div ul').innerHTML = '';
            document.getElementsByClassName('ImageDangerWarning').innerHTML = '';

            //document.querySelector('#preview_file_div2 ul').innerHTML = '';
            document.getElementsByClassName('ImageDangerWarning2').innerHTML = '';

            let progressBar = document.getElementById("progress");
            progressBar.style.display = 'none';
           /*
            $('#preview_file_div ul').html("");
            $('.ImageDangerWarning').html('');

            $('#preview_file_div2 ul').html("");
            $('.ImageDangerWarning2').html('');*/
        }

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
                        "<div class='ic-sing-file2 position-relative'>" +
                        "<img id='" + dynm_id + "' src='"+src+"' title='"+name+"' alt='' height='100'>" +
                        "<i class='fa fa-times closeImg' id='" + dynm_id + "'></i>" +
                        "</div>" +
                        "</li>");
                    dynm_id++;
                }
                if(len > 20){
                    $('.ImageDangerWarning').html('Maximum image limit is 20. Please remove some images.');
                }else{
                    $('.ImageDangerWarning').html('');
                }
            });

            $(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');
                deleted_file_ids.push(id);
                $('li#'+id).remove();

                //console.log(input_file.files.length - deleted_file_ids.length);
                //console.log(deleted_file_ids);
                $('#dismissedImage').val(deleted_file_ids);

                if((input_file.files.length - deleted_file_ids.length) == 0){
                    document.getElementById('post_image').value="";
                    document.getElementById('dismissedImage').value="";
                }
                if((input_file.files.length - deleted_file_ids.length) <= 20){
                    $('.ImageDangerWarning').html('');
                }
                /*console.log(id);
                input_file.files[id].remove;
                console.log(input_file.target.files[id]);*/
            });
        });



        $(function () {
            let input_file2 = document.getElementById('gallery_image');
            let deleted_file_ids2 = [];
            let deleted_db_file_ids = [];
            let dynm_id2 = 0;
            $("#gallery_image").change(function (event) {
                deleted_file_ids2 = []; // Clear array data on second file choose ...
                let len = input_file2.files.length;
                $('#preview_file_div2 ul').html("");

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
                    $('#preview_file_div2 ul').append("" +
                        "<li id='" + dynm_id2 + "'>" +
                        "<div class='ic-sing-file2 position-relative'>" +
                        "<img id='" + dynm_id2 + "' src='"+src+"' title='"+name+"' alt='' height='100'>" +
                        "<i class='fa fa-times closeImg' id='" + dynm_id2 + "'></i>" +
                        "</div>" +
                        "</li>");
                    dynm_id2++;
                }
                $('#NewImageText').show();
                if(len > 20){
                    $('.ImageDangerWarning2').html('Maximum image limit is 20. Please remove some images.');
                }else{
                    $('.ImageDangerWarning2').html('');
                }
            });

            /*$(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');
                deleted_file_ids2.push(id);
                $('li#'+id).remove();

                $('#dismissedImage2').val(deleted_file_ids2);
                if((input_file2.files.length - deleted_file_ids2.length) == 0){

                    document.getElementById('gallery_image').value="";
                    document.getElementById('dismissedImage2').value="";
                }
                if((input_file2.files.length - deleted_file_ids2.length) <= 20){
                    $('.ImageDangerWarning2').html('');
                }
            });
*/

            $(document).on('click','i.closeImg', function() {
                var id = $(this).attr('id');

                if($(this).attr('data-id') == 'previousImage'){
                    deleted_db_file_ids.push(id);
                    $('li#'+id).remove();
                    $('#deletePreviousImage').val(deleted_db_file_ids);
                }
                else{
                    deleted_file_ids2.push(id);
                    $('li#'+id).remove();
                    $('#dismissedImage2').val(deleted_file_ids2);
                }
                if($("#preview_file_div2 ul").children("li").length <= 20){
                    $('.ImageDangerWarning2').html('');
                }


                //console.log(input_file.files.length - deleted_file_ids.length);
                //console.log(deleted_file_ids);



                if((input_file2.files.length - deleted_file_ids2.length) == 0){
                    document.getElementById('gallery_image').value="";
                    document.getElementById('dismissedImage2').value="";

                    $('#NewImageText').hide();
                }

                //console.log((DB_len + input_file.files.length) - deleted_file_ids.length);
                //console.log($('#dismissedImage').val());
                //alert($('#dismissedImage').val());
                /*console.log(id);
                input_file.files[id].remove;
                console.log(input_file.target.files[id]);*/
            });
        });



        $('.AddMoreImageToGallery').click(function () {
            let $this = $(this);
            let Title  = $this.attr('data-title');
            let Id  = $this.attr('data-id');
            let progressBar = document.getElementById("progress");
            progressBar.classList.remove('bg-danger');
            progressBar.innerHTML = '<b>Loading ...</b>';
            progressBar.style.display = 'block';
            $.ajax({
                type:'POST',
                url:'{{ route('admin.gallery.image.edit') }}',
                data: { "gallery_id" :  Id, "_token": "{{ csrf_token() }}" },
                success:function(data) {
                    $('#EditGallery form #edit_gallery_title').val(data.Title);
                    $("#ShowUploadedImage").html('');
                    $("#ShowUploadedImage").append(data.Output);
                    progressBar.style.display = 'none';
                }
            }).fail(function (e) {
                $("#ShowUploadedImage").html('<div class="alert alert-danger">Failed to retrieve image from database.</div>');
                progressBar.classList.add('bg-danger');
                progressBar.innerHTML = '<b>Failed Loading Data ...</b>';
                progressBar.style.display = 'block';
            });
            $('#EditGallery form').attr('action', '{{ url('admin/gallery/image/update/') }}' + '/' + Id);
        });

    </script>

@stop
