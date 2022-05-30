@extends('front.layouts.main')
@section('title','Beneficairy create') 
@section('css')
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">ผู้รับผลประโยชน์</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5  class="card-title"><i class="fas fa-user-check text-muted mr-2"></i> ผู้รับผลประโยชน์</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <form id="form" action="{{ empty($beneficiary) ? route('front.users.beneficiary.store') : route('front.users.beneficiary.update') }}" method="post">
                            @csrf
                            @if(!empty($beneficiary))<input type="hidden" name="_method" value="PUT">@endif
                            <div class="form-group">
                                <label for="name"><span class="text-red">*</span> {{ __('ชื่อ-นามสกุล') }}</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ !empty($beneficiary) ? $beneficiary->name : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="relationship"><span class="text-red">*</span> {{ __('ความสัมพันธ์') }}</label>
                                <input type="text" name="relationship" id="relationship" class="form-control" value="{{ !empty($beneficiary) ? $beneficiary->relationship : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="address"><span class="text-red">*</span> {{ __('ที่อยู่') }}</label>
                                <textarea name="address" id="address" rows="3" class="form-control">{{ !empty($beneficiary) ? $beneficiary->address : '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone"><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</label>
                                <input type="number" name="phone" id="phone" class="form-control" value="{{ !empty($beneficiary) ? $beneficiary->phone : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="phone"><span class="text-red">*</span> {{ __('รหัสบัตรประชาชน/Passport') }}</label>
                                <input type="number" name="id_card_number" id="id_card_number" class="form-control" value="{{ !empty($beneficiary) ? $beneficiary->id_card_number : '' }}">
                            </div>
                            <div class="row">
                                <div class="col-12 text-center mt-2">
                                    <button type="submit" class="btn btn-success bg-green mr-2">บันทึกข้อมูล</button>
                                    <a href="{{ route('front.users.profile') }}" class="btn btn-default">ยกเลิก</a>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
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
            Swal.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลเรียบร้อย',
            }).then(() => {
                window.location.href = '{{ route("front.users.profile") }}';
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