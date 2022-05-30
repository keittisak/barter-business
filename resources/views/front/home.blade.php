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
  <title>Barter Advance</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt&display=swap">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{date('his')}}">

  <link href="{{ asset('assets/plugins/iconnic/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
</head>
<style>
    .logo {
        font-size: 2.1rem;
        font-weight: 300;
        margin-bottom: .9rem;
        text-align: center;
    }
    .language{
        position: absolute;
        /* padding: 1.6rem 1rem; */
        text-align: right;
        width: 100%;
        /* padding: 0.313rem */
        padding-top:10px
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
        @media screen and  (max-width:351px){
            .btn-home-menu i {
                font-size: 26px !important
            }
            .btn-home-menu h6{
                font-size: 18px
            }
            .btn-circle.btn-lg {
                width: 40px;
                height:40px
            }
            .btn-circle {
                font-size: 7px
            }
        }
        @media screen and  (max-width:223px){
            .btn-home-menu i {
                font-size: 24px !important
            }
            .btn-home-menu h6{
                font-size: 16px !important
            }
            .tex-contact-email{
                font-size: 15px
            }
        }
</style>
<body class="layout-top-nav font-prompt">
    <div class="wrapper">
        <div class="content-wrapper bg-white">
            <div class="language">
                {{-- <button class="btn btn-default btn-circle btn-img"><img src="{{ asset('assets/images/thailand.svg') }}" alt="th"></button> --}}
                @auth
                <nav class="main-header navbar nav-top float-right">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-nav-menu bg-green mr-0" data-toggle="dropdown" href="#" aria-expanded="false">เมนู <i class="fas fa-bars"></i></a>
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
                            @if(Auth::user()->isAdmin())
                            <div class="dropdown-divider text-orange"></div>
                            <a href="{{ route('front.auctions.winner-by-user') }}" class="dropdown-item {{Request::is('users/reports/income')? "active" : ""}}">
                                <i class="fas fa-gavel mr-2 text-muted"></i> รายการผู้ชนะการประมูล
                            </a>
                            @endif
                            {{-- <div class="dropdown-divider text-orange"></div>
                            <a href="{{ route('front.billing.index') }}" class="dropdown-item {{Request::is('billing')? "active" : ""}}">
                                <i class="fas fa-file-invoice-dollar mr-2 text-muted"></i> ค่าธรรมเนียม @if($countBilling) <span class="badge badge-danger badge-custom blink_me bg-red">{{$countBilling}}</span> @endif
                            </a> --}}
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('front.recommended-members') }}" class="dropdown-item">
                                <i class="fas fa-user-friends mr-2 text-muted"></i> สมาชิกที่ได้แนะนำ
                            </a>
                            {{-- <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item copyboard-url" data-url="{{ route('front.users.form',['uId'=>Auth::user()->id]) }}">
                                <i class="fas fa-link mr-2 text-muted"></i> ลิ้งค์แนะนำสมัครสมาชิก
                            </a> --}}
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
                @endauth
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 col-12">


                            {{-- @auth
                            <div class="row pt-5 mt-3">
                                <div class="col-12 text-center">
                                    <img src="{{ asset('assets/images/'.env('LOGO_BRANDER')) }}" alt="Barter Advance" class="w-100 h-auto mb-4">
                                    <h6 class="text-dark">ยินดีต้อนรับสู่&nbsp;<span class="text-green font-weight-bold">BA</span></h6>
                                    <h6 class="text-dark">แลกเปลี่ยนสินค้าและบริการ</h6>
                                </div>
                            </div>
                            @endauth --}}



                            <div class="row pt-5 mt-3">
                                <div class="col-12 text-center">
                                    <aside class="">
                                        <article class="gallery-wrap"> 
                                            
                                            <div id="carouselIndicators" class="carousel slide  w-100 carousel-store" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach ($branners as $key => $item)
                                                        @if( !empty($item->image) )
                                                            <div class="carousel-item {{ ($key==0) ? 'active':'' }}">
                                                                <div class="img-big-wrap">
                                                                    <a><img src="{{$item->image}}"></a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <ol class="carousel-indicators" style="position: initial">
                                                    @foreach($branners as $key=>$item)
                                                        @if( !empty($item->image) )
                                                            <li data-target="#carouselIndicators" data-slide-to="{{$key}}" class="{{ ($key==0) ? 'active':'' }}"></li>
                                                        @endif
                                                    @endforeach
                                                </ol>
                                            </div>
                                        </article>
                                    </aside>
                                    <h3 class="text-green font-weight-bold">Barter Advance</h3>
                                    <h6 class="text-dark">ยินดีต้อนรับสู่&nbsp;<span class="text-green font-weight-bold">BA</span></h6>
                                    <h6 class="text-dark">แพลตฟอร์มแลกเปลี่ยนสินค้าและบริการ</h6>
                                </div>
                            </div>



                            <div class="row pt-3 home-menu">
                                <div class="col-6">
                                    <div class="info-box bg-green btn-home-menu">
                                        <div class="info-box-content text-center">
                                            <i class="fas fa-store icon-size-r32"></i>
                                            <h6 class="h5 mt-1 mb-0">ร้านค้าและบริการ</h6>
                                        </div>
                                    </div>
                                    <a href="{{ route('front.shops.category') }}" class="stretched-link"></a>
                                </div>
                                
                                @guest
                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fas fa-balance-scale icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">กฎและข้อบังคับ</h6>
                                            </div>
                                        </div>
                                        <a href="{{route('front.branch.about')}}" class="stretched-link"></a>
                                    </div>
                                @endguest

                                @auth

                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fas fa-gavel icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">ประมูลสินค้า</h6>
                                            </div>
                                        </div>
                                        <a href="{{route('front.auctions.index')}}" class="stretched-link"></a>
                                    </div>

                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fa fa-user icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">โปรไฟล์</h6>
                                            </div>
                                        </div>
                                        <a href="{{ route('front.users.profile') }}" class="stretched-link"></a>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fas fa-sync-alt icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">โอนเทรดบาท</h6>
                                            </div>
                                        </div>
                                        <a href="{{ route('front.users.trade.form') }}" class="stretched-link"></a>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fas fa-file-invoice-dollar icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">ค่าธรรมเนียม
                                                    @php
                                                        $countBilling = Auth::user()->countBillingUnpaid();
                                                    @endphp
                                                    @if($countBilling > 0) <span class="badge badge-danger blink_me bg-red pt-1">{{$countBilling}}</span> @endif
                                                </h6>
                                            </div>
                                        </div>
                                        <a href="{{ route('front.billing.index') }}" class="stretched-link"></a>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-box bg-green btn-home-menu">
                                            <div class="info-box-content text-center">
                                                <i class="fas fa-link icon-size-r32"></i>
                                                <h6 class="h5 mt-1 mb-0">ลิ้งค์แนะนำสมัครสมาชิก</h6>
                                            </div>
                                        <a href="{{ route('front.users.trade.form') }}" class="stretched-link copyboard-url" data-url="{{ route('front.users.form',['uId'=>Auth::user()->id]) }}"></a>
                                    </div>
                                @endauth
                                @guest
                                <div class="col-6">
                                    <div class="info-box bg-green btn-home-menu">
                                        <div class="info-box-content text-center">
                                            <i class="fas fa-user-plus icon-size-r32"></i>
                                            <h6 class="h5 mt-1 mb-0">สมัครสมาชิก</h6>
                                        </div>
                                    </div>
                                    <a href="{{ route('front.users.form') }}" class="stretched-link"></a>
                                </div>
                                <div class="col-6">
                                    <div class="info-box bg-green btn-home-menu">
                                        <div class="info-box-content text-center">
                                            <i class="fas fa-sign-in-alt icon-size-r32"></i>
                                            <h6 class="h5 mt-1 mb-0">เข้าสู่ระบบ</h6>
                                        </div>
                                    </div>
                                    <a href="{{ route('front.users.login.form') }}" class="stretched-link"></a>
                                </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            {{-- <h6 class="mb-1">ช่องทางติดต่อ</h6> --}}
                            {{-- @php
                                $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
                            @endphp --}}
                            {{-- <a href="https://web.facebook.com/BarterAdvance8" target="_blank" class="btn btn-lg btn-circle bg-green mx-2 mt-2"><i class="fab fa-facebook-f"></i></a> --}}
                            {{-- <a href="https://line.me/ti/p/3k8JgISK_L" target="_blank" class="btn btn-lg btn-circle bg-green mx-2 mt-2"><i class="fab fa-line"></i></a> --}}
                            {{-- <a href="#" class="btn btn-lg btn-circle bg-green mx-2 mt-2"><i class="fab fa-weixin"></i></a> --}}
                            {{-- <a href="#" class="btn btn-lg btn-circle bg-green mx-2 mt-2"><i class="fab fa-telegram-plane"></i></a> --}}
                            
                        </div>
                        
                        <div class="row pt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    {{-- <h6>ติดต่อเรา</h6> --}}
                                    <p class="pb-0 mb-0 text-green text-r18 text-bold">บาร์เทอร์ แอดวานซ์</p>
                                    {{-- <p class="text-dark mb-1">55/160 ซอยลาดพร้าว 88 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหนคร 10310</p> --}}
                                    {{-- <div class="h6 d-block">
                                        <a href="tel:0801471385" class="text-dark">โทร : 093-4381629</a>
                                    </div> --}}
                                    <div class="h6 d-block">
                                        <a class="text-dark tex-contact-email">อีเมล : barteradvance@gmail.com</a>
                                    </div>
                                </div>
                                {{-- <div class="text-center mt-3">
                                    <a href="#" class="btn btn-icon rounded-circle bg-white border-dark"> <i class="fab fa-facebook-f"></i> </a>
                                    <a href="#" class="btn btn-icon rounded-circle bg-white border-dark"> <i class="fab fa-line"></i> </a>
                                    <a href="#" class="btn btn-icon rounded-circle bg-white border-dark"> <i class="far fa-comments"></i> </a>
                                </div> --}}
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- ./wrapper -->
</body>
<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>

<link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
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
</html>
