<!-- jQuery -->
{{--<script src="{{ asset('asset_backend/plugins/jquery/jquery.min.js') }}"></script>--}}
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('asset_backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('asset_backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Wow js -->
<script src="{{ asset('commonAsset/wow.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('asset_backend/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('asset_backend/plugins/toastr/toastr.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('asset_backend/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('asset_backend/plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('asset_backend/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('asset_backend/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('asset_backend/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('asset_backend/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('asset_backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('asset_backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('asset_backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('asset_backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('asset_backend/dist/js/adminlte.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('asset_backend/dist/js/demo.js') }}"></script>


<script src="{{ asset('asset_gallery/js/bootstrap-datepicker.min.js') }}"></script>


{{--<script type="text/javascript" src="{{ asset('commonAsset/js/kronos-date-picker.js') }}"></script>--}}

{{-- Show Profile manu --}}
<script>
    $('#Profile_link').click(function (e) {
        $('#Profile_Menu').toggleClass('show');
        $('#Profile_link').toggleClass('clicked');
        e.preventDefault();
    });
</script>



<script src="{{ asset('asset_gallery/js/popper.min.js') }}"></script>
