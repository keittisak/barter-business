@extends('admin.layouts.main')
@section('title','Shop') 
@section('css')
<style>
    .info-box .info-box-text{
        overflow: unset;
    }
    .show-slip{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>ข้อมูลร้านค้า</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('users.shops.edit', [$userID, $shop->id]) }}"" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> แก้ไขร้านค้า</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        @if( !empty($shop) )
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row no-gutters">
                        <aside class="col-sm-6 border-right">
                            <article class="gallery-wrap"> 
                                <div id="carouselIndicators" class="carousel slide  w-100 carousel-store" data-ride="carousel" data-interval="false">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="img-big-wrap">
                                                <a><img src="{{ $shop->image }}"></a>
                                            </div>
                                        </div>
                                        @for( $i=0; $i < count($shop->images); $i++ )
                                            <div class="carousel-item">
                                                <div class="img-big-wrap">
                                                    <a><img src="{{ $shop->images[$i]->image }}"></a>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <ol class="carousel-indicators"  style="position: initial">
                                        <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
                                        @for( $i=1; $i <= count($shop->images); $i++ )
                                            <li data-target="#carouselIndicators" data-slide-to="{{ $i }}" ></li>
                                        @endfor
                                    </ol>
                                </div>
                            </article>
                        </aside>
                        
                        <main class="col-sm-6">
                            <article class="content-body">
                                <h3 class="title">{{ $shop->name }}</h3>
                                <div class="rating-wrap mb-3">
                                    <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ !empty($shop->province) ? $shop->province->name.', ' : '' }}{{ !empty($shop->country) ? $shop->country->name : 'ประเทศไทย' }}</small>
                                </div>
                                <h5 class="title-section text-r18 text-muted">รายละเอียด</h5>
                                <p>{{ $shop->description }}</p>
                                <h5 class="title-section text-r18 text-muted">ประเภทร้านค้า</h5>
                                <p>{{ $shop->shop_type->name }}</p>
                                <h5 class="title-section text-r18 text-muted">ข้อมูลติดต่อ</h5>
        
                                <p class="m-0 pb-1"><i class="fas fa-user text-muted mr-2"></i>{{ $user->first_name.' '.$user->last_name }}</p>
                                <p class="m-0 pb-1"><i class="fas fa-mobile-alt text-muted mr-2"></i>{{ $user->phone }}</p>
                                <p class="m-0 pb-1"><i class="fas fa-map-marker-alt text-muted mr-2"></i>{{ $shop->full_address }}</p>
                            </article>
                            <div class="px-4">
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if( !empty($shop) )
            <div class="row mt-4 mb-2">
                <div class="col-md-6">
                    <h4>สินค้า</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('products.create', [$userID, $shop->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> เพิ่มข้อมูลสินค้า</a>
                </div>
            </div>
            <div class="row">
                @foreach ($products as $product)
                <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                    <div class="card card-sm card-product-grid">
                        @if($product->status == 'sold_out')
                        <div class="card-img-overlay justify-content-end badge-sale">
                            <a class="badge badge-warning float-right">SOLD OUT</a>
                        </div>
                        @endif
                        <div class="img-wrap">
                            <img src="{{ $product->image }}">
                        </div>
                        <div class="card-body px-2 pt-2">
                            <h5 class="product-title text-r16 text-center font-weight-bold">{{ $product->name }}</h3>
                            <div class="product-price text-center mt-3">{{ number_format($product->price) }}</div>
                        </div>
                        <div class="card-footer p-0">
                            <a href="{{ route('products.edit', [$userID, $shop->id,$product->id]) }}" class="btn btn-link btn-block bg-default border-topx-radius-0 "><i class="far fa-edit"></i> แก้ไข</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@include('admin.layouts.slip_modal')
@endsection
@section('javascript')
<script>
    var $dt = $('.report-table');
    var table;
    var datatableJson = {
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('trades.purchases.data') !!}",
            data: function (d) {
                d.user_id = {{ $userID }};
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'remark', name: 'remark' },
            { data: 'user_full_name', name: 'user_full_name' },
            { data: 'created_at', name: 'created_at' },
        ],
        order:[[ 0, "desc" ]],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        columnDefs : [
            { className: "text-nowrap", targets : [ 0 ] },
            {
                targets:0,
                render: function (data, type, full, meta){
                    return `<a class="text-blue btn-link show-slip" data-json='${JSON.stringify(full)}'><i class="fas fa-file-invoice"></i> #${data}</a>`;
                }
            }
        ],
        // searching: false,
        // paging: false,
        // info: false,
        drawCallback: function (settings) {
            if (!$dt.parent().hasClass("table-responsive")) {
                $dt.wrap("<div class='table-responsive'></div>");
            }
            loader.close();
        },
    }
    $( document ).ready(function(){
        table = $dt.DataTable(datatableJson);
    });

    $('.change-report').on('click',function(e){
        var type = $(this).data('type');
        var __url = "{!! route('trades.purchases.data') !!}";
        var textTitle = 'ผู้ขาย';

        if(type == 'sales'){
            __url = "{!! route('trades.sales.data') !!}";
            textTitle = 'ผู้ซื้อ';
        }else if(type == 'income'){
            __url = "{!! route('incomes.data') !!}";
            textTitle = '-';
        }

        table.ajax.url(__url);
        var title = table.column( 3 ).header();
        $(title).text(textTitle);
        if(type == 'income'){
            table.column( 3 ).visible(false);
        }else {
            table.column( 3 ).visible(true);
        }
        table.draw();
        $('.change-report').removeClass('btn-primary').addClass('btn-default');
        $(this).addClass('btn-primary').removeClass('btn-default');
    });

    $(document).on('click','.show-slip', function(e){
        var data = $(this).data('json');
        var element = ``;
        var img_bbg = "{{ asset('assets/images/'.env('LOGO_IMAGE_160')) }}";
        var img_default = "{{ asset('assets/images/img_profile_default.jpg') }}";
        var img_buyer = ``;
        var name_buyer = ``;
        var code_buyer = ``;
        var img_seller = ``;
        var name_seller = ``;
        var code_seller = ``;
        if(data.buyer_by_user == undefined){
            img_buyer = img_bbg;
            name_buyer = 'BA system';
            code_buyer = '&nbsp;';
        }else if(data.buyer_by_user != undefined && data.buyer_by_user.image != null){
            img_buyer = data.buyer_by_user.image ;
        }else{
            img_buyer = img_default;
            name_buyer = data.buyer_by_user.first_name+' '+data.buyer_by_user.last_name;
            code_buyer = `รหัสผู้ซื้อ  #${data.buyer_by_user.code}`;
        }

        if(data.seller_by_user == undefined){
            img_seller = (data.user.image == null)?img_default:data.user.image;
            name_seller = data.user.first_name+' '+data.user.last_name;
            code_seller = `รหัสผู้ขาย  #${data.user.code}`;
        }else if(data.seller_by_user != undefined && data.seller_by_user.image != null){
            img_seller = data.buyer_by_user.image ;
        }else{
            img_seller = img_default;
            name_seller = data.seller_by_user.first_name+' '+data.seller_by_user.last_name;
            code_seller = `รหัสผู้ขาย  #${data.seller_by_user.code}`;
        }
        code = data.code;
        element = `
            <h5 class="text-center text-green">Barter Advance</h5>
            <p class="text-center text-muted text-r14">รหัสอ้างอิง #${code.toUpperCase()}</p>
            <div class="user-block d-inline-block w-100 mt-4">
                <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                    <div class="avatars-one" style="background-image: url(${img_buyer})"></div>
                </div>
                <span class="username">${name_buyer}</span>
                <span class="description">${code_buyer}</span>
            </div>
            <span class="icon-transfer-down">
                <i class="fas fa-long-arrow-alt-down"></i>
            </span>
            <div class="user-block d-inline-block w-100">
                <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                    <div class="avatars-one" style="background-image: url(${img_seller})"></div>
                </div>
                <span class="username">${name_seller}</span>
                <span class="description">${code_seller}</span>
            </div>
            <ul class="list-unstyled list-inline mt-4 mb-1">
                <li class="d-inline">จำนวนเทรดบาท</li>
                <li class="d-inline float-right"><span class="h5">${data.total_amount}</span> เทรดบาท</li>
            </ul>
            <ul class="list-unstyled list-inline">
                <li class="d-inline">วันที่ทำรายการ</li>
                <li class="d-inline float-right">${data.created_at}</li>
            </ul>
            <ul class="list-unstyled list-inline">
                <li class="">บันทึกช่วยจำ</li>
                <li class="">${data.remark}</li>
            </ul>
        `;
        $('#slip-modal').find('.modal-body').html(element);
        $('#slip-modal').modal('show');
    });

    $('.user-renew').on('click',function(e){
        $.ajax({
            url: "{{ route('users.renew', $userID) }}",
            method: "POST",
            data:{
                    _token:'{{ csrf_token() }}',
                    _method:'PUT',
                },
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                location.reload();
            });
        }).fail(function( jqxhr, textStatus ) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            Swal.fire(message, ``, `error`);
            loader.close();
        });
    });



    
</script>
@endsection
