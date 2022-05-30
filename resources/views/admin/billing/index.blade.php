@extends('admin.layouts.main')
@section('title','Billing') 
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
                <h1>ค่าธรรมเนียม</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('billing.form') }}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> เพิ่มค่าธรรมเนียม</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ค่าธรรมเนียมทั้งหมด</h3>
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
                                        <option value="unpaid">ยังไม่ชำระเงิน</option>
                                        <option value="pending">รอการตรวจสอบ</option>
                                        <option value="paid">ชำระเงินเรียบร้อย</option>
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
                                    <th>เลขที่การซื้อขาย</th>
                                    <th>สมาชิก</th>
                                    <th>เทรดบาท</th>
                                    <th class="w-10">หมายเหตุ</th>
                                    <th>จำนวนเงิน</th>
                                    <th>สถานะ</th>
                                    <th>วันที่ทำรายการ</th>
                                    <th>การชำระเงิน</th>
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
@endsection
@section('javascript')
<script>
    var $dt = $('#table');
    var table;
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('billing.data') !!}",
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
            { data: 'id', name: 'id',className: 'text-nowrap text-center', },
            { 
                data: 'trade.id', name: 'trade.id',className: 'text-center',
                render: function (data, type, full, meta){
                    return (full.trade) ? data : '-'
                }
            },
            { 
                data: 'user_code', name: 'user_code',
                render: function (data, type, full, meta){
                    let url = "{{route('users.show','_id')}}"
                    url = url.replace('_id', full.user.id)
                    return `<a href="${url}" target="_blank" class="text-blue btn-link">​#${data} ${full.user_full_name}</a>`;
                }
            },
            { 
                data: 'trade.total_amount', name: 'trade.total_amount',className: 'text-nowrap',
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
            { 
                className: 'text-nowrap',
                render: function (data, type, full, meta){
                    var __url = "{{ route('billing.edit','__id') }}";
                        __url= __url.replace('__id', full.id);
                    var element = `<a href="${__url}" class="btn btn-warning btn-sm mr-2"><i class="far fa-edit"></i></a>`;
                    if(full.status != 'cancel' && full.status != 'paid'){
                        var __url = "{{ route('billing.edit','__id') }}";
                        __url= __url.replace('__id', full.id);
                        element += `
                        <button type="button" class="btn btn-danger btn-sm btn-cancel" data-id="${full.id}"><i class="far fa-trash-alt"></i></button>
                        `;
                    }
                    return element;
                }
            },
        ],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        order: [[ 0, "desc" ]],
        columnDefs : [
            // { className: "text-nowrap", targets : [ 0,8 ] },
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

    $(document).on('click', '.btn-cancel', function(e){
        var __id = $(this).data('id');
        Swal.fire({
            title: `ยกเลิกค่าธรรมเนียม<br>เลขที่ #${__id}`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
            if (result.isConfirmed) {
                var __url = `{{ route('billing.change-status','__id') }}`;
                __url = __url.replace('__id', __id);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'put',
                        _token:'{{ csrf_token() }}',
                        status:'cancel'
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                    }
                }).done(function(data){
                    Swal.fire(`ยกเลิกค่าธรรมเนียมเรียบร้อย`, ``, `success`);
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

    $(document).on('click', '#btn-confirm-payment', function(e){
        var __id = $(this).data('id');
        Swal.fire({
            title: `ยืนยันการชำเงินค่าธรรมเนียม`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
                var __url = `{{ route('billing.change-status','__id') }}`;
                __url = __url.replace('__id', __id);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'put',
                        status:'paid',
                        _token:'{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        $('#transfer-modal').modal('hide');
                        loader.init();
                    }
                }).done(function(data){
                    Swal.fire(`บันทึกข้อมูลเรียบร้อย`, ``, `success`);
                    table.draw();
                    loader.close();
                }).fail(function( jqxhr, textStatus ) {
                    var message = jqXHR.responseJSON.message
                    Swal.fire(`Error: ${message}`, ``, `error`);
                    loader.close();
                });
            });
    });
</script>
@endsection
