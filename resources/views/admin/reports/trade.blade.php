@extends('admin.layouts.main')
@section('title','Trade report') 
@section('css')
<style>
    .dataTables_filter {
        display: none;
    }
    #DataTables_Table_0_filter {
        display: none;
    }
    #approve-modal .table td {
        border-top: none;
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>รายงานการซื้อขาย</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">รายงานการซื้อขายทั้งหมด</h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="created_at" id="created_at" placeholder="วันที่ทำรายการ">
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control text-search" name="text_search" id="text-search" placeholder="ค้นหาข้อมูล ...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary btn-search"><i class="fas fa-search mr-2"></i> ค้นหา</button>
                        </div>
                    </div>
                
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="table">
                                <thead>
                                    <tr>
                                        <th>เลขที่</th>
                                        <th>จำนวนเทรดบาท</th>
                                        <th class="w-25">บันทึกช่วยจำ</th>
                                        <th>ผู้ขาย</th>
                                        <th>ผู้ซื้อ</th>
                                        <th>วันที่ทำรายการ</th>
                                    </tr>
                                </thead>
                            </table>
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
    var $dt = $('#table');
    var table;
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('reports.trade.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                if(created_at != ""){
                    d.created_at = created_at
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'remark', name: 'remark' },
            { data: 'created_by_user_full_name', name: 'created_by_user_full_name' },
            { data: 'transaction_by_user_full_name', name: 'transaction_by_user_full_name' },
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
                targets:3,
                render: function (data, type, full, meta){
                    var __url = "{{ route('users.show','__id') }}";
                    __url = __url.replace('__id', full.created_by);
                    if(full.transferred_to_user){
                        return `<a href="${__url}" target="_blank">${data}</a>`;
                    }
                    return '-';
                }
            },
            {
                targets:4,
                render: function (data, type, full, meta){
                    var __url = "{{ route('users.show','__id') }}";
                    __url = __url.replace('__id', full.transferred_to);
                    if(full.transferred_to_user){
                        return `<a href="${__url}" target="_blank">${data}</a>`;
                    }
                    return '-';
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

    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        table.search(text).draw();
    });
</script>
@endsection
