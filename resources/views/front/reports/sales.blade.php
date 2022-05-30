@extends('front.layouts.main')
@section('title','Incoming Report') 
@section('css')
<style>
    #DataTables_Table_0_filter {
        display: none;
    }
    .btn-express-date {
        /* margin-right: .5rem!important; */
        margin-bottom: 1rem!important;
    }
    .table tr {
        cursor: pointer;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ __('รายงานการขาย') }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0 pb-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2 col-12">
                        <div class="px-3 pt-4">
                            <button class="btn btn-success btn-express-date btn-sm" data-type="today">วันนี้</button>
                            <button class="btn btn-success btn-express-date btn-sm" data-type="yesterday">เมื่อวาน</button>
                            <button class="btn btn-success btn-express-date btn-sm" data-type="seven_day">7 วัน</button>
                            <button class="btn btn-success btn-express-date btn-sm" data-type="this_month">เดือนนี้</button>
                            <button class="btn btn-success btn-express-date btn-sm" data-type="last_mouth">เดือนที่แล้ว</button>
                            <button class="btn btn-success btn-express-date btn-sm" data-type="all">ทั้งหมด</button>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-prepend" id="basic-addon1">
                                        <span class="input-group-text">วันที่</span>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="start-date" value="{{ date( 'd/m/Y', strtotime('first day of this month') ) }}">
                                    <span class="input-group-prepend" id="basic-addon1">
                                        <span class="input-group-text">ถึง</span>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="end-date" value="{{ date( 'd/m/Y', strtotime('last day of this month') ) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-success btn-search"><i class="fas fa-search mr-2"></i> ค้นหา</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="text-center text-muted"><i class="fas fa-exchange-alt"></i></h5>
                <div class="table-responsive p-0">
                    <table class="table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th class="text-nowrap">เลขที่</th>
                                <th>เทรดบาท</th>
                                <th class="text-nowrap">บันทึกช่วยจำ</th>
                                <th class="text-nowrap">ผู้ซื้อ</th>
                                <th class="text-nowrap">สถานะ</th>
                                <th class="text-nowrap">วันที่ทำรายการ</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('front.reports.slip_modal')
@endsection

@section('javascript')
<script>
    var $dt = $('.table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('front.users.reports.sales.data') !!}",
            data: function (d) {
                var startDate = $('#start-date').val();
                var endDate = $('#end-date').val();
                if(startDate != "" && endDate != ""){
                    d.start_date = startDate;
                    d.end_date = endDate;
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'remark', name: 'remark', orderable: false },
            { data: 'buyer_full_name', name: 'buyer_full_name' },
            { data: 'status', name: 'status' },
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
                    return `<a class="text-blue btn-link"><i class="fas fa-file-invoice"></i> #${data}</a>`;
                }
            },
            {
            targets:4,
                render: function (data, type, full, meta){
                    if(data == 'success'){
                        return '<span class="badge badge-success">สำเร็จ</span>';
                    }else if( data == 'cancel'){
                        return '<span class="badge badge-danger">ยกเลิก</span>';
                    }
                }
            },
        ],
        // searching: false,
        paging: false,
        info: false,
        drawCallback: function (settings) {
            if (!$dt.parent().hasClass("table-responsive")) {
                $dt.wrap("<div class='table-responsive'></div>");
            }
            loader.close();
        },
    });

    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        table.search(text).draw();
    });

    $('.btn-express-date').on('click',function(e){
        var type = $(this).data('type');
        var startDate;
        var endDate;
        if(type == 'today'){
            startDate = moment();
            endDate = moment();
        }else if(type == 'yesterday'){
            startDate = moment().subtract(1, 'days');
            endDate = moment().subtract(1, 'days');
        }else if(type == 'this_month'){
            startDate = moment().startOf('month');
            endDate = moment().endOf("month");
        }else if(type == 'last_mouth'){
            startDate = moment().subtract(1, 'months').startOf('month');
            endDate = moment().subtract(1, 'months').endOf("month");
        }else if(type == 'seven_day'){
            startDate = moment().subtract(7, 'days');
            endDate = moment();
        }
        $('#start-date').val(startDate.format('DD/MM/YYYY'));
        $('#end-date').val(endDate.format('DD/MM/YYYY'));
        $('.btn-search').click();
    });

    $('#start-date').datepicker({
        autoclose:true,
        format:'dd/mm/yyyy',
        language:'th',
        setDate: new Date()
    });

    $('#end-date').datepicker({
        autoclose:true,
        format:'dd/mm/yyyy',
        language:'th',
        setDate: new Date()
    });

    $(document).on('click','.table tr', function(e){
        var element = ``;
        var code = $(this).data('code');
        var _url = '{{ route("front.users.trade.show", "_code") }}';
        code = btoa(code);
        _url = _url.replace('_code', code);
        $.ajax({
            url: _url,
            method: "GET",
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            var img_default = "{{ asset('assets/images/img_profile_default.jpg') }}";
            code = data.code;
            element = `
                <h5 class="text-center text-green">Barter Advance</h5>
                <p class="text-center text-muted text-r14">รหัสอ้างอิง #${code.toUpperCase()}</p>
                <div class="user-block d-inline-block w-100 mt-4">
                    <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                        <div class="avatars-one" style="background-image: url(${ (data.buyer_by_user == null || data.buyer_by_user.image == null) ? img_default : data.buyer_by_user.image  })"></div>
                    </div>
                    <span class="username">${ (data.buyer_by_user == null) ? 'BA system' : data.buyer_by_user.first_name+' '+data.buyer_by_user.last_name }</span>
                    <span class="description">${ (data.buyer_by_user == null) ? '&nbsp;' : 'รหัสผู้ซื้อ  #'+data.buyer_by_user.code }</span>
                </div>
                <span class="icon-transfer-down">
                    <i class="fas fa-long-arrow-alt-down"></i>
                </span>
                <div class="user-block d-inline-block w-100">
                    <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                        <div class="avatars-one" style="background-image: url(${ (data.seller_by_user.image == null) ? img_default : data.seller_by_user.image })"></div>
                    </div>
                    <span class="username">${ data.seller_by_user.first_name+' '+data.seller_by_user.last_name }</span>
                    <span class="description">รหัสผู้ขาย  #${ data.seller_by_user.code }</span>
                </div>
                <ul class="list-unstyled list-inline mt-4 mb-1">
                    <li class="d-inline">จำนวนเทรดบาท</li>
                    <li class="d-inline float-right"><span class="h5">${utilities.numberFormat(data.total_amount,0)}</span> เทรดบาท</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="d-inline">วันที่ทำรายการ</li>
                    <li class="d-inline float-right">${moment(data.created_at).add(543, 'years').format('DD/MM/YYYY H:mm')}</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="">บันทึกช่วยจำ</li>
                    <li class="">${data.remark}</li>
                </ul>
            `;
            $('#slip-modal').find('.modal-body').html(element);
            $('#slip-modal').modal('show');
            loader.close();
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            loader.close();
        });
    });
</script>
@endsection