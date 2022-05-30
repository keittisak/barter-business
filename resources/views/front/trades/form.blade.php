@extends('front.layouts.main')
@section('title','Point transfer') 
@section('css')
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">โอนเทรดบาท</h5>
@endsection

@section('content')
{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="shop-form-search" action="{{ route('front.users.shops.search') }}" method="GET">
                    @csrf
                    <div class="form-group">
                        <label for="shop-search">ค้นหาร้านค้า</label>
                        <div class="input-group">
                            <input type="text" name="text" class="form-control" placeholder="รหัสร้านค้า / เบอร์โทรศัพท์" id="shop-text-search" value="{{ isset($product) ? $product->shop->code : '' }}">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-outline-success bg-green" id="shop-btn-search">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0" style="max-height: 18.75rem">
                    <table id="shop-table" class="table table-valign-middle">
                        @if( isset($product) )
                        <tr>
                            <td>
                                <label class="info-box category-box box-shadow-none mb-0" for="shop-{{$product->shop->id}}">
                                    <span class="info-box-icon" style="width: 3rem;">
                                        <img src="{{$product->shop->image}}">
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted text-r14">#{{$product->shop->code}}</span>
                                        <span class="info-box-text text-r14">{{$product->shop->name}}</span>
                                    </div>
                                </label>
                            </td>
                            <td class="align-middle">
                                @if(\Auth::user()->id  != $product->shop->user_id)
                                    <input type="radio" name="shop_id" id="shop-{{$product->shop->id}}" value="{{$product->shop->id}}" data-user-id="{{$product->shop->user_id}}" data-name="{{$product->shop->name}}" data-image="{{$product->shop->image}}" checked>
                                @endif                              
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@if (Auth::user()->isExpire())
<div class="row">
    <div class="col-12 text-center">
        <h4><span class="text-red">*</span> สมาชิกหมดอายุ</h4>
        <h4>เมื่อวันที่ {{date('d/m/Y H:i', strtotime('+543 year', strtotime(Auth::user()->expired_at)))}}</h4>
        <h4>กรุณาติดต่อพนักงานหรือชำระค่าธรรมเนียมการต่ออายุสมาชิก</h4>
    </div>
</div>
@elseif($countBill)
<div class="col-12 text-center">
    <h4><span class="text-red">*</span> ขออภัยในความไม่สะดวกเนื่องจากสมาชิกค้างชำระค่าธรรมเนียมเกิน 2 เดือน กรุณาติดต่อพนักงานหรือชำระค่าธรรมเนียม</h4>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5  class="card-title"><i class="fas fa-sync-alt text-muted mr-2"></i>โอนเทรดบาท</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        {{-- <div id="shop-checked-box"></div> --}}
                        <p class="mb-0 pb-0"><span class="text-red">*</span> รหัสผู้ซื้อ #{{ Auth::user()->code }}</p>
                        <p>{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</p>
                        <hr>
                        {{-- <div class="form-group">
                            <label for="seller_code"><span class="text-red">*</span> {{ __('รหัสผู้ขาย') }}</label>
                            <input type="number" name="seller_code" id="seller_code" class="form-control" value="{{ isset($product) ? $product->created_by : '' }}">
                        </div> --}}
                        {{-- <label for="seller_code"><span class="text-red">*</span> {{ __('รหัสผู้ขาย') }}</label> --}}
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                             <span class="input-group-text bg-transparent"><span class="far fa-user text-muted"></span></span>
                            </div>
                                <input type="number" name="seller_code" id="seller_code" class="form-control" value="{{ isset($product) ? $product->created_by : '' }}" placeholder="รหัสผู้ขาย">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" id="btn-show-seller-name">แสดงชื่อผู้ขาย</button>
                            </span>
                        </div>
                        <p id="text-show-seller-name" style="display: none"></p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent text-gold"><i class="fab fa-google"></i></span>
                            </div>
                            <input type="number" class="form-control" name="total_amount" id="total_amount" placeholder="จำนวนเทรดบาท" autocomplete="off" value="{{ isset($product) ? $product->price : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="remark">บันทึกช่วยจำ</label>
                            <textarea name="remark" id="remark" rows="2" class="form-control">{{ isset($product) ? 'สั่งซื้อสินค้า '.$product->name : '' }}</textarea>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent"><span class="fas fa-lock text-muted"></span></span>
                            </div>
                            <input type="password" class="form-control" name="current_password" id="current_password" placeholder="ยืนยันรหัสผ่าน">
                        </div>
                        <button type="button" class="btn btn-success btn-block bg-green btn-transfer mt-4 mb-1">ยืนยันโอนเทรดบาท</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('javascript')
<script>
    $('.btn-transfer').on('click', function(e){
        submitTransfer();
    });
    function submitTransfer() {
        // var user_id = $("input[name=shop_id]:checked").attr('data-user-id');
        var seller_code = $("input[name=seller_code]").val();
        var total_amount = $("input[name=total_amount]").val();
        var current_password = $("input[name=current_password]").val();
        var remark = $("#remark").val();
        $.ajax({
            url: "{{ route('front.users.trade.store') }}",
            method: "POST",
            data:{
                    seller_code:seller_code,
                    total_amount:total_amount,
                    remark:remark,
                    current_password:current_password,
                    _token:'{{ csrf_token() }}'
                },
            beforeSend: function( xhr ) {
                $('.invalid-feedback').remove();
                $('.form-control').removeClass('is-invalid');
                loader.init();
            }
        }).done(function(data){
            var _url = '{{ route("front.users.trade.slip", "_code") }}';
            var code = btoa(data.code);
            _url = _url.replace('_code', code);
            Swal.fire({
                icon: 'success',
                title: 'โอนเทรดบาทเรียบร้อย',
            }).then(function(){
                window.location.href = _url;
            });
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    if( key == 'seller_code' || key == 'total_amount') {
                        Swal.fire(v[i], ``, `error`);
                    }
                    $(`#${key}`).parent('.input-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            loader.close();
        });
    }
    
    $('#shop-form-search').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
            $('#shop-checked-box').html('');
        },
        success: function (res) {
            var element = ``;
            if( res.length ) {
                var user_id_tmp = {{ \Auth::user()->id }};
                $.each(res,function(i,v){
                    element += `
                        <tr>
                            <td>
                                <label class="info-box category-box box-shadow-none mb-0" for="shop-${v.id}">
                                    <span class="info-box-icon" style="width: 3rem;">
                                        <img src="${v.image}">
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted text-r14">#${v.code}</span>
                                        <span class="info-box-text text-r14">${v.name}</span>
                                    </div>
                                </label>
                            </td>
                            <td class="align-middle">
                               ${(v.user_id != user_id_tmp) ?
                                    `<input type="radio" name="shop_id" id="shop-${v.id}" value="${v.id}" data-user-id="${v.user_id}" data-name="${v.name}" data-image="${v.image}">`
                                    : ``
                                }                                
                            </td>
                        </tr>
                    `;
                });
            } else {
                element = `<tr><td><h5 class="text-center">ไม่พบร้านค้า</h5></td></tr>`;
            }

            $('#shop-table').html(element);
            loader.close();
        },
        error: function (data, status, options, $form) {
            var errors =  jqXHR.responseJSON.errors
            loader.close();
        }
    });

    $(document).on('change', 'input[name=shop_id]', function(e){
        var shop_name = $(this).data('name');
        var shop_image = $(this).data('image');
        var element = `                            
            <div class="info-box category-box box-shadow-none">
                <span class="info-box-icon" style="width: 3rem !important;">
                    <img src="${shop_image}">
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">${shop_name}</span>
                </div>
            </div>
        `;
        $('#shop-checked-box').html(element);
    });

    $('#seller_code').on('keyup', function(e){
        $('#text-show-seller-name').hide();
    });

    $('#btn-show-seller-name').on('click',function(e){
        var seller_code = $("input[name=seller_code]").val();
        $('#text-show-seller-name').hide();
        if( seller_code == ""){ return false; }

        $.ajax({
            url: "{{ route('front.users.search') }}",
            method: "POST",
            data:{
                    code:seller_code,
                    _token:'{{ csrf_token() }}'
                },
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            console.log(data);
            var userName = `ไม่พบรหัสผู้ขายนี้`;
            if( data != ""){
                var userName = 'ชื่อผู้ขาย: '+data.first_name+' '+data.last_name
            }
            $('#text-show-seller-name').text(userName);
            $('#text-show-seller-name').show();
            loader.close();
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message;
            Swal.fire(`Error: ${message}`, ``, `error`);
            loader.close();
        });
    });
</script>
@endsection