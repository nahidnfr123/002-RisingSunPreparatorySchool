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
                                    <input class="form-control form-control-navbar" type="text" name="search" placeholder="Search" aria-label="Search" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="m-t-10 m-l-10" id="ButtonHolder">
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
                        @if($subPageName == 'Search')
                            <div class="col-12 py-1">
                                <div class="text-center">
                                    <h4 class="text-muted text-monospace"> <i class="fa fa-search"></i> Search result for: {{ $Search_text }}</h4>
                                </div>
                            </div>
                            <hr>
                        @endif
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
