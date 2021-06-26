@extends('layouts.admin.app_admin')

@php  $pageName = 'Contact Us'; $subPageName = 'Compose email'; @endphp

@section('admin_content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

            @include('layouts.admin.partials.contact_side_nav')


            @if(!isset($Msg))
                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Compose New Message</h3>
                        </div>
                        <!-- /.card-header -->
                        <form action="{{ route('admin.contact-us.msg.sendMail') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="email"> Email:</label>
                                    <input type="email" class="form-control" placeholder="To:" name="email" id="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject: </label>
                                    <input type="text" class="form-control" placeholder="Subject:" id="subject" name="message_subject" value="{{ old('message_subject') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="compose-textarea">Message: </label>
                                    <textarea id="compose-textarea" class="form-control" style="height: 300px;" name="message_body" required>{{ old('message_body') }}</textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                                </div>
                                <button type="reset" class="btn btn-default" onclick="window.location='{{ route('admin.contact-us.inbox') }}'"><i class="fas fa-times"></i> Discard</button>
                            </div>
                        </form>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            @else
                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Reply</h3>
                        </div>
                        <!-- /.card-header -->
                        <form action="{{ route('admin.contact-us.msg.replyMail') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" hidden name="name" readonly value="{{ old('name', $Msg->name) }}"  required>
                                <input type="hidden" hidden name="id" readonly value="{{ old('id', $Msg->id) }}"  required>
                                <div class="form-group">
                                    <label for="email"> Email:</label>
                                    <input type="email" class="form-control" placeholder="To:" name="email" id="email" readonly value="{{ old('email', $Msg->email) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject: </label>
                                    <input type="text" class="form-control" placeholder="Subject:" id="subject" name="message_subject" value="{{ old('message_subject') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="compose-textarea">Message: </label>
                                    <textarea id="compose-textarea" class="form-control" style="height: 300px;" name="message_body" required>{{ old('message_body') }}</textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                                </div>
                                <button type="reset" class="btn btn-default" onclick="window.location('{{ route("admin.contact-us.read", ["id" => encrypt($Msg->id)]) }}')"><i class="fas fa-times"></i> Discard</button>
                            </div>
                        </form>

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
            @endif

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

@stop


@section('page_level_script')
    <script>
        $(function () {
            //Add text editor
            $('#compose-textarea').summernote()
        })
    </script>
@stop
