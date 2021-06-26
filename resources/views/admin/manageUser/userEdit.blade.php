@extends('layouts.admin.app_admin')

@php  $pageName = 'Users'  @endphp

@section('admin_content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body pb-0">

                @if($User == null)
                    <div class="col-12 py-1">
                        <div class="text-center">
                            <h4 class="text-muted text-monospace"> <i class="fa fa-search"></i> Unable to fetch user information.</h4>
                        </div>
                    </div>
                @else
                    <div class="{{--container--}}">
                        <div class="col-12 col-lg-12 col-xl-12 m-auto">
                            <div class="col-12 text-center m-b-20">
                                <img src="{{ $User->avatar }}" alt="" class="img-circle img-fluid" style="object-fit: cover; object-position: center top; box-shadow: -1px 6px 10px rgba(60,100,200,.6);height: 200px!important;">
                            </div>
                            <form action="{{ route('admin.users.update') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" hidden name="id" value="{{ encrypt($User->id) }}">
                                    <div class="row m-b-10">
                                        <div class="col-lg-6 col-12">
                                            <label for="First_Name">First name: </label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First name" id="First_Name" value="{{ old('first_name' ,$User->first_name) }}" required>

                                            @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <label for="Last_Name">Last name: </label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Last name" id="Last_Name" value="{{ old('last_name' ,$User->last_name) }}" required>
                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="col-lg-6 col-12">
                                            <label for="Email">Email: </label>
                                            <input type="email" name="email" placeholder="email" id="Email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email' ,$User->email) }}" required>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <label for="User_Type">User Type: </label>
                                            <select name="user_type" id="User_Type" class="form-control @error('user_type') is-invalid @enderror" required>
                                                <option value="0" disabled selected> -- select -- </option>
                                                @foreach($Roles as $key => $Role)
                                                    <option value="{{ $Role->id }}" @if($User->roles->pluck('name')->first() == $Role->name) selected @endif>{{ $Role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{--<div class="col-lg-6 col-12">
                                            <label for="Gender">Gender: </label>
                                            <div class="form-group form-control">
                                                <div class="row">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input m-r-10" type="radio" id="Male" name="gender" required @if($User->gender == 'Male') checked @endif value="Male">
                                                        <label for="Male" class="custom-control-label m-l-10">Male</label>
                                                    </div>
                                                    <div class="custom-control custom-radio m-l-20">
                                                        <input class="custom-control-input m-r-10" type="radio" id="Female" name="gender" required @if($User->gender == 'Female') checked @endif value="Female">
                                                        <label for="Female" class="custom-control-label m-l-10">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>--}}

                                        <div class="col-lg-6 col-12">
                                            <label for="phone">Phone: </label>
                                            <input type="number" name="phone" id="phone" placeholder="01*********" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone' ,$User->phone) }}" required>
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        @if($User->roles->pluck('name')->first() == 'Super Admin' || $User->roles->pluck('name')->first() == 'Admin')
                                            <div class="col-lg-6 col-12">
                                                <label for="job_title">Job Title: </label>
                                                <input type="text" name="job_title" id="job_title" placeholder="Administrator, Teacher, etc" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title' ,$User->admin->job_title) }}" required>
                                                @error('job_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        {{--<div class="col-lg-6 col-12">
                                            <label for="Password">Temporary password: </label>
                                            <input type="password" name="password" placeholder="*******" id="Password" class="form-control" required>
                                        </div>--}}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="button Blue_button pl-4 pr-4"><i class="fa fa-edit m-r-6"></i> Update </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@stop


@section('page_level_script')
    {{-- Page level script --}}
    <script>
        $(document).ready(function () {
            $('#ActionDropDown').click(function () {
                $( ".ActionMenu" ).show();
            });
        });
    </script>
@stop
