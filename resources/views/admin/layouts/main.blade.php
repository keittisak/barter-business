<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="{{ asset('assets/images/'.env('LOGO_ICON')) }}"/>
    <title>AdminBA • @yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt&display=swap"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit&display=swap">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin_custom.css') }}?v={{date('his')}}">

    <link href="{{ asset('assets/plugins/iconnic/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
      <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
</head>
@yield('css')
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('admin.layouts.nav_top')
        @include('admin.layouts.slide_bar')
        <div class="content-wrapper pb-5">
            @yield('content')
        </div>
        @include('admin.layouts.footer')
    </div>
</body>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-form/jquery.form.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/locales/bootstrap-datepicker.th.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $('.select2').select2({
      theme: 'bootstrap4'
    })
    loader = {
        init: function(e){
            let loading = `
            <div class="qt-block-ui"></div>
        `;
        $('body').append(loading)
        },
        close: function(e){
            $('.qt-block-ui').remove();
        }
    }

    function pricceFormat(text) {
        return text.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }
    utilities = {
        numberFormat:function(n,digit=2){
            if (n === '') {
                return '';
            }
            else if (n == 0 || isNaN(n) || n == null || n == undefined) {
                return (digit == undefined) ? '0' : parseFloat('0').toFixed(digit);
            }

            if (digit == undefined) {
                return (parseFloat(n) + '').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
            return parseFloat(n).toFixed(digit).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        },
    }
</script>
@yield('javascript')
</html>
