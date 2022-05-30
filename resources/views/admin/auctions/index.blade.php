@extends('admin.layouts.main')
@section('title','Auctions') 
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
            <div class="col-md-6">
                <h1>การประมูลสินค้า</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('auctions.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> เพิ่มประมูลสินค้า</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">การประมูลสินค้าทั้งหมด</h3>
            </div>
            <div class="card-body px-0">
                <div class="px-4 mb-4">
                    <div class="row">
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">สถานะ</span>
                                    </div>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">ทั้งหมด</option>
                                        <option value="unpaid">ยังไม่ชำระเงิน</option>
                                        <option value="paid">ชำระเงินเรียบร้อย</option>
                                        <option value="cancel">ยกเลิก</option>
                                    </select>                                
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="created_at" id="created_at" placeholder="วันที่ทำรายการ">
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-8 col-lg-3">
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
                                    <th>เลขที่</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>รายละเอียด</th>
                                    <th>ราคา</th>
                                    <th>Bid ขั้นต่ำ</th>
                                    <th>ผู้ชนะ</th>
                                    <th>ราคาล่าสุด</th>
                                    <th>วันที่เริ่มประมูล</th>
                                    <th>วันที่จบการประมูล</th>
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
    var table;
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('auctions.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                var status = $('#status').val();
                if(created_at != ""){
                    d.created_at = created_at
                }
                if(status != ""){
                    d.status = status;
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'price', name: 'price' },
            { data: 'min_bid', name: 'min_bid' },
            { data: 'winner', name: 'winner' },
            { data: 'latest_price', name: 'latest_price' },
            { data: 'started_at', name: 'started_at' },
            { data: 'expired_at', name: 'expired_at' },
        ],
        order: [[ 0, "desc" ]],
        columnDefs : [
            { className: "text-nowrap", targets : [ 0,9 ] },
            {
                targets:3,
                render: function (data, type, full, meta){
                    return pricceFormat(data);
                }
            },
            {
                targets:4,
                render: function (data, type, full, meta){
                    return pricceFormat(data);
                }
            },
            {
                targets:5,
                render: function (data, type, full, meta){
                    if(data == null){
                        return "";
                    }
                    return full.winner_by_user.first_name+' '+full.winner_by_user.last_name;
                }
            },
            {
                targets:6,
                render: function (data, type, full, meta){
                    if(full.details[0] != undefined){
                        return pricceFormat(full.details[(full.details).length-1].amount);
                    }
                    return pricceFormat(data);
                }
            },
            {
                targets:9,
                render: function (data, type, full, meta){
                    var __url_show = "{{ route('auctions.show','__id') }}";
                    __url_show = __url_show.replace('__id', full.id);
                    var __url_edit = "{{ route('auctions.edit','__id') }}";
                    __url_edit = __url_edit.replace('__id', full.id);
                    return `
                    <a href="${__url_show}" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></a>
                    <a href="${__url_edit}" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
                    <a class="btn btn-danger btn-sm btn-delete" data-id="${full.id}"><i class="far fa-trash-alt"></i></a>
                    `;
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

    $(document).on('click', '.btn-delete', function(e){
        var __id = $(this).data('id');
        Swal.fire({
            title: `ยกเลิกกระประมูล<br>เลขที่ #${__id}`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
            if (result.isConfirmed) {
                var __url = "{{ route('auctions.delete','__id') }}";
                __url = __url.replace('__id', __id);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'delete',
                        _token:'{{ csrf_token() }}',
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                    }
                }).done(function(data){
                    Swal.fire(`ยกเลิกกระประมูลเรียบร้อย`, ``, `success`);
                    table.draw();
                    loader.close();
                }).fail(function( jqxhr, textStatus ) {
                    var message = jqXHR.responseJSON.message
                    Swal.fire(`Error: ${message}`, ``, `error`);
                    loader.close();
                });
            }
        });
    });

</script>
@endsection
