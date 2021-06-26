<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light elevation-1">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link"> Dashboard </a>
        </li>
        {{--<li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>--}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                @if($MsgCount > 0)
                    <span class="badge badge-danger navbar-badge">{{ $MsgCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @if(count($Msgsss) > 0)
                    @foreach($Msgsss as $Ms)
                        <a href="{{ route('admin.contact-us.read', ['id' => encrypt($Ms->id)]) }}" class="dropdown-item text-bold" @if($Ms->seen != 1) style="background: rgba(255,255,100,1);" @endif>
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title text-bold">
                                        {{$Ms->name}}
                                    </h3>
                                    <p class="text-sm">{{ $Ms->subject }}</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::now()->diffForHumans($Ms->created_at) }}
                                        @if($Ms->seen == 1) , seen @endif
                                    </p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                     @endforeach

                    <a href="{{ route('admin.contact-us.inbox') }}" class="dropdown-item dropdown-footer">See All Messages</a>
                @endif
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        {{--<li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>--}}
        {{--<li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fas fa-th-large"></i>
            </a>
        </li>--}}
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('top-logout-form').submit();">
                <strong><i class="fas fa-sign-out-alt text-left"></i> {{ __('Logout') }}</strong>
            </a>
            <form id="top-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
