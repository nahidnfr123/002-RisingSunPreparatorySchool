
<div class="col-md-3">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pages</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item @if($subPageName === 'Home') activeMe @endif">
                    <a href="{{ route('admin.home.settings') }}" class="nav-link">
                        <i class="fas fa-home m-r-15"></i> Home
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Post') activeMe @endif">
                    <a href="" class="nav-link">
                        <i class="fas fa-book m-r-15"></i> Post
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Event') activeMe @endif">
                    <a href="" class="nav-link">
                        <i class="fas fa-calendar-alt m-r-15"></i> Event
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Gallery') activeMe @endif">
                    <a href="" class="nav-link">
                        <i class="far fa-images m-r-15"></i> Gallery
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'Contact') activeMe @endif">
                    <a href="{{ route('admin.home.settings') }}" class="nav-link">
                        <i class="fas fa-envelope m-r-15"></i> Contact
                    </a>
                </li>
                <li class="nav-item @if($subPageName === 'About') activeMe @endif">
                    <a href="" class="nav-link">
                        <i class="far fa-circle m-r-15"></i> About
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<!-- /.col -->
