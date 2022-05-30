@extends('admin.layouts.main')
@section('title', empty($branner) ? 'Branner create' : 'Branner edit') 
@section('css')
<style>
    .image-manager-item{
        /* flex: 0 0 115px; */
        width: 100%;
        margin-bottom: 20px;
        /* max-width: 115px;
        min-height: 115px;
        max-height: 115px;
        margin: 0 0 40px; */

        
    }
    .image-manager-content{
        /* position: relative; */
        border: 1px solid rgba(0,0,0,.2);
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 115px;
        margin-bottom: 14px;
    }
    .image-manager-remove{
        position: absolute;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        bottom: 44px;
    }
    .image-manager-image{
        /* width: 100%; */
        height: 100%;
        object-fit: contain;
        /* min-width: 100%; */
        max-width: 100%;
    }
    .image-manager-vedio{
        /* height: 140px; */
    }
    .image-manager-content__upload{
        position: relative;
        height: 115px;
        width: 100%;
        border: 1px dashed #1791f2;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .ytp-large-play-button{
        display: none;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ !empty($branner) ? 'แก้ไข' : 'เพิ่ม' }}ภาพสไลด์{{ (Request('after_login') == 'y') ? 'หลัง Login' : ''   }}</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-6 offset-md-3 col-12">
                            <form id="form" action="{{ !empty($branner) ? route('branners.update', $branner->id) : route('branners.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if(!empty($branner))<input type="hidden" name="_method" value="PUT">@endif
                                <input type="hidden" name="after_login"value="{{ $branner->after_login ?? Request('after_login') }}">
                                <div class="row form-group">
                                    <div class="col-6 col-md-4 text-center mx-auto image-manager-box">
                                        @if(empty($branner))
                                            <div class="image-manager-item position-relative">
                                                <div class="image-manager-content__upload">
                                                    <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
                                                </div>
                                                <p class="text-muted m-1"><span class="text-red">*</span> รูปภาพ</p>
                                            </div>
                                            <input type="file" class="d-none" name="image" id="image" accept="image/*">   
                                        @else
                                            <div class="image-manager-item position-relative">
                                                <div class="image-manager-content__upload">
                                                    <img src="{{ $branner->image }}" class="image-manager-image" id="image-preview">
                                                </div>
                                                <p class="text-muted pt-3"><span class="text-red">*</span> รูปภาพ</p> 
                                                <button type="button" class="btn btn-danger btn-circle image-manager-remove"><i class="fas fa-times"></i></button>  
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name"><span class="text-red">*</span> {{ __('รายละเอียด') }}</label>
                                    <input type="text" class="form-control" name="description" id="description" value="{{ !empty($branner) ? $branner->description : '' }}">
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center mt-5">
                                        <button type="submit" class="btn btn-success bg-green mr-4">บันทึกข้อมูล</button>
                                        <a href="{{ route('branners.index') }}" class="btn btn-default">ย้อนกลับ</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
                title: 'คำเตือน',
                text: `ไม่สามารถอัปโหลด "${name}" เนื่องจากไฟล์ของคุณมีขนาดเกิน 6.0 MB`,
            });
            return false;
        }
        var element = `
            <div class="image-manager-content__upload">
                <img src="" class="image-manager-image" id="image-preview">
            </div>
            <p class="text-muted pt-3"><span class="text-red">*</span> รูปภาพ</p> 
            <button type="button" class="btn btn-danger btn-circle image-manager-remove"><i class="fas fa-times"></i></button>  
        `;
        $('.image-manager-item').html(element);
        readURL(this, '#image-preview');
    });

    $(document).on('click','.image-manager-remove',function(e){
        var element = `
            <div class="image-manager-item position-relative">
                <div class="image-manager-content__upload">
                    <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
                </div>
                <p class="text-muted m-1"><span class="text-red">*</span> รูปภาพ</p>
            </div>
            <input type="file" class="d-none" name="image" id="image" accept="image/*">   
        `;
        $('.image-manager-box').html(element);
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
                window.location.href = '{{ route("branners.index",["after_login"=>Request("after_login")]) }}';
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
                    title: 'คำเตือน',
                    text: message,
                    icon: 'error'
                });
            }
            loader.close();
        }
    });

</script>
@endsection
