@extends('layouts.admin.app_admin')

@php  $pageName = 'Dashboard'  @endphp

@section('admin_content')
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ count($VC) }}</h3>

                            <p>Total Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ count($VCT) }}</h3>

                            <p>Visitors Today</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>


            <div class="row">
                <section class="col-lg-12 connectedSortable">

                    <div class="card bg-gradient-blue">
                        <div class="card-header border-0">

                            <h3 class="card-title">
                                <i class="far fa-calendar-alt"></i>
                                Calendar
                            </h3>
                            <div class="card-tools">
                                {{--<div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-bars"></i></button>
                                    <div class="dropdown-menu float-right" role="menu">
                                        <a href="#" class="dropdown-item">Add new event</a>
                                        <a href="#" class="dropdown-item">Clear events</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">View calendar</a>
                                    </div>
                                </div>--}}
                                <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="calendar" style="width: 100%"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@stop


@section('page_level_script')
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('asset_backend/dist/js/pages/dashboard.js') }}"></script>
    {{-- Page level script --}}
    {{--
    <script>
        $('#Profile_link').click(function (e) {
            $('#Profile_Menu').toggleClass('show');
            $('#Profile_link').toggleClass('clicked');
            e.preventDefault();
        });
    </script>
    --}}

@stop
