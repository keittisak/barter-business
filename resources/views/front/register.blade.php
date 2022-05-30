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
    <h5 class="title-header">สมัครสมาชิก</h5>
@endsection


@section('content')
<div class="login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg text-black">สมัครสมาชิก</p>
            <form id="form" action="{{ route('front.users.store') }}" method="post">
                @csrf
                {{-- <div class="form-group">
                    <button type="button" class="btn btn-link text-blue pl-0" id="btn-modal-search-recommen" data-toggle="modal" data-target="#modal-search-recommen">ค้นหาผู้แนะนำ</button>
                    <div id="result-recommen">
                    </div>
                </div> --}}
                <div class="form-group">
                    <label for="recommended_by"><span class="text-red">*</span> {{ __('รหัสผู้แนะนำ (ไม่บังคับ)') }}</label>
                    @if(!empty($user->id))
                    <p>{{ $user->full_name() }}</p>
                    @endif
                    <input type="number" name="recommended_by" id="recommended_by" class="form-control" value="{{ !empty($user->id) ? $user->code : '' }}">
                </div>
                <div class="form-group">
                    <label for=""><span class="text-red">*</span> {{ __('อีเมล') }}</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                {{-- <div class="form-group">
                    <label for=""><span class="text-red">*</span> {{ __('รหัสผ่าน') }}</label>
                    <input type="password" name="password" id="password"  class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_confirmation"><span class="text-red">*</span> {{ __('ยืนยันรหัสผ่าน') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"  class="form-control">
                </div> --}}
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
                <div class="form-group">
                    <label for="address"><span class="text-red">*</span> {{ __('ที่อยู่') }}</label>
                    <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group d-none">
                    <label for="last_name">{{ __('ประเทศ') }}</label>
                    <select name="country_id" id="country_id" class="form-controller select2 w-100 countries">
                        <option value="216">ไทย</option>
                    </select> 
                </div>
                <div class="form-group">
                    <label for="province_id">{{ __('จังหวัด') }}</label>
                    <select name="province_id" id="province_id" class="form-controller select2 w-100 provinces">
                        <option value=""></option>
                    </select> 
                </div>
                <div class="form-group">
                    <label for="district_id">{{ __('อำเภอ') }}</label>
                    <select name="district_id" id="district_id" class="form-controller select2 w-100 districts">
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
                    <input type="text" name="postalcode" id="postalcode" class="form-control">
                </div>
                <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-block bg-green">ยืนยัน</button>
                </div>
                </div>
            </form>
        
            <div class="mb-1 mt-4">
                <a class="float-right" href="{{ route('front.users.login.form') }}">เป็นสมาชิกอยู่แล้ว</a>
            </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<div class="modal fade modal-search-recommen" id="modal-search-recommen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="shop-search">ค้นหาผู้แนะนำ</label>
                    <div class="input-group">
                        <input type="text" name="text_search" class="form-control" placeholder="อีเมล / เบอร์โทรศัพท์" id="text-search">
                        <span class="input-group-append">
                            <button type="submit" class="btn btn-outline-success bg-green" id="btn-search-recommen">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-colose-modal" data-dismiss="modal">
        <button type="button" class="btn btn-success btn-circle btn-lg"><i class="fas fa-times text-r24"></i></button>
        <p class="text-white">ปิด</p>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('assets/js/location.js') }}"></script>
<script>
    $('#btn-search-recommen').on('click',function(e){
        var button = $(this);
        var text = $('#text-search').val();
        if( text == '' ) { return false; }
        $.ajax({
            url: "{{ route('front.users.search') }}",
            method: "POST",
            data:{
                    text:text,
                    _token:'{{ csrf_token() }}'
                },
            beforeSend: function( xhr ) {
                $('#modal-search-recommen').modal('hide');
                $('#result-recommen').html('');
                loader.init();
            }
        }).done(function(data){
            var element = ``;
            if(data == '') {
                element = `<p class="mb-0 pb-0">ไม่พบผู้แนะนำลองอีกครั้ง</p>`;
            } else {
                element = `<p class="mb-0 pb-0">${data.first_name+' '+data.last_name} / ${data.phone} <button type="button" class="btn btn-link btn-sm text-danger pb-2 ml-2" id="btn-remove-recommen"><i class="fas fa-times"></i></button></p>
                    <input type="hidden" name="recommended_by" value="${data.id}" readonly>
                    `;
            }
            $('#result-recommen').html(element);
            loader.close();
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            alert(JSON.stringify(errors));
            loader.close();
        });
    });
    $(document).on('click','#btn-remove-recommen',function(e){
        $('#result-recommen').html('');
    });
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            Swal.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลเรียบร้อย',
                text: `ทางเราจะทำการตรวจแล้วติดต่อกลับเพื่อยืนยันการสมัครสมาชิก`,
            }).then(function(){
                window.location.href = '{{ route("front.home") }}';
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