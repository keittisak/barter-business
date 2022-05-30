@extends('front.layouts.main')
@section('title','Profile edit') 
@section('css')
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">แก้ไขโปรไฟล์</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body row">
                <div class="col-md-6 offset-md-3 col-12">
                    <form id="user-form" action="{{ route('front.users.profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group text-center">
                            <div class="avatars rounded-circle avatars-xl d-block my-0 mx-auto">
                                <div class="avatars-one" style="background-image: url({{ empty($user->image) ? asset('assets/images/img_profile_default.jpg') : $user->image }}"></div>
                            </div>
                            <input type="file" id="image" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-default btn-sm mt-2" id="btn-upload-image">เลือกรูป</button>
                            <button type="button" class="btn btn-default btn-sm mt-2" id="btn-edit-password">แก้ไขรหัสผ่าน</button>
                        </div>
                        <div class="form-group">
                            <label for="email"><span class="text-red">*</span> {{ __('อีเมล') }}</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="phone"><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</label>
                            <input type="number" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="first_name"><span class="text-red">*</span> {{ __('ชื่อ') }}</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name"><span class="text-red">*</span> {{ __('นามสกุล') }}</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}">
                        </div>
                        <div class="form-group">
                            <label for="id_card_number"><span class="text-red">*</span> {{ __('รหัสบัตรประชาชน/Passport') }}</label>
                            <input type="text" name="id_card_number" id="id_card_number" class="form-control" value="{{ $user->id_card_number }}">
                        </div>
                        <div class="form-group">
                            <label for="address"><span class="text-red">*</span> {{ __('ที่อยู่') }}</label>
                            <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group d-none">
                            <label for="last_name">{{ __('ประเทศ') }}</label>
                            <select name="country_id" id="country_id" class="form-controller select2 w-100 countries" data-selected="{{ $user->province_id }}">
                                <option value="216">ไทย</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="province_id">{{ __('จังหวัด') }}</label>
                            <select name="province_id" id="province_id" class="form-controller select2 w-100 provinces" data-selected="{{ $user->district_id }}">
                                <option value=""></option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="district_id">{{ __('อำเภอ') }}</label>
                            <select name="district_id" id="district_id" class="form-controller select2 w-100 districts" data-selected="{{ $user->subdistrict_id }}">
                                <option value=""></option>
                            </select>                    
                        </div>
                        <div class="form-group">
                            <label for="subdistrict_id">{{ __('ตำบล') }}</label>
                            <select name="subdistrict_id" id="subdistrict_id" class="form-controller select2 w-100 subdistricts" >
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="postalcode">{{ __('รหัสไปรษณีย์') }}</label>
                            <input type="text" name="postalcode" id="postalcode" class="form-control" value="{{ $user->postalcode }}">
                        </div>
                        <div class="row">
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-success bg-green">บันทึกข้อมูล</button>
                                <a href="{{ route('front.users.profile') }}" class="btn btn-default">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade edit-password-modal" id="edit-password-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">แก้ไขรหัสผ่าน</h4>
            </div>
            <div class="modal-body">
                <form id="edit-password-form" action="{{ route('front.users.profile.update.password') }}" method="post" enctype="multipart/form-data">
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

@endsection

@section('javascript')
<script src="{{ asset('assets/js/location.js') }}"></script>
<script>
    $(document).ready(function (){
        @if(!empty($user->province_id)) $(".provinces").val({{ $user->province_id }}).change(); @endif
        @if(!empty($user->district_id))$(".districts").val({{ $user->district_id }}).change(); @endif
        @if(!empty($user->subdistrict_id))$(".subdistricts").val({{ $user->subdistrict_id }}).change(); @endif
    })
    $(document).on('click','#btn-upload-image',function(e){
        $('#image').click();
    });
    $(document).on('change','#image',function(e){
        var size = (this.files[0].size);
        var name = this.files[0].name;
        if( size > 1000000 ) {
            Swal.fire({
                // icon: 'warning',
                title: 'คำเตือน',
                text: `ไม่สามารถอัปโหลด "${name}" เนื่องจากไฟล์ของคุณมีขนาดเกิน 1.0 MB`,
            });
            return false;
        }
        var file_data = this.files[0];   
        var form_data = new FormData();                  
        form_data.append('image', file_data);
        form_data.append('_token', '{{ csrf_token() }}');
        form_data.append('_method', 'PUT');
        $.ajax({
            url: "{{ route('front.users.profile.image.upload') }}",
            method: "POST",
            // dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data:form_data,
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            loader.close();
            $('.profile-user-img').find('img').attr('src', data.image);
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            loader.close();
        });
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
                $(element).attr('src', e.target.result);
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
            window.location.href = '{{ route("front.users.profile") }}';
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
            $('#edit-password-form').find('input').val('');
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