<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="{{ asset('assets/images/logo_v3_60.jpg') }}"/>
    <title>BA • @yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt&display=swap"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit&display=swap">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{date('his')}}">
    <link href="{{ asset('assets/plugins/iconnic/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}" rel="stylesheet"> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .main-header {
            position: fixed;
            top: 0;
            width: 100%;
        }
        .content {
            padding-top: 94px !important;
        }
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
        .badge-custom{
            position: absolute;
            margin-left: 10px;
        }
    </style>
    @yield('css')
</head>
<body class="layout-top-nav layout-footer-fixed font-prompt">
    <div class="wrapper">
        <nav class="main-header navbar nav-top bg-green pt-4">
            @yield('nav_header')

            <div class="dropdown">
                <a class="btn-header btn-nav-menu" data-toggle="dropdown" href="#" aria-expanded="false"><i class="fas fa-bars"></i></a>
                <div class="dropdown-menu dropdown-menu-sm-right dropdown-menu-right border-0">
                    {{-- <a href="{{ route('front.home') }}" class="dropdown-item">
                        <img src="{{ asset('assets/images/'.env('LOGO_IMAGE')) }}" class="fa logo-home" alt="Barter Advance">
                        หน้าแรก
                    </a>
                    <div class="dropdown-divider"></div> --}}
                    @auth 
                    <a href="{{ route('front.users.reports.bbg') }}" class="dropdown-item {{Request::is('users/reports/bbg')? "active" : ""}}">
                        <i class="fas fa-list-alt mr-2 text-muted"></i> รายงาน BA
                    </a>
                    <div class="dropdown-divider"></div>
                    @endauth
                    <a href="{{ route('front.shops.category') }}" class="dropdown-item {{Request::is('category')? "active" : ""}}">
                        <i class="icon fas fa-store mr-2 text-muted"></i> ร้านค้าและบริการ
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    @auth
                    <a href="{{ route('front.auctions.index') }}" class="dropdown-item">
                        <i class="fas fa-gavel mr-2 text-muted"></i> ประมูลสินค้า
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('front.users.reports.purchase') }}" class="dropdown-item {{Request::is('users/reports/purchase')? "active" : ""}}">
                        <i class="fas fa-list-alt mr-2 text-muted"></i> รายงานการซื้อ
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    <a href="{{ route('front.users.reports.sales') }}" class="dropdown-item {{Request::is('users/reports/sales')? "active" : ""}}">
                        <i class="fas fa-list-alt mr-2 text-muted"></i> รายงานการขาย
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    <a href="{{ route('front.users.reports.income') }}" class="dropdown-item {{Request::is('users/reports/income')? "active" : ""}}">
                        <i class="fas fa-list-alt mr-2 text-muted"></i> วงเงินเครดิต
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    <a href="{{ route('front.auctions.winner-by-user') }}" class="dropdown-item {{Request::is('users/reports/income')? "active" : ""}}">
                        <i class="fas fa-gavel mr-2 text-muted"></i> รายการผู้ชนะการประมูล
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    @php
                        $countBilling = Auth::user()->countBillingUnpaid();
                    @endphp
                    <a href="{{ route('front.billing.index') }}" class="dropdown-item @if($countBilling) @endif {{Request::is('billing')? "active" : ""}}">
                        <i class="fas fa-file-invoice-dollar mr-2 text-muted"></i> ค่าธรรมเนียม @if($countBilling) <span class="badge badge-danger badge-custom blink_me bg-red">{{$countBilling}}</span> @endif
                    </a>
                    <div class="dropdown-divider"></div>
                    {{-- <a href="{{ route('front.users.profile') }}" class="dropdown-item {{Request::is('users/profile')? "active" : ""}}">
                        <i class="icon fa fa-user mr-2 text-muted"></i> โปรไฟล์
                    </a>
                    <div class="dropdown-divider"></div> --}}
                    <a href="{{ route('front.recommended-members') }}" class="dropdown-item">
                        <i class="fas fa-user-friends mr-2 text-muted"></i> สมาชิกที่ได้แนะนำ
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item copyboard-url" data-url="{{ route('front.users.form',['uId'=>Auth::user()->id]) }}">
                        <i class="fas fa-link mr-2 text-muted"></i> ลิ้งค์แนะนำสมัครสมาชิก
                    </a>
                    <div class="dropdown-divider text-orange"></div>
                    <a href="{{ route('front.users.logout.process') }}" class="dropdown-item">
                        <i class="icon fas fa-sign-out-alt mr-2 text-muted"></i> ออกจากระบบ
                    </a>
                    @endauth
                    @guest
                    <a href="{{ route('front.users.form') }}" class="dropdown-item {{Request::is('users/sign_up')? "active" : ""}}">
                        <i class="fas fa-user-plus mr-2 text-muted"></i> สมัครสมาชิก
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('front.users.login.form') }}" class="dropdown-item {{Request::is('users/sign_in')? "active" : ""}}">
                        <i class="fas fa-sign-in-alt mr-2 text-muted"></i> เข้าสู่ระบบ
                    </a>
                @endguest
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>

    {{-- <button type="button" class="btn bg-green btn-circle btn-sm btn-totop"><i class="fas fa-angle-up text-white"></i></button> --}}
    @include('front.layouts.nav_footer')
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
<script>
    jQuery(document).ready(function($){
        $('.btn-totop').hide();
        $(window).scroll(function(){
            if ($(this).scrollTop() > 10) {
                // $('.btn-totop').show().fadeIn();
                $('.main-header').addClass('box-shadow');
            } else {
                $('.main-header').removeClass('box-shadow');
                // $('.btn-totop').fadeOut().hide();
            }
        });
        $('.btn-totop').click(function(){
            $('html, body').animate({scrollTop : 0},360);
            return false;
        });
    });
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
    $('.select2').select2({
      theme: 'bootstrap4'
    })

    $('.copyboard-url').on('click', function(e) {
        e.preventDefault();
        var copyText = $(this).data('url');
        var textarea = document.createElement("textarea");
        textarea.textContent = copyText;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy"); 

        document.body.removeChild(textarea);
        Swal.fire({
            icon: 'success',
            title: 'คัดลอกลิ้งค์',
        })
    })
</script>
@yield('javascript')
</html>
