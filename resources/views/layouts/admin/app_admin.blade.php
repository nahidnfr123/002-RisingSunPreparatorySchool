<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.admin.partials.head')
@if(!isset($subPageName)) {{ $subPageName = '' }} @endif

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<!-- Page Wrapper -->
<div class="wrapper">

    @include('layouts.admin.partials.topbar')

    @include('layouts.admin.partials.left_nav')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4>@if($subPageName != ''){{ $pageName .' / '. $subPageName }}@else {{ $pageName }} @endif</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                {{--<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>--}}
                                <li class="breadcrumb-item active">{{ $pageName }}</li>
                                @if($subPageName != '')
                                    <li class="breadcrumb-item active">{{ $subPageName }}</li>
                                @endif
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>


            {{-- Dynamic Web Content --}}
            @yield('admin_content')

        </div>
        <!-- /.content-wrapper -->



    @include('layouts.admin.partials.footer')

    @include('layouts.admin.partials.right_nav')

</div>
<!-- ./wrapper -->

@include('layouts.admin.partials.script')

@yield('page_level_script')

@component('components.popupMsg') @endcomponent

</body>
</html>
