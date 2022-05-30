@extends('admin.layouts.main')
@section('title','Trades') 
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
                <h1>การซื้อขาย</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">การซื้อขายทั้งหมด</h3>
            </div>
            <div class="card-body px-0">
                <div class="px-4 mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">สถานะ</span>
                                    </div>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">ทั้งหมด</option>
                                        <option value="success">สำเร็จ</option>
                                        <option value="cancel">ยกเลิก</option>
                                    </select>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="created_at" id="created_at" placeholder="วันที่ทำรายการ">
                                </div>
                            </div>
                        </div>
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
                                    <th>ผู้ซื้อ</th>
                                    <th>ผู้ขาย</th>
                                    <th>เทรดบาท</th>
                                    <th class="w-20">บันทึกช่วยจำ</th>
                                    <th>สถานะ</th>
                                    <th class="w-15">วันที่</th>
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
@include('admin.layouts.slip_modal')
@endsection
@section('javascript')
<script>
    var $dt = $('#table');
    var table;
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('trades.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                var status = $('#status').val();
                if(created_at != ""){
                    d.created_at = created_at;
                }
                if(status != ""){
                    d.status = status;
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'buyer_full_name', name: 'buyer_full_name' },
            { data: 'seller_full_name', name: 'seller_full_name' },
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
            { className: "text-center", targets : [ 7 ] },
            {
                targets:0,
                render: function (data, type, full, meta){
                    return `<a class="text-blue btn-link show-slip" data-json='${JSON.stringify(full)}'><i class="fas fa-file-invoice"></i> #${data}</a>`;
                }
            },
            {
                targets:5,
                render: function (data, type, full, meta){
                    if(data == 'success'){
                        return '<span class="badge badge-success">สำเร็จ</span>';
                    }else if( data == 'cancel'){
                        return '<span class="badge badge-danger">ยกเลิก</span>';
                    }
                }
            },
            {
                targets:7,
                render: function (data, type, full, meta){
                    if(full.status == 'success'){
                        return `<button type="button" class="btn btn-danger btn-sm btn-cancel" data-id="${full.id}"><i class="far fa-trash-alt"></i></button>`;
                    }
                    return '';
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
        // applyButtonClasses: "btn-success",
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

    $(document).on('click', '.btn-cancel', function(e){
        var __id = $(this).data('id');
        Swal.fire({
            title: `ยกเลิกรายการซื้อขาย<br>เลขที่ #${__id}`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
            if (result.isConfirmed) {
                var __url = `{{ route('trades.cancel','__id') }}`;
                __url = __url.replace('__id', __id);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'put',
                        _token:'{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                    }
                }).done(function(data){
                    Swal.fire(`ยกเลิกรายการซื้อขายเรียบร้อย`, ``, `success`);
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

    $(document).on('click','.show-slip', function(e){
        var data = $(this).data('json');
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
</script>
@endsection
