@extends('admin.layouts.main')
@section('title','Membership requests') 
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
                <h1>คำขอสมัครสมาชิก</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('membership-requests.create') }}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i> เพิ่มคำขอสมัครสมาชิก</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card px-0">
            <div class="card-header">
                <h3 class="card-title">คำขอสมัครสมาชิกทั้งหมด</h3>
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
                                    <input type="text" class="form-control" name="created_at" id="created_at" placeholder="วันที่">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control text-search" name="text_search" id="text-search" placeholder="ค้นหาข้อมูล ...">
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-4">
                            <button type="button" class="btn btn-secondary btn-search"><i class="fas fa-search mr-2"></i> ค้นหา</button>
                        </div>
  
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">ชื่อ-นามสกุล</th>
                                    <th class="text-nowrap">เบอร์โทรศัพท์</th>
                                    <th>อีเมล</th>
                                    <th>เลขที่บัตร</th>
                                    <th class="text-nowrap">ผู้แนะนำ</th>
                                    <th>วันที่</th>
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

<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ยืนยันการสมัครสมาชิก</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body px-0">
            <table class="table">
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="text-center mt-2 mb-4">
            {{-- <button type="button" class="btn btn-success mr-2 btn-action" data-type="approve" data-month="6"><i class="fas fa-check-circle"></i> อนุมัติ 6 เดือน</button> --}}
            <button type="button" class="btn btn-success mr-2 btn-action" data-type="approve" data-month="12"><i class="fas fa-check-circle"></i> อนุมัติ 1 ปี</button>
            <button type="button" class="btn btn-danger mr-2 btn-action" data-type="not_approve" data-month="0"><i class="fas fa-times-circle"></i> ไม่อนุมัติ</button>
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">ปิด</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('javascript')
<script>
    $(document).on('click','.btn-approve-modal',function(e){
        var data = $(this).data('json');
        var recommended_by_user = '-'
        if( data.recommended_by_user ){
            recommended_by_user = data.recommended_by_user.first_name+' '
            if( data.recommended_by_user.last_name ){
                recommended_by_user+=data.recommended_by_user.last_name
            }
        }
        var element = `
            <tr>
                <td width="43%">ชื่อ-นามสกุล<span class="float-right">:</span></td>
                <td>${data.first_name+' '+data.last_name}</td>
            </tr>
            <tr>
                <td>เบอร์โทรศัพท<span class="float-right">:</span></td>
                <td>${data.phone}</td>
            </tr>
            <tr>
                <td>ที่อยู่<span class="float-right">:</span></td>
                <td>${data.full_address}</td>
            </tr>
            <tr>
                <td>อีเมล<span class="float-right">:</span></td>
                <td>${data.email}</td>
            </tr>
            <tr>
                <td>เลขบัตรประชาชน<span class="float-right">:</span></td>
                <td>${data.id_card_number ?? '-'}</td>
            </tr>
            <tr>
                <td>ผู้แนะนำ<span class="float-right">:</span></td>
                <td>${recommended_by_user}</td>
            </tr>
            <tr>
                <td>วันที่<span class="float-right">:</span></td>
                <td>${data.created_at}</td>
            </tr>
        `;
        $('#approve-modal').find('.btn-action').attr('data-id',data.id);
        $('#approve-modal').find('.btn-action').attr('data-name',data.first_name+' '+data.last_name);
        $('#approve-modal').find('.table tbody').html(element)
        $('#approve-modal').modal('show');
    });
    var $dt = $('#table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('membership-requests.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                if(created_at != ""){
                    d.created_at = created_at;
                }
                d.approved_by = 'is_null';
            }
        },
        columns: [
            { 
                data: 'first_name', name: 'first_name',
                render: function (data, type, full, meta){
                    return full.first_name+' '+full.last_name
                }
            },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'id_card_number', name: 'id_card_number' },
            { data: 'recommended_by', name: 'recommended_by' },
            { data: 'created_at', name: 'created_at' },
        ],
        order: [[ 5, "desc" ]],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
        },
        columnDefs : [
            { className: "text-nowrap", targets : [ 0 ] },
            {
                targets:6,
                render: function (data, type, full, meta){
                    return `<button type="button" class="btn btn-primary btn-sm btn-approve-modal" data-json='${JSON.stringify(full)}'><i class="far fa-edit"></i></button>`;
                }
            },
            {
                targets:4,
                render: function (data, type, full, meta){
                    if( !full.recommended_by_user ){
                        return '-'
                    }
                    var last_name = (full.recommended_by_user.last_name) ? full.recommended_by_user.last_name : ''
                    return full.recommended_by_user.first_name+' '+last_name;
                }
            },
        ],
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
    $('.btn-action').on('click',function(e){
        $('#approve-modal').modal('hide');
        var _name = $(this).data('name');
        var type = $(this).data('type');
        var month = $(this).data('month');
        var id = $(this).data('id');
        var text = 'อนุมัติ 6 เดือน';
        var _icon = 'warning';
        if(type != 'approve'){
            text = 'ไม่อนุมัติ';
            _icon = 'error';
        }

        if( month == 12 ){ text = 'อนุมัติ 1 ปี' }

        Swal.fire({
            title: `${text} ให้ ${_name} เป็นสมาชิก?`,
            icon: `${_icon}`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
            if (result.isConfirmed) {
                var __url = `{{ route('membership-requests.process','__id') }}`;
                __url = __url.replace('__id', id);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        type:type,
                        month:month,
                        _token:'{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                        
                    }
                }).done(function(data){
                    if(data) {
                        Swal.fire({
                            icon: 'success',
                            title: `บันทึกข้อมูลเรียบร้อย`,
                            html:`
                                <b>${data.user.first_name+' '+data.user.last_name}</b>
                                <div class="row">
                                <div class="col-6 text-right">รหัสสมาชิก : </div>
                                <div class="col-6 text-left font-weight-bold">${data.user.code}</div>
                                </div>
                                <div class="row">
                                <div class="col-6 text-right">รหัสผ่าน : </div>
                                <div class="col-6 text-left font-weight-bold" style="letter-spacing: 1px;">${data.random_password}</div>
                                </div>
                            `
                        })
                    }else{
                        Swal.fire(`บันทึกข้อมูลเรียบร้อย`, ``, `success`);
                    }
                    table.draw();
                    loader.close();
                }).fail(function( jqxhr, textStatus ) {
                    var message = jqXHR.responseJSON.message
                    Swal.fire(`Error`, message, `error`);
                    loader.close();
                });
            }
        })
    });
</script>
@endsection
