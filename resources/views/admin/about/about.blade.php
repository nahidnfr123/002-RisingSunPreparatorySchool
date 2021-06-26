@extends('layouts.admin.app_admin')

@php  $pageName = 'About';  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('admin_content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

            @if($About == null)
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $pageName }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <form action="{{ route('admin.about.add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea name="about" id="about" cols="30" rows="10" class="form-control" required>{{ old('about') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center p-b-20">
                                <input type="submit" name="Save" value="Save" class="button Blue_button">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer p-0">

                    </div>
                </div>
            </div>
            @else
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $pageName }}</h3>
                        </div>
                        <div class="card-body p-0">
                            <form action="{{ route('admin.about.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" hidden name="id" value="{{ $About->id }}" readonly required>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="aboutEdit"></label>
                                        <textarea name="about" id="aboutEdit" cols="30" rows="10" class="form-control" required readonly><div>{!! $About->about !!}</div></textarea>
                                    </div>
                                </div>
                                <div class="col-12 text-center p-b-20">
                                    <input type="button" name="Save" value="Edit" id="EditBtn" class="button Blue_button button-sm">
                                    <div class="btn-group-sm" id="GroupBtn" style="display: none;">
                                        <input type="submit" name="Save" value="Update" class="button Blue_button button-sm">
                                        <input type="button" name="Save" value="Cancel" id="CancelBtn" class="button Red_button button-sm">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer p-0">

                        </div>
                    </div>
                </div>
            @endif


        </div>
    </section>

@stop


@section('page_level_script')
    <script>
        $(document).ready(function () {
            $(function () {
                //Add text editor
                $('#about').summernote({
                    height: 300,
                });
                $('#aboutEdit').summernote('disable');
                $('#aboutEdit').summernote({
                    height: 300,
                });
            });

            $('#EditBtn').click(function () {
                $('#EditBtn').hide();
                $('#GroupBtn').show();
                return notReadOnly();
            });



            $('#CancelBtn').click(function () {
                $('#EditBtn').show();
                $('#GroupBtn').hide();
                return readOnly();
            });


            function readOnly() {
                $('#aboutEdit').summernote('disable');
            }

            function notReadOnly() {
                $('#aboutEdit').summernote('enable');
                $('#aboutEdit').summernote({
                    height: 300,
                });
            }
        });
    </script>
@stop
