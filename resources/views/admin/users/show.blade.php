@extends('admin.layouts.main')
@section('title','User') 
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
                <h1>ข้อมูลสมาชิก</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="avatars rounded-circle avatars-xl d-block my-0 mx-auto">
                                    <div class="avatars-one" style="background-image: url( {{ !empty($user->image) ? $user->image : asset('assets/images/img_profile_default.jpg') }} )"></div>
                                </div> 
                                <div class="text-center text-bold mt-2">
                                    <p class="mb-0">รหัส #{{ $user->code }}</p>
                                    <p class="mb-0">{{ $user->full_name() }}</p>
                                    <p class="mb-0">{{ $user->user_type->name }}</p>
                                    <div class="d-block mb-2">
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-warning font-weight-normal">{{ $role->display_name }}</span>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-xs"><i class="far fa-edit"></i> แก้ไขข้อมูล</a>
                                </div>
                            </div>
                            <div class="col-sm-8 row">
                                <div class="col-6 text-center">
                                    <div>
                                        <label class="mb-0">ผู้แนะนำ</label>
                                        @if($user->recommended_by)
                                        <p class="">#{{ $user->recommended_by_user->code.' '.$user->recommended_by_user->first_name.' '.$user->recommended_by_user->last_name }}</p>
                                        @else 
                                        <p>-</p>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="mb-0">อีเมมล์</label>
                                        <p class="">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <label class="mb-0">เบอร์โทรศัพท์</label>
                                        <p class="">{{ $user->phone }}</p>
                                    </div>
                                    <div>
                                        <label class="mb-0">เลขที่บัตรประชาชน</label>
                                        <p class="">{{ $user->id_card_number }}</p>
                                    </div>
                                    {{-- <p class="mb-0">ที่อยู่: -</p> --}}
                                </div>
                                <div class="col-6 text-center">
                                    <div>
                                        <label class="mb-0">ที่อยู่</label>
                                        <p class="">{{$user->full_address()}}</p>
                                    </div>
                                    <div>
                                        <label class="mb-0">วันที่สมัครสมาชิก</label>
                                        <p class="">{{ $createdAt }}</p>
                                    </div>
                                    <div>
                                        <label class="mb-0">วันที่หมดอายุสมาชิก</label>
                                        <p class="">{{ $expiredAt }}</p>
                                        @if($user->isExpire())
                                            <button type="button" class="btn btn-sm btn-warning user-renew"><i class="far fa-calendar-plus mr-2"></i> ต่ออายุสมาชิก 12 เดือน</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($pointBalance->total_amount) }}</h3>
                                <p class="text-center mb-0">เทรดบาทคงเหลือ</p>
                            </div>
                        </div>
                    </div>
                    @if($user->type == 2)
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($user->credit_total_amount) }}</h3>
                                <p class="text-center mb-0">วงเงินเครดิต</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ isset($creditBalance->total_amount) ? number_format($creditBalance->total_amount) : 0 }}</h3>
                                <p class="text-center mb-0">เครดิตคงเหลือ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($user->purchase_fee) }}%</h3>
                                <p class="text-center mb-0">ค่าธรรมเนียมการซื้อ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($user->sales_fee) }}%</h3>
                                <p class="text-center mb-0">ค่าธรรมเนียมการขาย</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($totalAmount['purchase']) }}</h3>
                                <p class="text-center mb-0">การซื้อ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($totalAmount['sales']) }}</h3>
                                <p class="text-center mb-0">การขาย</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3 class="text-center">{{ number_format($totalAmount['income']) }}</h3>
                                <p class="text-center mb-0">วงเงินเครดิต</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">รายงาน</h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-primary btn-sm mr-2 change-report" data-type="purchase">การซื้อ</button>
                                        <button type="button" class="btn btn-default btn-sm mr-2 change-report" data-type="sales">การขาย</button>
                                        <button type="button" class="btn btn-default btn-sm mr-2 change-report" data-type="income">วงเงินเครดิต</button>
                                        <button type="button" class="btn btn-default btn-sm mr-2 change-report" data-type="billing">ค่าธรรมเนียม</button>
                                    </div>
                                </div>      

                            </div>
                            <div class="card-body">
                                <div id="d-report-table">
                                    <table class="table report-table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่</th>
                                                <th>เทรดบาท</th>
                                                <th>บันทึกช่วยจำ</th>
                                                <th>ผู้ขาย</th>
                                                <th>วันที่ทำรายการ</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                
                                <div id="d-billing-table" style="display: none;">
                                    <table class="table billing-table">
                                        <thead>
                                            <tr>
                                                <th>เลขที่</th>
                                                <th>เลขที่การซื้อขาย</th>
                                                <th>จำนวนเทรดบาท</th>
                                                <th>บันทึกช่วยจำ</th>
                                                <th>จำนวนเงิน</th>
                                                <th>สถานะ</th>
                                                <th>วันที่ทำรายการ</th>
                                                <th>การชำระเงิน</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ข้อมูลร้านค้า</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <a href="{{ route('users.shops.create', $user->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> เพิ่มข้อมูลร้านค้า</a>
                            </div>
                        </div>      
                    </div>
                    <div class="card-body">
                        <table class="table shop-table">
                            <thead>
                                <tr>
                                    <th>ชื่อร้านค้า</th>
                                    <th>รายละเอียด</th>
                                    <th>ประเภท</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shops as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->description}}</td>
                                    <td>{{$item->shop_type->name}}</td>
                                    <td>
                                        <a href="{{ route('users.shops.show', [$user->id, $item->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transfer-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">หลักฐานการชำระเงิน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h5 id="transferedAt" class="text-center">เวลาโอนเงิน xxxxx</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8 offset-2">
                        <img id="transferedImg" class="image w-100" src="#" alt="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if( Auth::user()->isRoleAccess('admin') )
                <button type="button" class="btn btn-success mr-auto" id="btn-confirm-payment" data-id="">ยืนยันการชำระเงิน</button> 
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
            </div>
        </div>
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
                d.user_id = {{ $user->id }};
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'remark', name: 'remark' },
            { data: 'user_full_name', name: 'user_full_name' },
            { data: 'created_at', name: 'created_at' },
        ],
        autoWidth: false,
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
        // $('.report-table').show();
        // $('.billing-table').hide();

        var type = $(this).data('type');
        var __url = "{!! route('trades.purchases.data') !!}";
        var textTitle = 'ผู้ขาย';

        if(type == 'sales'){
            __url = "{!! route('trades.sales.data') !!}";
            textTitle = 'ผู้ซื้อ';
        }else if(type == 'income'){
            __url = "{!! route('incomes.data') !!}";
            textTitle = '-';
        }else if(type == 'billing'){
            $('.report-table').closest('#d-report-table').hide();
            $('.billing-table').closest('#d-billing-table').show();
        }

        table.ajax.url(__url);
        var title = table.column( 3 ).header();
        $(title).text(textTitle);
        if(type == 'income'){
            table.column( 3 ).visible(false);
        }else if( type != 'billing'){
            table.column( 3 ).visible(true);
        }

        if( type != 'billing'){
            $('.report-table').closest('#d-report-table').show();
            $('.billing-table').closest('#d-billing-table').hide();
            table.draw();
        }
        
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
            url: "{{ route('users.renew', $user->id) }}",
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

    var billingTable = $('.billing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('billing.data') !!}",
            data: function (d) {
                d.user_id = {{ $user->id }};
            }
        },
        columns: [
            { data: 'id', name: 'id',className: 'text-center' },
            { 
                data: 'trade_id', name: 'trade_id',className: 'text-center',
                render: function (data, type, full, meta){
                    return (full.trade) ? data : '-'
                } 
            },
            { 
                data: 'trade.total_amount', name: 'trade.total_amount',
                render: function (data, type, full, meta){
                    return (full.trade) ? pricceFormat(data) : '-'
                }
            },
            { data: 'remark', name: 'remark' },
            { 
                data: 'total_amount', name: 'total_amount',
                render: function (data, type, full, meta){
                    return pricceFormat(data)
                }
            },
            { 
                data: 'status', name: 'status',
                render: function (data, type, full, meta){
                    if(data == 'paid'){
                        return '<span class="badge badge-success">ชำระเงินเรียบร้อย</span>';
                    }else if( data == 'unpaid'){
                        return '<span class="badge badge-primary">ยังไม่ชำระเงิน</span>';
                    }else if( data == 'pending'){
                        return '<span class="badge badge-warning">รอการตรวจสอบ</span>';
                    }else if( data == 'cancel'){
                        return '<span class="badge badge-danger">ยกเลิก</span>';
                    }
                }
            },
            { data: 'created_at', name: 'created_at' },
            { 
                render: function (data, type, full, meta){
                    if(full.status == 'pending' || full.status == 'paid'){
                        return `<button type="button" class="btn btn-primary btn-sm btn-transfer-modal" data-id="${full.id}" data-image="${full.transfered_img}" data-transfered="${full.transfered_at}">หลักฐาน</button>`;
                    }
                    return '-'
                }
            },
        ],
        order:[[ 0, "desc" ]],
        autoWidth: false,
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        drawCallback: function (settings) {
            if (!$dt.parent().hasClass("table-responsive")) {
                $dt.wrap("<div class='table-responsive'></div>");
            }
            loader.close();
        },
    })

    $(document).on('click', '.btn-transfer-modal', function(e){
        var __id = $(this).data('id');
        var __url = `{{ route('billing.show','__id') }}`;
        __url = __url.replace('__id', __id);
        $.ajax({
            url: __url,
            method: "GET",
            beforeSend: function( xhr ) {
                $('#btn-confirm-payment').removeClass('d-none');
                $('#transfer-modal').modal('hide');
                loader.init();
            }
        }).done(function(data){
            var transfered_at = '';
            if(data.transfered_at){ transfered_at = moment(data.transfered_at).add(543,'years').format('DD/MM/YYYY h:mm'); }
            $('#transferedAt').html(`เวลาโอนเงิน ${transfered_at}`);
            $('#transferedImg').attr('src', data.transfered_img);
            $('#btn-confirm-payment').attr('data-id', data.id);
            $('#transfer-modal').modal('show');
            if(data.status != 'pending'){
                $('#btn-confirm-payment').addClass('d-none');
            }
            loader.close();
        }).fail(function( jqxhr, textStatus ) {
            var message = jqXHR.responseJSON.message
            Swal.fire(`Error: ${message}`, ``, `error`);
            loader.close();
        });
    });


    
</script>
@endsection
