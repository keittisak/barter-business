@extends('front.layouts.main')
@section('title',empty($shop) ? 'Shop create' : 'Shop update') 
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

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ empty($shop) ? 'เพิ่มร้านค้า' : 'แก้ไขร้านค้า' }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form id="form" action="{{ empty($shop) ? route('front.users.shops.store') : route('front.users.shops.update', $shop->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @if(!empty($shop))<input type="hidden" name="_method" value="PUT">@endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('ข้อมูลทั่วไป') }}</h3>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 offset-md-3 col-12">

                        <div class="form-group">
                            <label for="name"><span class="text-red">*</span> {{ __('ชื่อร้านค้า') }}</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ !empty($shop)?$shop->name:'' }}">
                        </div>
                        {{-- <div class="form-group">
                            <label for="code"><span class="text-red">*</span> {{ __('รหัสร้านค้า') }}</label>
                            <input type="text" class="form-control" name="code" id="code" value="{{ !empty($shop)?$shop->code:'' }}">
                        </div> --}}
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> {{ __('รายละเอียด') }}</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ !empty($shop)?$shop->description:'' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> {{ __('ประเภทร้านค้า') }}</label>
                            <select name="type_id" id="type_id" class="form-control">
                                <option value="">--เลือก--</option>
                                @foreach($shopTypes as $type)
                                <option value="{{ $type->id }}" @if(!empty($shop->type_id) && $shop->type_id  == $type->id) selected @endif>{{ $type->name }}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="last_name">{{ __('ประเทศ') }}</label>
                            <select name="country_id" id="country_id" class="form-controller select2 w-100 countries" data-selected="{{ !empty($shop->province_id) ? $shop->province_id : '' }}">
                                <option value="216">ไทย</option>
                            </select> 
                        </div>
                        <div class="form-group">
                            <label for="province_id">{{ __('จังหวัด') }}</label>
                            <select name="province_id" id="province_id" class="form-controller select2 w-100 provinces" data-selected="">
                                <option value=""></option>
                            </select> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('การจัดการสื่อ') }}</h3>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <div class="row row-image-manager">
                            @if(!empty($shop) && $shop->image)
                                <div class="col-6 text-center image-manager-box">
                                    <div class="image-manager-item position-relative">
                                        <div class="image-manager-content">
                                            <img src="{{ $shop->image }}" class="image-manager-image" id="cover-image">
                                        </div>
                                        <p class="text-muted m-2"><span class="text-red">*</span> ภาพปก</p> 
                                        <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="cover-image"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            @else 
                                <div class="col-6 text-center image-manager-box">
                                    <div class="image-manager-item position-relative">
                                        <div class="image-manager-content__upload">
                                            <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="cover-image"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="text-muted m-1"><span class="text-red">*</span> ภาพปก</p>   
                                    </div>
                                    <input type="file" class="d-none" name="image" id="image" value="" accept="image/*">
                                </div>
                            @endif
                            
                            @if(!empty($shop) && $shop->images)
                                @foreach ($images as $key => $item)
                                    <div class="col-6 text-center image-manager-box">
                                        <div class="image-manager-item position-relative">
                                            <div class="image-manager-content">
                                                <img src="{{ $item->image }}" class="image-manager-image" id="images-{{ $key+1 }}">
                                            </div>
                                            <p class="text-muted m-2">รูปภาพ {{ $key+1 }}</p> 
                                            <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="images"><i class="fas fa-times"></i></button>
                                        </div>
                                        <input type="hidden" class="input-images" name="image_id[]" value="{{ $item->id }}">  
                                    </div>
                                @endforeach
                                @if(count($images) < 7)
                                    <div class="col-6 text-center image-manager-box">
                                        <div class="image-manager-item position-relative">
                                            <div class="image-manager-content__upload">
                                                <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <p class="text-muted m-1">รูปภาพ {{ count($images)+1 }}</p>   
                                        </div>
                                        <input type="file" class="d-none input-images" name="images[]" id="images-{{ count($images)+1 }}" value="" accept="image/*">
                                    </div>
                                @endif
                            @else 
                                <div class="col-6 text-center image-manager-box">
                                    <div class="image-manager-item">
                                        <div class="image-manager-content__upload">
                                            <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="text-muted m-1"><span class="text-red">*</span> รูปภาพ 1</p>   
                                    </div>
                                    <input type="file" class="d-none input-images" name="images[]" id="images-1" value="" accept="image/*">
                                </div>
                            @endif
    
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('ข้อมูลติดต่อ') }}</h3>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 offset-md-3 col-12">
                        {{-- <div class="form-group">
                            <label for="contact_name"><span class="text-red">*</span> {{ __('ชื่อผู้ติดต่อ') }}</label>
                            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ !empty($shop)?$shop->contact_name:'' }}">
                        </div>
                        <div class="form-group">
                            <label for="phone"><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ !empty($shop)?$shop->phone:'' }}">
                        </div> --}}
                        <div class="form-group">
                            <label for="full_address"><span class="text-red">*</span> {{ __('ที่อยู่ร้านค้า') }}</label>
                            <textarea name="full_address" id="full_address" cols="30" rows="3" class="form-control">{{ !empty($shop)?$shop->full_address:'' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row pb-4">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success bg-green mr-2">บันทึกข้อมูล</button>
                    <a href="{{ route('front.users.shops.index') }}" class="btn btn-danger">ยกเลิก</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="image-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                เพิ่มรูปภาพ/ลิ้งค์วีดีโอ
            </div>
            <div class="modal-body">
                <label for=""><span class="text-red">*</span>เลือก</label>
                <div class="btn-group btn-block mb-4">
                    <button class="btn btn-default" id="select-image">รูปภาพ</button>
                    <button class="btn btn-default" id="select-url-video">ลิ้งค์วีดีโอ</button>
                </div>
                <div id="display-url-vedio" style="display: none">
                    <div class="form-group">
                        <label for=""><span class="text-red">*</span>ลิ้ง</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary float-right" data-dismiss="modal">ตกลง</button>
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
    $(document).ready(function (){
        @if(!empty($shop->province_id)) $(".provinces").val({{ $shop->province_id }}).change(); @endif
    })
    $(document).on('click','.image-manager-upload', function(e){
        var type = $(this).data('type');
        if(type == 'cover-image'){
            $('#image').click();
        }else{
            var display = $(this).parents('.image-manager-box');
            var input = $(display).find(`.input-images` );
            input.click();
        }
    });

    $(document).on('change','#image',function(e){
        var validate = validateImageSize(this.files[0]);
        if( !validate ) { return false; }
        var display = $(this).parent('.image-manager-box');
        var element = `
            <div class="image-manager-content">
                <img src="" class="image-manager-image" id="cover-image">
            </div>
            <p class="text-muted m-2"><span class="text-red">*</span> ภาพปก</p> 
            <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="cover-image"><i class="fas fa-times"></i></button>  
        `;
        display.find('.image-manager-item').html(element);
        readURL(this, '#cover-image');
    });

    $(document).on('change','.input-images',function(e){
        var validate = validateImageSize(this.files[0]);
        if( !validate ) { return false; }
        var display = $(this).parent('.image-manager-box');
        var number = $('.image-manager-item').length;
        var element = `
            <div class="image-manager-content">
                <img src="" class="image-manager-image">
            </div>
            <p class="text-muted m-2"><span class="text-red">*</span> รูปภาพ ${number-1}</p> 
            <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="images"><i class="fas fa-times"></i></button>  
        `;
        display.find('.image-manager-item').html(element);
        readURL(this, display.find('img'));
        if(number <= 7){
            $(this).parents('.row-image-manager').append(`
            <div class="col-6 text-center image-manager-box">
                <div class="image-manager-item position-relative">
                    <div class="image-manager-content__upload">
                        <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                    </div>
                    <p class="text-muted m-1">รูปภาพ ${number}</p>   
                </div>
                <input type="file" class="d-none input-images" name="images[]" value="" accept="image/*">
            </div>`);
        }
    });

    function validateImageSize (file) {
        var size = (file.size);
        var name = file.name;
        if( size > 2000000 ) {
            Swal.fire({
                title: 'คำเตือน',
                text: `ไม่สามารถอัปโหลด "${name}" เนื่องจากไฟล์ของคุณมีขนาดเกิน 6.0 MB`,
            });
            return false;
        }
        return true;
    }

    $(document).on('click','.image-manager-remove',function(e){
        var type = $(this).data('type');
        var display = $(this).parents('.image-manager-box');
        if(type == 'cover-image'){
            display.html(`
                <div class="image-manager-item position-relative">
                    <div class="image-manager-content__upload">
                        <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="cover-image"><i class="fas fa-plus"></i></button>
                    </div>
                    <p class="text-muted m-1"><span class="text-red">*</span> ภาพปก</p>   
                </div>
                <input type="file" class="d-none" name="image" id="image" value="" accept="image/*">
            `);
        }else{
            var number = $('.image-manager-item').length;
            if(number > 1){
                display.remove();
            }else if(number == 1){
                display.html(`
                    <div class="image-manager-item position-relative">
                        <div class="image-manager-content__upload">
                            <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                        </div>
                        <p class="text-muted m-1">รูปภาพ 1</p>   
                    </div>
                    <input type="file" class="d-none input-images" name="images[]" id="images-1" value="" accept="image/*">
                `);
            }
            $('.input-images').each(function(i,e){
                $(e).parent('.image-manager-box').find('p').html(`รูปภาพ ${i+1}`);
            });
        }
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
            @if(!empty($shop))
            window.location.href = '{{ route("front.users.shops.show", $shop->id) }}';
            @else
            window.location.href = '{{ route("front.users.shops.index") }}';
            @endif
            
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
            Swal.fire(message, ``, `error`);
            loader.close();
        }
    });

    $('#code').on('keyup',function(e){
        var text = $(this).val();
        $(this).val(text.replace(/\s+/g, ''));
    })

</script>
@endsection