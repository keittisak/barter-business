<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="{{ asset('assets/images/'.env('LOGO_ICON')) }}"/>
    <title>Barter Advance</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit&display=swap">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin_custom.css') }}?v={{date('his')}}">
</head>
<style>
    body{
    font-family: 'Kanit', 'Athiti', sans-serif !important;
    font-size: 0.9375rem;
    font-weight: 400;
}
</style>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#" class="text-bold">Admin<span class="text-green">BA</span></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">ลงชื่อเข้าสู่ระบบ</p>
                <form id="form" action="{{ route('login.process') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="username" id="username" class="form-control" placeholder="อีเมล์">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="รหัสผ่าน">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success btn-block">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-form/jquery.form.js') }}"></script>
<script>
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
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            window.location.href = '{{ route("home") }}';
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    console.log($(`#${key}`).parent('.input-group'))
                    $(`#${key}`).parent('.input-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            loader.close();
        }
    });
</script>
</html>
