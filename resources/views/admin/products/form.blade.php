@extends('admin.layouts.main')
@section('title','Products create') 
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
                <h1>เพิ่มข้อมูลสินค้า</h1>
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
                            <form id="form" action="{{ empty($product) ? route('products.store',[$userID, $shop->id]) : route('products.update', [$userID, $shop->id, $product->id])   }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if(!empty($product))<input type="hidden" name="_method" value="PUT">@endif
                                <div class="row form-group">
                                    <div class="col-6 col-md-4 text-center mx-auto image-manager-box">
                                        @if(empty($product))
                                            <div class="image-manager-item position-relative">
                                                <div class="image-manager-content__upload">
                                                    <button type="button" class="btn btn-info btn-circle" id="btn-upload-image"><i class="fas fa-plus"></i></button>
                                                </div>
                                                <p class="text-muted m-1"><span class="text-red">*</span> ภาพสินค้า</p>
                                            </div>
                                            <input type="file" class="d-none" name="image" id="image" accept="image/*">   
                                        @else
                                            <div class="image-manager-item position-relative">
                                                <div class="image-manager-content__upload">
                                                    <img src="{{ $product->image }}" class="image-manager-image" id="image-preview">
                                                </div>
                                                <p class="text-muted pt-3"><span class="text-red">*</span> ภาพสินค้า</p> 
                                                <button type="button" class="btn btn-danger btn-circle image-manager-remove"><i class="fas fa-times"></i></button>  
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name"><span class="text-red">*</span> {{ __('ชื่อสินค้า') }}</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ !empty($product) ? $product->name : '' }}">
                                </div>
                                {{-- <div class="form-group">
                                    <label for=""><span class="text-red">*</span> {{ __('รายละเอียด') }}</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ !empty($product)?$product->description:'' }}</textarea>
                                </div> --}}
                                <div class="form-group">
                                    <label for="price"><span class="text-red">*</span> {{ __('จำนวนเทรดบาท') }}</label>
                                    <input type="number" class="form-control" name="price" id="price" value="{{ !empty($product) ? $product->price : '' }}">
                                </div>
                                <div class="form-group" id="form-group-status">
                                    <label class="d-block"><span class="text-red">*</span> สถานะ</label>
                                    <div class="form-check d-inline mr-2">
                                        <input class="form-check-input" type="radio" name="status" id="staus-for-sale" value="for_sale" @if(!empty($product) && $product->status == 'for_sale') checked @endif checked>
                                        <label class="form-check-label" for="staus-for-sale">พร้อมขาย</label>
                                    </div>
                                    <div class="form-check d-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status-sold-out" value="sold_out" @if(!empty($product) && $product->status == 'sold_out') checked @endif>
                                        <label class="form-check-label" for="status-sold-out">สินค้าหมด</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center mt-5">
                                        <button type="submit" class="btn btn-success bg-green">บันทึกข้อมูล</button>
                                        <a href="{{ route('users.shops.show',[$userID,$shop->id]) }}" class="btn btn-info ml-2">ยกเลิก</a>
                                        @if(!empty($product))<button type="button" class="btn btn-danger btn-sm ml-4" id="btn-delete">ลบข้อมูลสินค้า</button>@endif
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
            <p class="text-muted pt-3"><span class="text-red">*</span> ภาพสินค้า</p> 
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
                <p class="text-muted m-1"><span class="text-red">*</span> ภาพสินค้า</p>
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
                window.location.href = '{{ route("users.shops.show", [$userID, $shop->id]) }}';
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

    $('#btn-delete').on('click',function(e){
        Swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบข้อมูลสินค้า?',
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
        }).then(function(result){
            if (result.isConfirmed) {
                var __url = `{{ route('products.destroy',['_user_Id','_shop_id','_product_id']) }}`;
                @if(!empty($product))
                __url = __url.replace('_user_Id', {{$userID}});
                __url = __url.replace('_shop_id', {{$shop->id}});
                __url = __url.replace('_product_id', {{$product->id}});
                @endif
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'DELETE',
                        _token:'{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                    }
                }).done(function(data){
                    window.location.href = '{{ route("users.shops.show", [$userID,$shop->id]) }}';
                }).fail(function( jqxhr, textStatus ) {
                    var message = jqXHR.responseJSON.message
                    Swal.fire(`คำเตือน`, message, `error`);
                    loader.close();
                });
            }
        });
    })
</script>
@endsection
