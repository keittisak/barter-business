@extends('front.layouts.main')
@section('title','Sign in') 
@section('css')
<style>
    .login-page{
        justify-content: unset;
        background:transparent;
        /* margin: 1.5rem; */
    }
    .nav{
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">เข้าสู่ระบบ</h5>
@endsection

@section('content')
<div class="login-page">
    <div class="login-box">
    <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg text-black">ลงชื่อเข้าสู่ระบบ</p>
        
            <form id="form" action="{{ route('front.users.login.process') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="">{{ __('รหัสสมาชิก') }}</label>
                    <input type="text" name="username" id="username" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">{{ __('รหัสผ่าน') }}</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="รหัสผ่าน">
                </div>
                <div class="row">
                {{-- <div class="col-8">
                    <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">
                        Remember Me
                    </label>
                    </div>
                </div> --}}
    
                <div class="col-12">
                    <button type="submit" class="btn btn-block bg-green">เข้าสู่ระบบ</button>
                </div>
    
                </div>
            </form>
        
            <div class="mb-1 mt-4">
                <a class="text-left" href="https://line.me/ti/p/3k8JgISK_L">ลืมรหัสผ่าน</a>
                {{-- <a class="float-right" href="{{ route('front.users.form') }}" class="text-center">สมัครสมาชิก</a> --}}
            </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            window.location.href = '{{ route("front.home") }}';
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
                if( key == 'status' && v == 'pending' ){
                    Swal.fire({
                        icon: 'warning',
                        title: 'ไม่สามารถเข้าสู่ระบบได้',
                        text: `รอการตรวจสอบเพื่อยืนยันการสมัครสมาชิก`,
                    })
                } else if ( key == 'status' && v == 'inactive' ){
                    Swal.fire({
                        icon: 'warning',
                        title: 'ไม่สามารถเข้าสู่ระบบได้',
                        text: `รหัสสมาชิกถูกยกเลิกการใช้งานกรุณาติดต่อ BA`,
                    })
                }else if( key == 'expired' ){
                    Swal.fire({
                        icon: 'warning',
                        title: 'ไม่สามารถเข้าสู่ระบบได้',
                        text: `รหัสสมาชิกหมดอายุเมื่อ ${v} กรุณาติดต่อเจ้าหน้าที่ Barter Advance`,
                    })
                }
            });
            loader.close();
        }
    });
</script>
@endsection