@extends('admin.layouts.main')
@section('title','Members') 
@section('css')
<style>
    #DataTables_Table_0_filter {
        display: none;
    }
    .dataTables_length, .dataTables_info{
        margin-top: 1rem!important;
        padding-left: 1.5rem!important;
    }
    .dataTables_paginate{
        margin-top: 1rem!important;
        padding-right: 1.5rem!important;
    }
    .dataTables_filter {
        display: none;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>สมาชิก</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">สมาชิกทั้งหมด</h3>
            </div>
            <div class="card-body px-0">
                <div class="px-4 mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="created_at" id="created_at" placeholder="วันที่สมัคร">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="expired_at" id="expired_at" placeholder="วันที่หมดอายุ">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control text-search" name="text_search" id="text-search" placeholder="ค้นหาข้อมูล ...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary btn-search"><i class="fas fa-search mr-2"></i> ค้นหา</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>รหัส</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>ประเภท</th>
                                    <th>ผู้แนะนำ</th>
                                    <th>ร้านค้า</th>
                                    <th>เทรดบาทคงเหลือ</th>
                                    <th>วงเงินเครดิต</th>
                                    <th>เครดิตคงเหลือ</th>
                                    <th>วันที่หมดอายุ</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script>
    var $dt = $('#table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('users.data') !!}",
            data: function (d) {
                var status = $('#status').val();
                var expired_at = $('#expired_at').val();
                var created_at = $('#created_at').val();
                if(status != 'all'){
                    d.status = status;
                }
                if(expired_at != "") {
                    d.expired_at = expired_at
                }
                if(created_at != ""){
                    d.created_at = created_at
                }
            }
        },
        columns: [
            { data: 'code', name: 'code' },
            { data: 'first_name', name: 'first_name' },
            { data: 'type', name: 'type' },
            { data: 'recommended_by', name: 'recommended_by' },
            { data: 'shop_name', name: 'shop_name'},
            { data: 'total_amount', name: 'total_amount'},
            { data: 'credit_total_amount', name: 'credit_total_amount'},
            { data: 'credit_balance_amount', name: 'credit_balance_amount' },
            { data: 'expired_at', name: 'expired_at' },
        ],
        order: [[ 0, "desc" ]],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        columnDefs : [
            { className: "text-nowrap", targets : [ 0 ] },
            { className: "text-right", targets : [ 5,6,7 ] },
            {
                targets:2,
                render: function (data, type, full, meta){
                    return full.user_type.name;
                }
            },
            {
                targets:3,
                render: function (data, type, full, meta){
                    if(full.recommended_by_user){
                        return `#${full.recommended_by_user.code} ${full.recommended_by_user.first_name} ${full.recommended_by_user.last_name}`
                    }
                    return `-`;
                }
            },
            {
                targets:5,
                render: function (data, type, full, meta){
                    return pricceFormat(data);
                }
            },
            {
                targets:6,
                render: function (data, type, full, meta){
                    return pricceFormat(data);
                }
            },
            {
                targets:7,
                render: function (data, type, full, meta){
                    return pricceFormat(data);
                }
            },
            {
                targets:9,
                render: function (data, type, full, meta){
                    var __url = "{{ route('users.show','__id') }}";
                    __url= __url.replace('__id', full.id);
                    return `
                    <a href="${__url}" class="btn btn-primary btn-sm mr-2"><i class="far fa-edit"></i></a>
                    `;
                }
            },
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
    });

    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        table.search(text).draw();
    });
    $('input[name="created_at"]').daterangepicker({
        alwaysShowCalendars: true,
        applyButtonClasses: "btn-success",
        autoUpdateInput: false,
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 วันล่าสุด': [moment().subtract(6, 'days'), moment()],
           '30 วันล่าสุด': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
    },function(start, end, label) {
        var daterange = start.format('DD/MM/YYYY')+' - '+ end.format('DD/MM/YYYY');
        $('input[name="created_at"]').val(daterange);
    });
    $('input[name="created_at"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('input[name="expired_at"]').daterangepicker({
        alwaysShowCalendars: true,
        applyButtonClasses: "btn-success",
        autoUpdateInput: false,
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 วันล่าสุด': [moment().subtract(6, 'days'), moment()],
           '30 วันล่าสุด': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
    },function(start, end, label) {
        var daterange = start.format('DD/MM/YYYY')+' - '+ end.format('DD/MM/YYYY');
        $('input[name="expired_at"]').val(daterange);
    });
    $('input[name="expired_at"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>
@endsection
