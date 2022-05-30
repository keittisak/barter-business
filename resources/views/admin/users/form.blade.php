@extends('admin.layouts.main')
@section('title','Users') 
@section('css')
<style>
    .dataTables_filter {
        display: none;
    }
    #DataTables_Table_0_filter {
        display: none;
    }
    #approve-modal .table td {
        border-top: none;
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>เพิ่มผู้ใช้งาน</h1>
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
                        <form id="user-form" action="{{ !isset($user->id) ? route('users.create') : route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(isset($user->id))
                            <input type="hidden" name="_method" value="PUT">
                            @endif
                            <div class="form-group text-center">
                                <div class="avatars rounded-circle avatars-xl d-block my-0 mx-auto">
                                    <div class="avatars-one" style="background-image: url({{ empty($user->image) ? asset('assets/images/img_profile_default.jpg') : $user->image }}"></div>
                                </div>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                <button type="button" class="btn btn-default btn-sm mt-2" id="btn-upload-image">เลือกรูป</button>
                                @if(isset($user->id))
                                    <button type="button" class="btn btn-default btn-sm mt-2" id="btn-edit-password">แก้ไขรหัสผ่าน</button>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email"><span class="text-red">*</span> {{ __('อีเมล') }}</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <label for="phone"><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</label>
                                <input type="number" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                            </div>

                            @if(!isset($user->id))
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> {{ __('รหัสผ่าน') }}</label>
                                    <input type="password" name="password" id="password"  class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation"><span class="text-red">*</span> {{ __('ยืนยันรหัสผ่าน') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"  class="form-control">
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="first_name"><span class="text-red">*</span> {{ __('ชื่อ') }}</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}">
                            </div>
                            <div class="form-group">
                                <label for="last_name"><span class="text-red">*</span> {{ __('นามสกุล') }}</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="expired_at"><span class="text-red">*</span> {{ __('วันที่หมดอายุสมาชิก') }}</label>
                                <input type="text" name="expired_at" id="expired_at" class="form-control" value="{{ date('d/m/Y',strtotime($user->expired_at)) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="roles-admin"><span class="text-red">*</span> {{ __('ประเภท') }}</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="type-standard" value="1" @if($user->type == 1) checked @endif>
                                            <label class="form-check-label" for="type-standard">Standard</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="type-bussines" value="2" @if($user->type == 2) checked @endif>
                                            <label class="form-check-label" for="type-bussines">Bussines</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="display-trade-fee" @if($user->type != 2) style="display:none;" @endif>
                                <div class="form-group">
                                    <label for="purchase_fee"><span class="text-red">*</span> {{ __('ค่าธรรมเนียมการซื้อ') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="purchase_fee" id="purchase_fee" class="form-control" value="{{ $user->purchase_fee }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sales_fee"><span class="text-red">*</span> {{ __('ค่าธรรมเนียมการขาย') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="sales_fee" id="sales_fee" class="form-control" value="{{ $user->sales_fee }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roles-admin"><span class="text-red">*</span> {{ __('สิทธิ์การใช้งาน') }}</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input type="checkbox" name="roles[]" id="roles-admin" class="form-check-input" value="admin" @if($isAdmin) checked @endif>
                                            <label class="form-check-label" for="roles-admin">{{ __('Admin') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input type="checkbox" name="roles[]" id="roles-sub_admin" class="form-check-input" value="sub_admin" @if($isSubAdmin) checked @endif>
                                            <label class="form-check-label" for="roles-sub_admin">{{ __('Sub Admin') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input type="checkbox" name="roles[]" id="roles-member" class="form-check-input" value="member" @if($isMember) checked @endif>
                                            <label class="form-check-label" for="roles-member">{{ __('Member') }}</label>
                                        </div>
                                    </div>
                                </div>
                      
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
                            <button type="button" class="btn btn-default" id='btn-colse-edit-password-modal'>ปิด</button>
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
    $(document).on('click','#btn-upload-image',function(e){
        $('#image').click();
    });
    $(document).on('change','#image',function(e){
        var size = (this.files[0].size);
        var name = this.files[0].name;
        if( size > 2000000 ) {
            Swal.fire({
                icon: 'warning',
                title: 'คำเตือน',
                text: `ไม่สามารถอัปโหลด "${name}" เนื่องจากไฟล์ของคุณมีขนาดเกิน 6.0 MB`,
            });
            return false;
        }
        readURL(this, '.avatars-one');
    });

    $(document).on('click','.image-manager-remove',function(e){
        var element = `
            <div class="image-manager-content__upload">
                <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
            </div>
            <p class="text-muted m-1"><span class="text-red">*</span> ภาพสินค้า</p>  
        `;
        $('.image-manager-item').html(element);
    });

    function readURL(input, element) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // $(element).attr('src', e.target.result);
                $(element).attr('style', `background-image: url(${e.target.result})`)
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#user-form').ajaxForm({
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
                window.location.href = '{{ route("users.index") }}';
            });
            
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    if(key == 'image') {
                        $('.image-manager-item').parent().append(`<div class="invalid-feedback">${v[i]}</div>`);
                    }
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            loader.close();
        }
    });

    $('#btn-edit-password').on('click',function(e){
        $('#edit-password-modal').modal('show');
    });
    $('#btn-colse-edit-password-modal').on('click',function(e){
        $('#edit-password-form').find('input').val('');
        $('#edit-password-modal').modal('hide');
    });
    $('#edit-password-form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            loader.close();
            $('#edit-password-modal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'แก้ไขรหัสผ่านเรียบร้อย',
            });
            $('#password').val('');
            $('#password_confirmation').val('');
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
    $('input[type="radio"][name="type"]').change(function(e){
        if($(this).val() == 2){
            $('#display-trade-fee').show();
        }else{
            $('#display-trade-fee').hide();
        }
    });
    $('#expired_at').datepicker({
        format:'dd/mm/yyyy',
        language:'th',
        autoclose: true,
    });
</script>
@endsection
