@extends('admin.layouts.main')
@section('title', (!isset($auction) ? 'Auction create' : 'Auction edit')) 
@section('css')
<style>
    #DataTables_Table_0_filter {
        display: none;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ (!empty($auction) ? 'แก้ไขประมูลสินค้า' : 'เพิ่มประมูลสินค้า') }}</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="form" action="{{ ( empty($auction) ? route('auctions.store') : route('auctions.update',$auction->id) ) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(!empty($auction))<input type="hidden" name="_method" value="PUT">@endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('ข้อมูลทั่วไป') }}</h3>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 offset-md-3 col-12">
                                <div class="form-group">
                                    <label for="name"><span class="text-red">*</span> {{ __('ชื่อสินค้า') }}</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ ( !empty($auction) ? $auction->name : '' ) }}">
                                </div>
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> {{ __('รายละเอียด') }}</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ ( !empty($auction) ? $auction->description : '' ) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="name"><span class="text-red">*</span> {{ __('ราคาเริ่มต้น') }}</label>
                                    <input type="number" class="form-control" name="price" id="price" value="{{ ( !empty($auction) ? $auction->price : '' ) }}">
                                </div>
                                <div class="form-group">
                                    <label for="name"><span class="text-red">*</span> {{ __('Bid ขั้นต่ำ') }}</label>
                                    <input type="number" class="form-control" name="min_bid" id="min_bid" value="{{ ( !empty($auction) ? $auction->min_bid : '' ) }}">
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
                                    <div class="col-6 text-center image-manager-box mb-2">
                                        @if( empty($auction) || empty($auction->image_1) )
                                            <div class="image-manager-item">
                                                <div class="image-manager-content__upload">
                                                    <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="image-manager-item">
                                                <div class="image-manager-content">
                                                    <img src="{{ $auction->image_1 }}" class="image-manager-image">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="images"><i class="fas fa-times"></i></button> 
                                            </div>
                                        @endif
                                            <p class="text-muted m-1"><span class="text-red">*</span> รูปภาพ 1</p>
                                            <input type="file" class="d-none input-images" name="image_1" id="images-1" value="" accept="image/*">
                                    </div>
                                    @for($i=2; $i <= 4; $i++)
                                    <div class="col-6 text-center image-manager-box mb-2">
                                        @if( empty($auction) || empty($auction['image_'.$i]) )
                                            <div class="image-manager-item">
                                                <div class="image-manager-content__upload">
                                                    <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="image-manager-item">
                                                <div class="image-manager-content">
                                                    <img src="{{ $auction['image_'.$i] }}" class="image-manager-image">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="images"><i class="fas fa-times"></i></button> 
                                            </div>
                                        @endif
                                            <p class="text-muted m-1">รูปภาพ {{$i}}</p>
                                            <input type="file" class="d-none input-images" name="image_{{$i}}" id="images-{{$i}}" value="" accept="image/*">
                                    </div>
                                    @endfor
            
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('การจัดเวลาประมูล') }}</h3>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6 offset-md-3 col-12">
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> วันที่เริ่มประมูล</label>
                                    <input type="text" class="form-control input-date" name="started_date" id="started_date" value="{{ ( !empty($auction) ? date('d/m/Y', strtotime($auction->started_at)) : date('d/m/Y') ) }}">
                                </div>
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> เวลาเริ่มประมูล</label>
                                    <input type="text" class="form-control input-time" name="started_time" id="started_time" value="{{ ( !empty($auction) ? date('H:i', strtotime($auction->started_at)) : '0000' ) }}">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> วันที่จบการประมูล</label>
                                    <input type="text" class="form-control input-date" name="expired_date" id="expired_date" value="{{ ( !empty($auction) ? date('d/m/Y', strtotime($auction->expired_at)) : date('d/m/Y') ) }}">
                                </div>
                                <div class="form-group">
                                    <label for=""><span class="text-red">*</span> เวลาจบการประมูล</label>
                                    <input type="text" class="form-control input-time" name="expired_time" id="expired_time" value="{{ ( !empty($auction) ? date('H:i', strtotime($auction->expired_at)) : '0000' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row pb-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success bg-green mr-4">บันทึกข้อมูล</button>
                            <a href="{{ route('auctions.index') }}" class="btn btn-default">ย้อนกลับ</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script>
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

    $(document).on('change','.input-images',function(e){
        var validate = validateImageSize(this.files[0]);
        if( !validate ) { return false; }
        var display = $(this).parent('.image-manager-box');
        var number = $('.image-manager-item').length;
        var element = `
            <div class="image-manager-content">
                <img src="" class="image-manager-image">
            </div>
            <button type="button" class="btn btn-danger btn-circle image-manager-remove" data-type="images"><i class="fas fa-times"></i></button>  
        `;
        display.find('.image-manager-item').html(element);
        readURL(this, display.find('img'));
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
        var display = $(this).parents('.image-manager-item');
        display.html(`
                <div class="image-manager-content__upload">
                    <button type="button" class="btn btn-info btn-circle image-manager-upload" data-type="images"><i class="fas fa-plus"></i></button>
                </div>
            `);
        display.parent().find('.input-images').val('');
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
                window.location.href = '{{ route("auctions.index") }}';
            });
            // loader.close();
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
            Swal.fire(`คำเตือน`, message, `error`);
            loader.close();
        }
    });

    $('.input-date').datepicker({
        autoclose:true,
        format:'dd/mm/yyyy',
        language:'th',
        startDate: new Date(),
        maxDate: new Date()
    });
    $('.input-time').inputmask("99:99");

</script>
@endsection
