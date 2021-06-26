@extends('layouts.admin.app_admin')

@php  $pageName = 'Contact Us'; $subPageName = 'Settings'; @endphp

@section('admin_content')

    <div class="container">
        <div class="row p-b-40">


            @if($CD != null)
                <div class="card col-12 col-md-8 col-sm-12 col-lg-6 col-xl-5 m-auto">
                    <form action="{{ route('admin.contact-us.details-update') }}" method="post">
                        @csrf
                        <input type="hidden" hidden name="id" value="{{ $CD->id }}" readonly>
                        <div class="card-header">
                            <h4 class="text-muted">Contact Info</h4>
                        </div>
                        <div class="card-body">

                            <div class="form-row m-b-10">
                                <label for="email">Email: </label>
                                <input type="email" name="email" id="email" class="form-control" required readonly value="{{ old('email', $CD->email ) }}">
                            </div>

                            <div class="form-row m-b-10">
                                <label for="phone">Phone no: </label>
                                <input type="text" name="phone" id="phone" class="form-control" required readonly value="{{ old('phone', $CD->phone) }}" minlength="11" maxlength="11">
                            </div>

                            <div class="form-row m-b-10">
                                <label for="city" class="label">City: </label>
                                <input type="text" name="city" id="city" class="form-control" required readonly value="{{ old('city', $CD->city) }}">
                            </div>

                            <div class="form-row">
                                <label for="address" class="label">Address: </label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-control" required readonly>{{ old('address', $CD->address) }}</textarea>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="button" class="button Blue_button button-sm" id="EditBtn">Edit</button>
                                <div class="btn-group-sm" id="GroupBtn" style="display: none;">
                                    <button type="submit" class="button Blue_button button-sm" id="UpdateBtn">Update</button>
                                    <button type="button" class="button Red_button button-sm" id="CancelBtn">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card col-12 col-md-8 col-sm-12 col-lg-6 col-xl-5 m-auto">
                    <form action="{{ route('admin.contact-us.details-add') }}" method="post">
                        @csrf
                        <div class="card-header">
                            <h4 class="text-muted">Contact Info</h4>
                        </div>
                        <div class="card-body">

                            <div class="form-row m-b-10">
                                <label for="email">Email: </label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="form-row m-b-10">
                                <label for="phone">Phone no: </label>
                                <input type="text" name="phone" id="phone" class="form-control" required minlength="11" maxlength="11">
                            </div>

                            <div class="form-row m-b-10">
                                <label for="city" class="label">City: </label>
                                <input type="text" name="city" id="city" class="form-control" required>
                            </div>

                            <div class="form-row">
                                <label for="address" class="label">Address: </label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-control" required></textarea>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="button Blue_button button-sm">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>

@stop


@section('page_level_script')

    <script>
        $(document).ready(function () {
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
                $('#email').attr('readonly', '');
                $('#phone').attr('readonly', '');
                $('#city').attr('readonly', '');
                $('#address').attr('readonly', '');
            }

            function notReadOnly() {
                $('#email').removeAttr('readonly');
                $('#phone').removeAttr('readonly');
                $('#city').removeAttr('readonly');
                $('#address').removeAttr('readonly');
            }
        });
    </script>

@stop
