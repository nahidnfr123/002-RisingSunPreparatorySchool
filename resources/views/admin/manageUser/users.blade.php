@extends('layouts.admin.app_admin')

@php  $pageName = 'Users'  @endphp

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
                            <form class="form-inline ml-3 form" method="post" action="{{ route('admin.users.search') }}">
                                @csrf
                                <div class="input-group input-group-sm m-t-10">
                                    <input class="form-control form-control-navbar @error('search') is-invalid @enderror" type="text" name="search" placeholder="Search" aria-label="Search" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="m-t-10 m-l-10" id="ButtonHolder">
                                <button type="button" class="button Blue_button button-sm m-l-6" data-toggle="modal" data-target=".AddUserModal">
                                    <i class="fa fa-plus p-r-10 hideOnPhone"></i> Add User
                                </button>
                                @if(isset($subPageName) && $subPageName == 'Trash')
                                    <a href="{{ route('admin.users') }}" class="button Green_button button-sm m-l-6">
                                        <i class="fa fa-user-friends p-r-6 hideOnPhone"> </i> All Users
                                    </a>
                                @else
                                    <a href="{{ route('admin.users.trash') }}" class="button Red_button button-sm m-l-6">
                                        <i class="fa fa-trash p-r-6 hideOnPhone"> </i> Trash
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="{{--container--}}">
                    <div class="col-12 col-lg-12 col-xl-12 m-auto">
                        <div class="row d-flex align-items-stretch">
                            @if(count($Users) > 0)
                                @foreach($Users as $User)
                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                        <div class="card bg-gradient-light">
                                            <div class="card-header text-muted border-bottom-0">
                                                {{--Digital Strategist--}}
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b>{{ $User->first_name.' '.$User->last_name }}</b></h2>
                                                        @if($User->roles->pluck('name')->first() != 'User')
                                                            <div class="text-muted text-sm"><b>Job Title: <br>{{ $User->admin->job_title }} </b>
                                                                <br>
                                                                <hr>
                                                                <span class="text-muted text-sm"><b>Role: {{ '('. $User->roles->pluck('name')->first() .')' }} </b></span>
                                                            </div>
                                                        @else
                                                            <div class="text-muted text-sm"><b>:- {{ 'User' }} </b>
                                                                <br>
                                                                <hr>
                                                                <span class="text-muted text-sm"><b>Role: {{ '('. $User->roles->pluck('name')->first() .')' }} </b></span>
                                                            </div>
                                                        @endif
                                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> <b class="m-r-4">Phone: </b> {{ $User->phone }}</li>
                                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> <b class="m-r-4">Email: </b> {{ $User->email }}
                                                                - @if($User->email_verified_at == null) <span class="text-danger"><b>Not Verified</b></span> @else <span class="text-success"><b>Verified</b></span> @endif </li>
                                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-parachute-box"></i></span>
                                                                <b class="m-r-4">Status: </b>
                                                                @if($User->deleted_at == null)
                                                                    @if($User->status == null || $User->status == '') <span class="text-bold text-success">Active</span>
                                                                    @else
                                                                        @if($User->status == 'Blocked')
                                                                            <span class="text-bold text-danger">{{ $User->status }}</span>
                                                                        @else
                                                                            <span class="text-bold text-success">{{ $User->status }}</span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <span class="text-bold text-danger">Deleted</span>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-5 text-center">
                                                        <img src="/{{ $User->avatar }}" alt="" class="img-circle img-fluid" style="object-fit: cover; object-position: center top; box-shadow: -1px 6px 10px rgba(60,100,200,.6);">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-right row">
                                                    <div class="dropdown text-right col-12">
                                                        @if($User->roles->pluck('name')->first() != 'Super Admin')
                                                            <button class="btn bg-maroon btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu ActionMenu" aria-labelledby="dropdownMenuButton">
                                                                @if(isset($subPageName) && $subPageName == 'Trash')
                                                                    <a href="{{ route('admin.users.restore' , ['id' => encrypt($User->id)]) }}" class="dropdown-item text-success" title="Unblock"><i class="fa fa-trash-restore"></i> Restore</a>
                                                                @else
                                                                    @if($User->status == 'Blocked')
                                                                        <a href="{{ route('admin.users.unblock' , ['id' => encrypt($User->id)]) }}" class="dropdown-item text-success" title="Unblock"><i class="fa fa-check-square"></i> Unblock</a>
                                                                    @endif
                                                                    @if($User->status != 'Blocked')
                                                                        <a href="{{ route('admin.users.block' , ['id' => encrypt($User->id)]) }}" class="dropdown-item text-maroon" title="Block" onclick="return confirm('Are you sure you want to block the user?')">
                                                                            <i class="fa fa-user-times"></i> Block
                                                                        </a>
                                                                    @endif
                                                                    <a href="{{ route('admin.users.edit', ['id' => encrypt($User->id)]) }}" class="dropdown-item text-warning" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                                    <a href="{{ route('admin.users.delete' , ['id' => encrypt($User->id)]) }}" class="dropdown-item text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete the user?')">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                @endif
                                                            </div>

                                                            {{--<a href="" class="btn btn-sm bg-teal" title="Chat"><i class="fa fa-comments"></i></a>--}}
                                                            <a href="{{ route('admin.users.details', ['id'=>encrypt($User->id)]) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-user p-r-6"></i> Profile
                                                            </a>
                                                        @elseif(auth()->guard('admin')->id() != $User->id)
                                                            {{--<a href="" class="btn btn-sm bg-teal" title="Chat"><i class="fa fa-comments"></i></a>--}}
                                                            <a href="{{ route('admin.users.details', ['id'=>encrypt($User->id)]) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-user p-r-6"></i> Profile
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.profile') }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-user p-r-6"></i> Profile
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 py-1">
                                    <div class="text-center">
                                        <h4 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'User' found. </h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <nav aria-label="Contacts Page Navigation">
                    <div class="text-center row" style="position: relative; height: 40px;">
                        <div style="position: absolute;left: 50%;top: 60%;transform: translate(-50%, -50%)">{{ $Users->links() }}</div>
                    </div>
                </nav>
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->


        {{--Add user modal--}}
        <div class="modal fade bd-example-modal-lg AddUserModal" id="AddUserModal" tabindex="10" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index: 100000;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add new user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.users.add') }}" method="post" enctype="multipart/form-data" name="upload-file" id="form-upload-file" role="form">
                        <div class="modal-body">
                            @csrf
                            <div class="row m-b-10">
                                <div class="col-lg-6 col-12">
                                    <label for="First_Name">First name: </label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First name" id="First_Name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-12">
                                    <label for="Last_Name">Last name: </label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Last name" id="Last_Name" value="{{ old('last_name') }}" required>
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
                                    <input type="email" name="email" placeholder="email" id="Email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
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
                                            <option value="{{ $Role->id }}" @if(old('user_type') == $Role->id) selected @endif>{{ $Role->name }}</option>
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
                                <div class="col-lg-6 col-12">
                                    <label for="Gender">Gender: </label>
                                    <div class="form-group form-control  @error('gender') is-invalid @enderror">
                                        <div class="row">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input m-r-10" type="radio" id="Male" name="gender" required @if(old('gender') == 'Male') checked @endif value="Male">
                                                <label for="Male" class="custom-control-label m-l-10">Male</label>
                                            </div>
                                            <div class="custom-control custom-radio m-l-20">
                                                <input class="custom-control-input m-r-10" type="radio" id="Female" name="gender" required @if(old('gender') == 'Female') checked @endif value="Female">
                                                <label for="Female" class="custom-control-label m-l-10">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-12">
                                    <label for="Password">Temporary password: </label>
                                    <input type="password" name="password" placeholder="*******" id="Password" class="form-control @error('password') is-invalid @enderror" required min="8" max="60">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <label for="phone">Phone: </label>
                                    <input type="text" name="phone" id="phone" pattern="[0-9]{11,}" placeholder="01*********" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required minlength="11" maxlength="11">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-12">
                                    <label for="job_title">Job Title: </label>
                                    <input type="text" name="job_title" id="job_title" placeholder="Administrator, Teacher, etc" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" required>
                                    @error('job_title')
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


    @if(session('formError')) // edit for old data show problem ...
    <script>
        $(document).ready(function () {
            $('#AddUserModal').modal('toggle');
        });
    </script>
    @endif

@stop
