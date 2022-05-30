@extends('front.layouts.main')
@section('title','Billing') 
@section('css')
<style>
    #DataTables_Table_0_filter {
        display: none;
    }
    .btn-express-date {
        /* margin-right: .5rem!important; */
        margin-bottom: 1rem!important;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ __('ค่าธรรมเนียม') }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5  class="card-title">ช่องทางการชำระเงิน</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">ธนาคาร : กรุงศรี</p>
                <p class="mb-0">เลยที่บัญชี : 619-1-20680-0</p>
                <p class="mb-0">ชื่อ : นายบริพัฒน์ วงษ์อุปรี</p>
            </div>
        </div>
    </div>
</div>
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
                                    <input type="text" class="form-control datepicker" id="start-date" value="">
                                    <span class="input-group-prepend" id="basic-addon1">
                                        <span class="input-group-text">ถึง</span>
                                    </span>
                                    <input type="text" class="form-control datepicker" id="end-date" value="">
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
                                <th>เลขที่</th>
                                <th>จำนวนเงิน</th>
                                <th class="w-25">หมายเหตุ</th>
                                <th>สถานะ</th>
                                <th>วันที่ทำรายการ</th>
                                <th></th>
                            </tr>
                        </thead>
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
                <h5 class="modal-title">หลักฐานการขำระเงิน</h5>
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
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    //  $('input[name="time_transfer"]').inputmask({mask:'00:00'})
    var $dt = $('.table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('front.billing.data') !!}",
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
            { data: 'remark', name: 'remark' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
        ],
        order: [[ 0, "desc" ]],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        columnDefs : [
            { className: "text-nowrap", targets : [ 0 ] },
            // {
            //     targets:0,
            //     render: function (data, type, full, meta){
            //         return `<a class="text-blue btn-link"><i class="fas fa-file-invoice"></i> #${data}</a>`;
            //     }
            // },
            {
                targets:3,
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
            {
                targets:5,
                render: function (data, type, full, meta){
                    
                    if(full.status == 'unpaid'){
                        var __url = '{{ route("front.billing.payment.form", "__id") }}';
                        __url = __url.replace('__id', full.id);
                        return `<a href="${__url}" class="btn btn-primary btn-sm">แจ้งการชำระเงิน</a>`;                    
                    }else if(full.status == 'pending' || full.status == 'paid'){
                        return `<button type="button" class="btn btn-info btn-sm btn-transfer-modal" data-image="${full.transfered_img}" data-transfered="${full.transfered_at}">หลักฐาน</button>`;
                    }
                    return '';
                }
            }
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
        } else if(type == 'all') {
            startDate = '';
            endDate = '';
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

    $(document).on('click', '.btn-transfer-modal', function(e){
        var transferedAt = $(this).data('transfered');
        var image = $(this).data('image');
        $('#transferedAt').html(`เวลาโอนเงิน ${transferedAt}`);
        $('#transferedImg').attr('src', image);
        $('#transfer-modal').modal('show');
    });

</script>
@endsection