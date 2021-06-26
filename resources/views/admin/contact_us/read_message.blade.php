@extends('layouts.admin.app_admin')

@php  $pageName = 'Contact Us'; $subPageName = 'Read Message'; @endphp

@section('admin_content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

        @include('layouts.admin.partials.contact_side_nav')

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Read Mail</h3>

                        <div class="card-tools">
                            <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i class="fas fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h5><strong>Subject: </strong>{{ $ContactDetails->subject }}</h5>
                            <h6>
                                @if($ContactDetails->sender == 0)
                                    <strong>From: </strong>{{ $ContactDetails->email }}
                                @else
                                    <strong>To: </strong>{{ $ContactDetails->email }}
                                @endif
                                <span class="mailbox-read-time float-right">{{ \Carbon\Carbon::parse($ContactDetails->created_at)->format('d.m.Y H:i a') }}</span></h6>
                        </div>
                        <!-- /.mailbox-read-info -->
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            {!! $ContactDetails->message !!}
                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.card-body -->
                    {{--<div class="card-footer bg-white">
                        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                            <li>
                                <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> Sep2014-report.pdf</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1,245 KB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                                </div>
                            </li>
                            <li>
                                <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> App Description.docx</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1,245 KB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                                </div>
                            </li>
                            <li>
                                <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo1.png</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                          <span>2.67 MB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                                </div>
                            </li>
                            <li>
                                <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i> photo2.png</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                          <span>1.9 MB</span>
                          <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                                </div>
                            </li>
                        </ul>
                    </div>--}}


                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <div class="float-right">
                            <a href="{{ route('admin.contact-us.msg.reply', ['id'=> encrypt($ContactDetails->id)]) }}" type="button" class="btn btn-default"><i class="fas fa-reply"></i> Reply</a>
                        </div>
                        <a href="{{ route('admin.contact-us.msg.delete', ['id'=> encrypt($ContactDetails->id)]) }}" type="button" class="btn btn-default" onclick="return confirm('Are you sure you want to delete this email.')">
                            <i class="far fa-trash-alt"></i> Delete</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

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
