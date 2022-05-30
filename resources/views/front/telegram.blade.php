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
        @media only screen and  (max-width:351px){
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
        @media only screen and  (max-width:223px){
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
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 col-12">
                            
 
                            <div class="row pt-5 mt-3 mb-4">
                                <div class="col-12 text-center">
                                    <img src="{{ asset('assets/images/logo_v3.jpg') }}" alt="Barter Advance" class="h-auto" style="width: 30%">
                                    <h4 class="text-dark">ขั้นตอนการเข้ากลุ่มซื้อ-ขาย Barter Advance</h4>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col text-center">
                                    <h5 class="mb-3">1. โหลดโปรแกรม Telegram</h5>
                                    <img src="{{ asset('assets/images/telegram.png') }}" class="h-auto" style="width: 40%">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 text-center">
                                    <img src="{{ asset('assets/images/telegram_1.png') }}" class="mb-2" style="height: 300px;">
                                    <a href="https://telegram.org/android"><u>https://telegram.org/android</u></a>
                                </div>
                                <div class="col-md-6 mb-4 text-center">
                                    <img src="{{ asset('assets/images/telegram_2.png') }}" class="mb-2" style="height: 300px;">
                                    <a href="https://telegram.org/dl/ios"><u>https://telegram.org/dl/ios</u></a>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col text-center">
                                    <h5 class="mb-3">2. เพิ่มเพื่อน Admin ใน Telegram โดยการบันทึกเบอร์โทรศัพท์ 0930140288 ลงเครื่อง</h5>
                                    <h5>3. ทัก Admin ใน Telegram เพื่อให้ Admin ดึงเข้ากลุ่มซื้อ-ขาย Barter Advance</h5>
                                </div>
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
