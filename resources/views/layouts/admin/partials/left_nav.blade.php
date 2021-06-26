<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link elevation-3">
        <img src="{{ asset('asset_backend/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3">
            <div class="d-flex" id="Profile_link">
                <div class="image">
                    <img src="/{{ auth()->user()->avatar }}" class="img-circle elevation-2" style="height: 36px;width: 36px;object-fit: cover;object-position: top center;" alt="">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->first_name.' '.auth()->user()->last_name }}</a>
                </div>
            </div>

            <div id="Profile_Menu">
                <ul>
                    <li><a href="{{ route('admin.profile') }}"><i class="fas fa-user text-left"></i> Profile</a></li>
                    <li><hr class="bg-white" style="margin: 5px 0;"></li>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt text-left"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item @if($pageName == 'Dashboard') menu-open @endif">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @if($pageName == 'Dashboard') active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if(auth()->user()->roles()->pluck('name')->first() == 'Super Admin')
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link @if($pageName == 'Users') active @endif">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Users
                            {{--<span class="right badge badge-danger">New</span>--}}
                        </p>
                    </a>
                </li>
                @endif
                {{--<li class="nav-item has-treeview">
                    <a href="{{ route('admin.post') }}" class="nav-link @if($pageName === 'Post') active @endif">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Posts
                        </p>
                    </a>
                </li>--}}
                <li class="nav-item has-treeview @if($pageName == 'Post') menu-open @endif">
                    <a href="#" class="nav-link @if($pageName == 'Post') active @endif">
                        <i class="nav-icon  fas fa-book"></i>
                        <p>Post <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview m-l-15">
                        <li class="nav-item">
                            <a href="{{ route('admin.post') }}" class="nav-link  @if($subPageName == 'My posts') active @endif">
                                <i class="far fa-circle nav-icon text-info"></i><p>My Post</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.post.all') }}" class="nav-link @if($subPageName == 'All posts') active @endif">
                                <i class="far fa-circle nav-icon text-info"></i><p>All Post</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.events') }}" class="nav-link @if($pageName == 'Events') active @endif">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Events
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.gallery') }}" class="nav-link @if($pageName == 'Gallery') active @endif">
                        <i class="nav-icon fas fa-images"></i>
                        <p>
                            Gallery
                        </p>
                    </a>
                </li>
                {{--<li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon  fas fa-book"></i>
                        <p>
                            Pages
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/icons.html" class="nav-link">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>About</p>
                            </a>
                        </li>
                    </ul>
                </li>--}}
                <li class="nav-item has-treeview @if($pageName == 'Contact Us') menu-open @endif">
                    <a href="#" class="nav-link @if($pageName == 'Contact Us') active @endif">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Contact Us
                            <i class="fas fa-angle-left right"></i>
                            @if($MsgCount > 0)
                                <span class="badge badge-info right">{{ $MsgCount }}</span>
                            @endif
                        </p>
                    </a>
                    <ul class="nav nav-treeview m-l-15">
                        <li class="nav-item">
                            <a href="{{ route('admin.contact-us.inbox') }}" class="nav-link @if($subPageName == 'Inbox') active @endif">
                                <i class="fas fa-inbox nav-icon text-info"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact-us.compose') }}" class="nav-link @if($subPageName == 'Compose email') active @endif">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Compose Email</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact-us.details') }}" class="nav-link @if($subPageName == 'Settings') active @endif">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.about') }}" class="nav-link @if($pageName == 'About') active @endif">
                        <i class="nav-icon fa fa-circle"></i>
                        <p>
                            About
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.home.settings') }}" class="nav-link @if($pageName == 'Page Settings') active @endif">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Page Settings
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
