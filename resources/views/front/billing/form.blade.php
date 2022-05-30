@extends('front.layouts.main')
@section('title','Payment') 
@section('css')
<style>
    .image-manager-content__upload{
        height: 200px;
    }
    .image-manager-remove{
        top: 186px;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">แจ้งการชำระเงิน</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5  class="card-title">แจ้งการชำระเงิน</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <p class="mb-0">เลขที่ #{{ $id }}</p>
                        <p>{{$remark}}</p>
                        <form id="form" action="{{ route('front.billing.update', $id) }}" method="post" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label for=""><span class="text-red">*</span> วันที่โอนเงิน</label>
                                <input type="text" class="form-control" name="date" id="date">
                            </div>
                            <div class="form-group">
                                <label for=""><span class="text-red">*</span> เวลาโอนเงิน</label>
                                <input type="text" class="form-control" name="time" id="time">
                            </div>
                            <div class="form-group">
                                <label for=""><span class="text-red">*</span> ภาพสลิปการโอนเงิน</label>
                                <div class="text-center image-manager-box">
                                    <div class="image-manager-item position-relative">
                                        <div class="image-manager-content__upload">
                                            <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <input type="file" class="d-none" name="image" id="image" accept="image/*">  
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-block bg-green btn-transfer mt-5 mb-1">ยืนยันแจ้งการชำระเงิน</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script>
    $('#date').datepicker({
        autoclose:true,
        format:'dd/mm/yyyy',
        language:'th',
        setDate: new Date()
    });
    $('#time').inputmask("99:99");

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
                window.location.href = '{{ route("front.billing.index") }}';
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
                    } else if (key == 'status') {
                        $('#form-group-status').append(`<div class="invalid-feedback d-flex">${v[i]}</div>`);
                    }
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            if( status != 422 ) {
                Swal.fire({
                    icon:'error',
                    title: 'คำเตือน',
                    text: message,
                });
            }
            loader.close();
        }
    });

    $(document).on('click','#btn-upload-image',function(e){
        $('#image').click();
    });
    $(document).on('change','#image',function(e){
        var size = (this.files[0].size);
        var name = this.files[0].name;
        if( size > 2000000 ) {
            Swal.fire({
                title: 'คำเตือน',
                text: `ไม่สามารถอัปโหลด "${name}" เนื่องจากไฟล์ของคุณมีขนาดเกิน 6.0 MB`,
            });
            return false;
        }
        var element = `
            <div class="image-manager-content__upload">
                <img src="" class="image-manager-image" id="image-preview">
            </div>
            <button type="button" class="btn btn-danger btn-circle image-manager-remove"><i class="fas fa-times"></i></button>  
        `;
        $('.image-manager-item').html(element);
        readURL(this, '#image-preview');
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

    $(document).on('click','.image-manager-remove',function(e){
        var element = `
            <div class="image-manager-item position-relative">
                <div class="image-manager-content__upload">
                    <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <input type="file" class="d-none" name="image" id="image" accept="image/*">   
        `;
        $('.image-manager-box').html(element);
    });

</script>
@endsection