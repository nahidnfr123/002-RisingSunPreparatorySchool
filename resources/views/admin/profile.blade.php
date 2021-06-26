@extends('layouts.admin.app_admin')

@php  $pageName = 'Profile'  @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_gallery/themes/csslider.default.css') }}">
    <link rel="stylesheet" href="{{ asset('commonAsset/mydatepicker/datepicker.min.css') }}">
@stop

@section('admin_content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <div class="pos-relative" style="height: 100px; width: 100px; margin: auto;">
                                    <img class="profile-user-img img-fluid img-circle"
                                         src="/{{ $User->avatar }}"
                                         style="object-fit: cover; object-position: center top;height: 100px!important; width: 100px!important;">

                                    <!-- Hidden Form for uploading profile image... -->
                                    <form action="{{ route('admin.profile.update_avatar') }}" method="Post" enctype="multipart/form-data" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="uid" hidden value="{{  auth()->id() }}" required readonly>
                                        <input type="file" name="avatar" id="select_Profile_Pic_file" hidden required readonly>
                                        <button type="submit" class="btn" id="submit_profile_image_file" name="UploadProfilePic" title="Upload Profile Image" hidden></button>
                                    </form>

                                    <button type="button" id="BtnCamera" class="pos-absolute">
                                        <i class="fa fa-camera"></i>
                                    </button>
                                </div>
                            </div>

                            <h3 class="profile-username text-center">{{ $User->first_name.' '.$User->last_name}}</h3>

                            <p class="text-muted text-center">
                                {{ $User->admin->job_title }}
                                <br>
                                {{ '('. $User->roles->pluck('name')->first() .')' }}
                            </p>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" id="tabActivity" href="#activity" data-toggle="tab" style="font-size: 12px;">Activity</a></li>
                                <li class="nav-item"><a class="nav-link" id="tabSettings" href="#settings" data-toggle="tab" style="font-size: 12px;">Settings</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="activity">

                                </div>
                                <!-- /.tab-pane -->



                                {{-- Update profile option --}}
                                <div class="tab-pane" id="settings">
                                    <div class="col-12 row">
                                        <div class="col-12 row p-t-10">
                                            <span style="width: 110px;"> <strong>Email: </strong></span>
                                            <span>{{ $User->email }}</span>
                                        </div>
                                        <div class="col-12 row p-t-10">
                                            <span style="width: 110px;"> <strong>Phone: </strong></span>
                                            <span>{{ $User->phone }}</span>
                                        </div>
                                        <div class="col-12 row p-t-10">
                                            <span style="width: 110px;"> <strong>Gender: </strong></span>
                                            <span>{{ $User->gender }}</span>
                                        </div>
                                        <div class="col-12 row p-t-10">
                                            <span style="width: 110px;"> <strong>Date of birth: </strong></span>
                                            <span>@if($User->dob == '' || $User->dob == null || $User->dob >= \Carbon\Carbon::now()) --:--:-- @else {{ $User->dob }} @endif</span>
                                        </div>

                                        <button type="button" class="button CyanGreen_button button-sm btn-sm m-t-20 m-r-10" data-toggle="modal" data-target="#EditProfileModal">Edit Profile</button>
                                        <button type="button" class="button Blue_button button-sm btn-sm m-t-20 m-r-10" data-toggle="modal" data-target="#ChangePassword">Change Password</button>
                                        @if(Auth::guard('admin')->user()->roles->pluck('name')->first() == 'Super Admin')
                                            <button type="button" class="button Red_button button-sm btn-sm m-t-20" id="DeleteAccountBtn" data-toggle="modal" data-target="">Delete Account</button>
                                        @endif
                                    </div>

                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



    {{-- Update profile modal --}}
    <div class="modal bd-example-modal-lg fade" id="EditProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="LoginLongTitle">Edit profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form" method="Post"
                          action=" @if(Auth::guard('admin')->user()->roles->pluck('name')->first() == 'Super Admin') {{ route('admin.profile.update.super')}} @else {{ route('admin.profile.update.admin') }} @endif">
                        @csrf
                        <input type="hidden" hidden name="id" value="{{ encrypt($User->id) }}">
                        <div class="row m-b-10">
                            <div class="col-12 col-lg-6">
                                <label for="first_name" class="label col-form-label-sm">First Name:  <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $User->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="last_name" class="label col-form-label-sm">Last Name:  <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $User->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>
                        </div>

                        @if(Auth::guard('admin')->user()->roles->pluck('name')->first() == 'Super Admin')
                            <div class="row m-b-10">
                                <div class="col-lg-6 col-12">
                                    <label for="Email" class="label col-form-label-sm">Email:  <span class="text-danger">*</span></label>
                                    <input type="email" name="email" placeholder="email" id="Email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $User->email) }}" required>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-12">
                                    <label for="User_Type" class="label col-form-label-sm">User Type:  <span class="text-danger">*</span></label>
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
                        @endif

                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <label for="phone" class="label col-form-label-sm">Phone: <span class="text-danger">*</span></label>
                                <input type="number" name="phone" id="phone" placeholder="01*********" class="form-control" value="{{ old('phone', $User->phone) }}" required>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="dob" class="label col-form-label-sm">Date of birth (d-m-y): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dob') invalid @enderror" name="dob" id="dob"
                                           value="{{ old('dob', \Carbon\Carbon::parse($User->dob)->format('d-m-Y')) }}" readonly required>
                                </div>
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-b-20">
                            @if(Auth::guard('admin')->user()->roles->pluck('name')->first() == 'Super Admin')
                                <div class="col-lg-6 col-12">
                                    <label for="job_title" class="label col-form-label-sm">Job Title: <span class="text-danger">*</span></label>
                                    <input type="text" name="job_title" id="job_title" placeholder="Administrator, Teacher, etc" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title' ,$User->admin->job_title) }}" required>
                                </div>
                                @error('job_title')
                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                @enderror
                            @endif
                        </div>

                        <div class="modal-footer">
                            <div class="col-12 text-center">
                                <button type="submit" class="button Blue_button pl-4 pr-4"><i class="fa fa-edit m-r-6"></i> Update </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Change password modal --}}
    <div class="modal fade" id="ChangePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="LoginLongTitle">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form" method="Post" action="{{ route('admin.profile.update_Password') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="old_password" class="col-sm-4 col-form-label">Old Password:</label>
                            <div class="col-sm-8">
                                <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" placeholder="Old password" required minlength="8" maxlength="60">
                                <div class="text-bold" style="font-size: 12px;" id="Old_password_status">
                                    <span class="text-danger">Old passwords is required.</span>
                                </div>
                            </div>
                            @error('old_password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-4 col-form-label">New Password: </label>
                            <div class="col-sm-8">
                                <input type="password" name="password" class="form-control NewPasswordEntry @error('password') is-invalid @enderror"
                                       id="password" placeholder="New password" required minlength="8" maxlength="60"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                       title="Password must contain at least one number and one uppercase and lowercase letter, and 8 or more characters">

                                <div class="text-bold" style="font-size: 12px;" id="password_strength">
                                    <span class="text-danger">Use (A-Z a-z 0-9 @#*&?!) for strong password.</span>
                                </div>

                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror

                                <div id="message">
                                    <div>Password must contain the following:</div>
                                    <span id="letter" class="invalid">A <b>lowercase</b> letter</span><br>
                                    <span id="capital" class="invalid">A <b>capital (uppercase)</b> letter</span><br>
                                    <span id="number" class="invalid">A <b>number</b></span><br>
                                    <span id="length" class="invalid">Minimum <b>8 characters</b></span><br>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-sm-4 col-form-label">Confirm Password: </label>
                            <div class="col-sm-8">
                                <input type="password" name="password_confirmation" readonly class="form-control @error('password') is-invalid @enderror" id="password_confirmation" placeholder="Confirm password" required minlength="8" maxlength="60">
                                <div class="text-bold" style="font-size: 12px;" id="password_status"></div>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="button button-sm Blue_button" id="ChangePasswordBtnSubmit" style="display: none;">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete account confirmation --}}
    <div class="modal fade" id="ConfirmAccountDeletionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="LoginLongTitle">Confirm account deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal form" method="Post" action="{{ route('admin.profile.delete') }}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" hidden value="{{ encrypt($User->id) }}">
                        <div class="row">
                            <div class="col-12">
                                <label for="TxtConfDeletePassword">Password: </label>
                                <input type="password" name="password" id="TxtConfDeletePassword" placeholder="Type your password ..." class="form-control">
                            </div>
                        </div>
                        <div id="MatchPasswordStatus"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="button Blue_button button-sm " id="ConfirmDeleteSubmitBTN">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop


@section('page_level_script')
    {{-- Page level script --}}
    {{--<script src="{{ asset('commonAsset/js/myJS.js') }}"> /*Password Js*/ </script>--}}
    <script src="{{ asset('commonAsset/mydatepicker/bootstrap-datepicker.min.js') }}"></script>


    <script>
        let nowTemp = new Date();
        let now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        $('#dob').datepicker({
            /*beforeShowDay: function(date) {
                return date.valueOf() >= now.valueOf();
            },
            afterShowDay: function(date) {
                return date.valueOf() >= now.valueOf();
            },*/
            autoclose: true,
            format:"dd-mm-yyyy",
        });
    </script>
    <script>
        // profile picture upload ...
        $(document).ready(function(){
            $(" #BtnCamera").click(function(){
                $('#select_Profile_Pic_file').trigger("click");
            });

            $("#select_Profile_Pic_file").change(function(){
                $("#submit_profile_image_file").trigger("click");
            });

            $("#submit_profile_image_file").click(function(){
                $(this).submit();
            });
        });

        let Old_password = document.getElementById('old_password')
            , New_password = document.getElementById('password')
            , Confirm_password = document.getElementById('password_confirmation')
            , Old_pass_Status = document.getElementById('Old_password_status')
            , password_strength = document.getElementById("password_strength")
            , password_status = document.getElementById('password_status')
            , btn_Submit_Pass = document.getElementById('ChangePasswordBtnSubmit');

        let letter = document.getElementById("letter")
            , capital = document.getElementById("capital")
            , number = document.getElementById("number")
            , length = document.getElementById("length");


        let OldPass = false, NewPass = false, ConfPass = false;

        // Check if old passwords is correct ....
        Old_password.onkeyup = function() {
            let password = this.value;
            OldPass = false;
            if(password.length == 0){
                toggleChangePassBTN();
                Old_pass_Status.innerHTML = '<span class="text-danger">Old passwords is required.</span>';
            }
            else if(password.length < 8){
                toggleChangePassBTN();
                Old_pass_Status.innerHTML = '<span class="text-danger">Password should be at least 8 characters.</span>';
            }else{
                $.ajax({
                    type:'POST',
                    url:'{{ route('admin.profile.chkPassword') }}',
                    data: { "password" :  password, "_token": "{{ csrf_token() }}" },
                    success:function(data) {
                        //Old_pass_Status.innerHTML = data.status;
                        if(data.status == 'Success'){
                            OldPass = true;
                            toggleChangePassBTN();
                            Old_pass_Status.innerHTML = '<span class="text-success"><i class="fa fa-check m-r-6"></i> Success</span>';
                        }else{
                            OldPass = false;
                            toggleChangePassBTN();
                            Old_pass_Status.innerHTML = '<span class="text-danger">Password does not match.</span>';
                        }
                    }
                });
            }
        };

        //Check strong new password ....
        // When the user starts to type something inside the password field
        New_password.onkeyup = function() {
            let StrongPassword = 0;
            NewPass = false;

            // Validate lowercase letters
            let lowerCaseLetters = /[a-z]/g;
            if(New_password.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
                StrongPassword += 1;
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            let upperCaseLetters = /[A-Z]/g;
            if(New_password.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
                StrongPassword += 1;
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            let numbers = /[0-9]/g;
            if(New_password.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
                StrongPassword += 1;
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if(New_password.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
                StrongPassword += 1;
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
                NewPass = false;
            }
            if(StrongPassword >= 4){
                if(Confirm_password.hasAttribute('readonly')){
                    Confirm_password.removeAttribute('readonly');
                }
                NewPass = true;
            }else{
                if(!Confirm_password.hasAttribute('readonly')){
                    Confirm_password.setAttribute('readonly', '');
                }
                NewPass = false;
            }

            // Strength meter//
            let password = this.value;
            //TextBox left blank.
            if (password.length == 0) {
                password_strength.innerHTML = "<span class='text-danger'>Use (A-Z, a-z, 0-9, @#*&?!) for strong password.</span>";
                return;
            } else if (password.length < 8) {
                password_strength.innerHTML = "<span class='text-danger'>Minimum 8 characters.</span>";
                if(!Confirm_password.hasAttribute('readonly')){
                    Confirm_password.setAttribute('readonly', '');
                }
                return;
            }

            //Regular Expressions.
            let regex = new Array();
            regex.push("[A-Z]"); //Uppercase Alphabet.
            regex.push("[a-z]"); //Lowercase Alphabet.
            regex.push("[0-9]"); //Digit.
            regex.push("[$@$!%*#?&]"); //Special Character.

            let passed = 0;

            //Validate for each Regular Expression.
            for (let i = 0; i < regex.length; i++) {
                if (new RegExp(regex[i]).test(password)) {
                    passed++;
                }
            }
            //Validate for length of Password.
            if (passed > 2 && password.length > 8) {
                passed++;
            }

            if(New_password.value != Old_password.value){
                //Display status.
                let color = "";
                let strength = "";
                switch (passed) {
                    case 0:
                    case 1:
                        strength = "Strength: Weak";
                        color = "red";
                        NewPass = false;
                        break;
                    case 2:
                        strength = "Strength: Good";
                        color = "darkorange";
                        NewPass = false;
                        break;
                    case 3:
                    case 4:
                        strength = "Strength: Strong";
                        color = "green";
                        NewPass = true;
                        break;
                    case 5:
                        strength = "Strength: Very Strong";
                        color = "darkgreen";
                        NewPass = true;
                        break;
                }
                password_strength.innerHTML = strength;
                password_strength.style.color = color;
            }else{
                NewPass = false;
                password_strength.innerHTML = "<span class='text-danger'>Old password and new password cannot be same.</span>";
            }

            if(Confirm_password.value.length > 0){
                if(New_password.value != Confirm_password.value){
                    NewPass = false; ConfPass = false;
                    password_status.innerHTML = "<span class='text-danger'>Password confirmation does not match.</span>";
                }else{
                    NewPass = true; ConfPass = true;
                    password_status.innerHTML = "";
                }
            }

            toggleChangePassBTN();
        };

        Confirm_password.onkeyup = function(){
            ConfPass = false;
            if(Confirm_password.value.length > 0){
                if(New_password.value != Confirm_password.value){
                    password_status.innerHTML = "<span class='text-danger'>Password confirmation does not match.</span>";
                }
                else{
                    password_status.innerHTML = "";
                    ConfPass = true;
                }
            }
            toggleChangePassBTN();
        };

        function toggleChangePassBTN(){
            if(OldPass == true && NewPass == true && ConfPass == true){
                document.getElementById('ChangePasswordBtnSubmit').style.display = 'block';
            }else{
                document.getElementById('ChangePasswordBtnSubmit').style.display = 'none';
            }
            document.getElementById('ChangePasswordBtnSubmit').style.margin = 'auto';
            console.log(OldPass, NewPass, ConfPass);
        }
    </script>

    @if(session()->has('formError'))
        <script>
            $(document).ready(function () {
                $('#tabActivity').removeClass('active');
                $('#tabSettings').addClass('active');
                $('#activity').removeClass('active');
                $('#settings').addClass('active');
                $('#ChangePassword').modal('toggle');
            });
        </script>
    @endif

    @if(session()->has('formErrorUpdate'))
        <script>
            $(document).ready(function () {
                $('#tabActivity').removeClass('active');
                $('#tabSettings').addClass('active');
                $('#activity').removeClass('active');
                $('#settings').addClass('active');
                $('#EditProfileModal').modal('toggle');
            });
        </script>
    @endif

    <script>
        $(document).ready(function (){
            $('#ConfirmDeleteSubmitBTN').hide();
            $('#DeleteAccountBtn').click(function () {
                @if($AdminCount <= 1)
                    alert('You cannot delete your account. Please create a super admin first.');
                @else
                    let conf = confirm('Are you sure you want to delete your account.');
                    if(conf == true){
                        $('#ConfirmAccountDeletionModal').modal('toggle');
                    }
                @endif
            });

            $('#TxtConfDeletePassword').on('keyup', function(){
                let ConfDelPassword = this.value;
                let MatchPasswordStatus = document.getElementById('MatchPasswordStatus');

                if(ConfDelPassword.length == 0){
                    MatchPasswordStatus.innerHTML = '<span class="text-danger">Type your password.</span>';
                }
                else if(ConfDelPassword.length < 8){
                    MatchPasswordStatus.innerHTML = '<span class="text-danger">Password should be at least 8 characters.</span>';
                }else{
                    $.ajax({
                        type:'POST',
                        url:'{{ route('admin.profile.chkPassword') }}',
                        data: { "password" :  ConfDelPassword, "_token": "{{ csrf_token() }}" },
                        success:function(data) {
                            if(data.status == 'Success'){
                                MatchPasswordStatus.innerHTML = '<span class="text-success"><i class="fa fa-check m-r-6"></i> Success</span>';
                                $('#ConfirmDeleteSubmitBTN').show();
                            }else{
                                MatchPasswordStatus.innerHTML = '<span class="text-danger">Password does not match.</span>';
                                $('#ConfirmDeleteSubmitBTN').hide();
                            }
                        }
                    });
                }
            });

            @if(Auth::guard('admin')->user()->roles->pluck('name')->first() == 'Super Admin')
            document.getElementById('User_Type').onchange = function() {
                if(this.selectedIndex == 2){

                    @if($AdminCount <= 1)
                        alert('The system has only one super admin. Please create a admin first.');
                        document.getElementById('User_Type').selectedIndex = 1;
                    @else
                    if(confirm('Are you sure you want to become a Admin from a Super Admin. You will loose control over many settings.')){
                        document.getElementById('User_Type').selectedIndex = 2;
                    }else{
                        document.getElementById('User_Type').selectedIndex = 1;
                    }
                    @endif
                }
            }
            @endif

        });
    </script>

@stop
