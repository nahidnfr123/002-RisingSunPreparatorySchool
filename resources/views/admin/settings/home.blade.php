@extends('layouts.admin.app_admin')

@php  $pageName = 'Page Settings'; $subPageName = 'Images';  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('admin_content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

            {{--@include('layouts.admin.partials.pages_side_nav')--}}

            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $subPageName }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="mailbox-controls">
                            {{--<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i></button>--}}
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" id="AddFormToTable" data-toggle="modal" data-target="#SettingsFormModal"><i class="fa fa-plus"></i></button>
                                {{--<button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>--}}
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="reload"><i class="fas fa-sync-alt"></i></button>
                            {{--<div class="float-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>--}}
                        </div>
                        <hr>
                        <div class="table-responsive mailbox-messages">

                            <div class="col-12 alert" id="alertMSG" style="display: none;"></div>

                            <table id="HomeTable" class="table table-hover table-bordered table-striped col-12">
                                <thead>
                                <tr>
                                    <th style="width: 20px">#</th>
                                    <th style="width: 100px">Image</th>
                                    <th>Title</th>
                                    <th>Details</th>
                                    <th style="width: 60px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--@if (count($home_datas) > 0)
                                    @foreach($home_datas as $home_data)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="check{{ $home_data->id }}" value="{{ $home_data->id }}" @if($home_data->home == 1) checked @endif
                                                    onclick="statusUpdate({{ $home_data->id }});">
                                                    <label for="check{{ $home_data->id }}" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">
                                                    </label>
                                                </div>
                                            </td>
                                            <td> <img src="/{{ $home_data->thumbnail }}" alt="" width="100%" height="50" style="object-fit: cover; object-position: center;"> </td>
                                            <td> {{ $home_data->title }} </td>
                                            <td> {{ $home_data->details }} </td>
                                            <td>
                                                <button><i class="fa fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif--}}
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer p-0">

                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- modal --}}
   <div class="modal fade bd-example-modal-lg" id="SettingsFormModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new slider image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pages.image.add') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="title" class="col-form-label-sm label">Title:</label>
                                    <input type="text" name="title" class="form-control @error('title') invalid @enderror" id="title" value="{{ old('title') }}" placeholder="Image title ....">
                                </div>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="image" class="label col-form-label-sm">Image: <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file"  name="image" id="image" value="Upload" required
                                               class="custom-file-input @error('image') invalid @enderror"
                                               onchange="document.getElementById('image_preview').src = window.URL.createObjectURL(this.files[0])">
                                        <label class="custom-file-label" for="post_image">Choose file ....</label>
                                    </div>
                                </div>

                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <img src="" alt="" id="image_preview" height="60" width="100" style="object-fit: cover; object-position: center;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="details" class="label col-form-label-sm">Details:</label>
                                    <textarea class="textarea form-control @error('details') invalid @enderror" name="details" id="details" cols="5"
                                              style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('details') }}</textarea>
                                </div>
                                @error('details')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center">
                            <button type="submit" class="button Blue_button pl-4 pr-4" id="SubmitForm"> Submit </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="e_SettingsFormModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit slider image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pages.image.update') }}" method="post" enctype="multipart/form-data" name="upload-file" id="e_form-upload-file" role="form">
                    <div class="modal-body">
                        <input type="hidden" name="id" hidden required readonly value="" id="img_id">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="e_title" class="col-form-label-sm label">Title:</label>
                                    <input type="text" name="title" class="form-control @error('title') invalid @enderror" id="e_title" value="{{ old('title') }}" placeholder="Image title ....">
                                </div>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="e_image" class="label col-form-label-sm">Image: <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file"  name="image" id="e_image" value="Upload"
                                               class="custom-file-input @error('image') invalid @enderror"
                                               onchange="document.getElementById('e_image_preview').src = window.URL.createObjectURL(this.files[0])">
                                        <label class="custom-file-label" for="post_image">Choose file ....</label>
                                    </div>
                                </div>

                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <img src="" alt="" id="e_image_preview" height="60" width="100" style="object-fit: cover; object-position: center;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="e_details" class="label col-form-label-sm">Details:</label>
                                    <textarea class="textarea form-control @error('details') invalid @enderror" name="details" id="e_details" cols="5"
                                              style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('details') }}</textarea>
                                </div>
                                @error('details')
                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-12 text-center">
                            <button type="submit" class="button Blue_button pl-4 pr-4" id="Update"> Update </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop


@section('page_level_script')

    <script src="{{ asset('asset_backend/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('asset_backend/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        $(document).ready( function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#HomeTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                ajax: "{{ route('admin.home_settings.index') }}",
                columns: [
                    /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                    {data: '#', name: '#', orderable: false, searchable: false},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'title', name: 'title', searchable: true},
                    {data: 'details', name: 'details', searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('.CheckBoxes').each(function () {
                this.hide();
            });

            if($(".CheckBoxes div input").is(':checked'))
                $(this).show();  // checked
            else
                $(this).hide();  // unchecked

           /* if ($('.CheckBoxes' div input').checked) {

            } else {

            }*/
            //Upload image ...
            /*$('#form-upload-file').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this); console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: '{{-- route('admin.home.settings.add') --}}',
                    cache:false,
                    contentType: false,
                    processData: false,
                    data:formData,
                    /!*data: {
                        image: $('#UploadImage').val(),
                        title: $('#title').val(),
                        details: $('#details').val(),
                    },*!/
                    dataType:'JSON',
                    success: function (data) {
                        if (data.success){
                            $('#SettingsFormModal').modal('hide');
                            $("#alertMSG").removeClass('alert-danger');
                            $("#alertMSG").html(data.success);
                            $("#alertMSG").addClass('alert-success').fadeIn(500).delay(2000).fadeOut(500);

                            //Success
                            $('#FormInTable').remove();
                            $('#HomeTable').DataTable().ajax.reload();
                        }else {
                            $("#alertMSG").html(data.error);
                            $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                        }
                    }, error: function () {
                        $("#alertMSG").html('Internal error');
                        $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                    }
                });
            }));*/



        });

        $('#reload').click(function () {
            $('#HomeTable').DataTable().ajax.reload();
            resetForm();
        });

        function updateImg(id, page_name) {
            $.ajax({
                type:'POST',
                url:'{{ route('admin.home.settings.status.update') }}',
                data: { "id" :  id, "page_name" : page_name, "_token": "{{ csrf_token() }}" },
                success:function(data) {
                    if(data.errors){
                        $("#alertMSG").html('Internal error');
                        $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                    }else{
                        $("#alertMSG").html(data.Output);
                        $("#alertMSG").removeClass('alert-danger');
                        $("#alertMSG").removeClass('alert-warning');
                        $("#alertMSG").addClass(data.AlertStatus).fadeIn(500).delay(2000).fadeOut(500);
                        $('#HomeTable').DataTable().ajax.reload();
                        resetForm();
                    }
                },
                error:function () {
                    $("#alertMSG").html('Internal error');
                    $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                }
            });
        }


        function deleteImg(id) {
            if (confirm('Are you sure you want to delete the image.') == true) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.home.settings.status.delete') }}',
                    data: {"id": id, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        if (data.errors) {
                            $("#alertMSG").html('Internal error');
                            $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                        } else {
                            $("#alertMSG").html(data.Output);
                            $("#alertMSG").removeClass('alert-danger');
                            $("#alertMSG").addClass(data.AlertStatus).fadeIn(500).delay(2000).fadeOut(500);
                            $('#HomeTable').DataTable().ajax.reload();
                        }
                    },
                    error: function () {
                        $("#alertMSG").html('Internal error');
                        $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                    }
                });
            }
        }


        function editImg(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.home.settings.status.edit') }}',
                data: {"id": id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $('#img_id').val(data.id);
                    $('#e_title').val(data.title);
                    $('#e_details').val(data.details);
                    $("#e_image_preview").attr("src", '/'+data.thumbnail);
                    $('#e_SettingsFormModal').modal('show');
                },
                error: function () {
                    $("#alertMSG").html('Internal error');
                    $("#alertMSG").addClass('alert-danger').fadeIn(500).delay(3000).fadeOut(500);
                }
            });
        }

        function resetForm() {
            $("#image_preview").attr("src", '');
            $('#form-upload-file').trigger("reset");
        }

    </script>

    @if(session('formError')) // edit for old data show problem ...
    <script>
        $(document).ready(function () {
            $('#SettingsFormModal').modal('show');
        });
    </script>
    @endif
@stop
