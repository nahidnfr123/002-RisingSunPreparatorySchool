
<div class="col-md-3">
    @if($subPageName === 'Compose email')
        <a href="{{ route('admin.contact-us.inbox') }}" class="btn btn-primary btn-block mb-3"> <i class="fa fa-angle-left m-r-10"></i> Return to Inbox</a>
    @else
        <a href="{{ route('admin.contact-us.compose') }}" class="btn btn-primary btn-block mb-3">Compose</a>
    @endif


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Folders</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item @if($subPageName === 'Inbox') activeMe  @endif">
                    <a href="{{ route('admin.contact-us.inbox') }}" class="nav-link">
                        <i class="fas fa-inbox"></i> Inbox
                        @if($MsgCount > 0)
                            <span class="badge bg-primary float-right">{{ $MsgCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Sent message') activeMe  @endif">
                    <a href="{{ route('admin.contact-us.sent') }}" class="nav-link">
                        <i class="far fa-envelope"></i> Sent
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Trash') activeMe  @endif">
                    <a href="{{ route('admin.contact-us.trash') }}" class="nav-link">
                        <i class="far fa-trash-alt"></i> Trash
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<!-- /.col -->
