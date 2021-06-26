@extends('layouts.admin.app_admin')

@php  $pageName = 'Contact Us'; @endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@php
    function truncate($text, $chars = 25) {
        if (strlen($text) <= $chars) {
            return $text;
        }
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
        return $text;
    }
@endphp
@section('admin_content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

            @include('layouts.admin.partials.contact_side_nav')

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $subPageName }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" title="Delete" name="bulk_delete" id="bulk_delete"><i class="far fa-trash-alt"></i></button>
                                {{--<button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>--}}
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-default btn-sm" onclick="window.location.reload()" title="Reload"><i class="fas fa-sync-alt"></i></button>
                        </div>
                        <div class="col-12 alert alert-success Success_message_message_box" style="display: none;"></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table" id="inbox">
                                <thead style="display: none">
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th></th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($Msgs) > 0)
                                    @foreach($Msgs as $Msg)
                                        <tr @if($Msg->seen != 1) style="background: rgba(255,255,100,1);" @else style="font-size: 13px!important;" @endif>
                                            <td>
                                                <div class="icheck-primary">
                                                    <input type="checkbox" class="i-checks mail_checkbox" id="check-{{ $Msg->id }}" name="mail_checkbox[]" value="{{ $Msg->id }}">
                                                    <label for="check-{{ $Msg->id }}"></label>
                                                </div>
                                            </td>
                                            <td class="mailbox-name text-dark"><a href="{{ route('admin.contact-us.read', ['id' => encrypt($Msg->id)]) }}" class="text-dark">{{ $Msg->name }}</a></td>
                                            <td class="mailbox-subject">
                                                <a href="{{ route('admin.contact-us.read', ['id' => encrypt($Msg->id)]) }}" class="text-dark">
                                                <b>{{ $Msg->subject }}</b> - {!! truncate(strip_tags($Msg->message), 40) !!}
                                                </a>
                                            </td>
                                            <td class="mailbox-date">{{ \Carbon\Carbon::now()->diffForHumans($Msg->created_at) }} @if($Msg->seen == 1 && $subPageName != 'Sent message') , seen @endif</td>
                                            <td class="mailbox-star">
                                                {{--<a href="{{ route('admin.contact-us.msg.delete', ['id'=> encrypt($Msg->id)]) }}" class="button Red_button button-sm" title="delete" onclick="return confirm('Are you sure you want to delete this email.')"><i class="fa fa-trash-alt"></i></a>--}}
                                                @if($subPageName != 'Sent message')
                                                <a href="{{ route('admin.contact-us.msg.reply', ['id'=> encrypt($Msg->id)]) }}" type="button" class="btn btn-default btn-sm" title="Reply">
                                                    <i class="fas fa-reply"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.card-body -->
                    {{--<div class="card-footer p-0">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                <i class="far fa-square"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                            <div class="float-right">
                                1-50/200
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.float-right -->
                        </div>
                    </div>--}}
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
    <script src="{{ asset('asset_backend/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('asset_backend/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        $(document).ready( function () {
            $('#inbox').DataTable({
                autoWidth: false,
                pageLength: 25,
                responsive: true,
            });


            $(document).on('click', '#bulk_delete', function () {
                let id = [];
                if(confirm('Are you sure you want to delete message.')){
                    $('.mail_checkbox:checked').each(function () {
                        id.push($(this).val());
                        if(id.length > 0){
                            $.ajax({
                                url: "{{ route('admin.contact-us.msg.delete-multiple') }}",
                                method: "GET",
                                data: {id:id},
                                success:function (data) {
                                    $('.Success_message_message_box').fadeIn(200);
                                    $('.Success_message_message_box').html(data);
                                    $('.Success_message_message_box').fadeOut(2000);
                                    window.location.href = "{{ route('admin.contact-us.inbox') }}";
                                }
                            });
                        }else{
                            alert('You did not select message to perform the delete action.');
                        }
                    });
                }
            });
        });
    </script>
@stop
