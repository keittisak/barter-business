@extends('admin.layouts.main')
@section('title','Membership request create') 
@section('css')
<style>
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>เพิ่มคำขอสมัครสมาชิก</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <form id="form" action="{{route('membership-requests.create')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="recommended_by"><span class="text-red">*</span> {{ __('รหัสผู้แนะนำ') }}</label>
                                <input type="number" name="recommended_by" id="recommended_by" class="form-control" value="{{ !empty($user->id) ? $user->id : '' }}">
                            </div>
                            <div class="form-group">
                                <label for=""><span class="text-red">*</span> {{ __('อีเมล') }}</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone"><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</label>
                                <input type="number" name="phone" id="phone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="first_name"><span class="text-red">*</span> {{ __('ชื่อ') }}</label>
                                <input type="text" name="first_name" id="first_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_name"><span class="text-red">*</span> {{ __('นามสกุล') }}</label>
                                <input type="text" name="last_name" id="last_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="id_card_number">{{ __('รหัสบัตรประชาชน/Passport (ไม่บังคับ)') }}</label>
                                <input type="text" name="id_card_number" id="id_card_number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-success bg-green">บันทึกข้อมูล</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-default">ยกเลิก</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($user->id))
    <div class="modal fade edit-password-modal" id="edit-password-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">แก้ไขรหัสผ่าน</h4>
                </div>
                <div class="modal-body">
                    <form id="edit-password-form" action="{{ route('users.update-password', $user->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> {{ __('รหัสผ่านใหม่') }}</label>
                            <input type="password" name="password" id="password"  class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> {{ __('ยืนยันรหัสผ่านใหม่') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"  class="form-control">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">ตกลง</button>
                            <a href="{{ route('membership-requests.index') }}" class="btn btn-default" id='btn-colse-edit-password-modal'>ปิด</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
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
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                window.location.href = '{{ route("membership-requests.index") }}';
            });
            
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            loader.close();
        }
    });
</script>
@endsection
