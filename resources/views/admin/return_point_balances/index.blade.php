@extends('admin.layouts.main')
@section('title','Return Point Balance') 
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
                <h1>รายการปรับปรุงเทรดบาทคงเหลือ</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col text-right">
                <a href="{{route('return-point-balances.create')}}" class="btn btn-info btn-sm align-bottom"><i class="fas fa- mr-2"></i>ทำรายการปรับปรุงเทรดบาทคงเหลือ</a>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">รายการปรับปรุงเทรดบาทคงเหลือทั้งหมด</h3>
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
                                    <th>เลขที่</th>
                                    <th>รหัสสมาชิก</th>
                                    <th>ชื่อสมาชิก</th>
                                    <th>หมายเหตุ</th>
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
</section>
<div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">เลขที่</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="text-center mt-2 mb-4">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">ปิด</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('javascript')
<script>
    var $dt = $('#table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('return-point-balances.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                if(created_at != ""){
                    d.created_at = created_at
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'user', name: 'user',
                render: function (data, type, full, meta){
                    if( data ){
                        return data.code
                    }
                    return '-'
                }
            },
            { 
                data: 'user', name: 'user',
                render: function (data, type, full, meta){
                    if( data ){
                        return data.first_name+' '+data.last_name
                    }
                    return '-'
                }
            },
            { data: 'remark', name: 'remark' },
            { 
                data: 'created_at', name: 'created_at',
                render: function (data, type, full, meta){
                    return moment(data).format('DD/MM/YYYY H:mm')
                }
            },
            {
                data: 'id', name: 'id',
                render: function (data, type, full, meta){
                    return `<button type="button" class="btn btn-primary btn-sm btn-details-modal"><i class="far fa-edit"></i></button>`;
                }
            }
        ],
        order: [[ 0, "desc" ]],
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

    $(document).on('click','.btn-details-modal', function(e){
        let $row = $(this).closest('tr');
        let data = table.row($row).data();
        var beforeElement = ``;
        var afterElement = ``;
        (data.details).map(item => {
            if( item.point_id == 1 ){
                beforeElement += `
                    <label class="col">เทรดบาทคงเหลือ:&nbsp;&nbsp;<span id="text-credit-balance">${pricceFormat(item.before_total_amount)}</span></label>
                `
                afterElement += `
                <label class="col">เทรดบาทคงเหลือ:&nbsp;&nbsp;<span id="text-credit-balance">${pricceFormat(item.after_total_amount)}</span></label>
                `
            }else{
                beforeElement += `
                    <label class="col">วงเงินเครดิต:&nbsp;&nbsp;<span id="text-credit-balance">${pricceFormat(item.before_total_amount)}</span></label>
                `
                afterElement += `
                    <label class="col">วงเงินเครดิต:&nbsp;&nbsp;<span id="text-credit-balance">${pricceFormat(item.after_total_amount)}</span></label>
                `
            }
        })
        $('#details-modal').find('.modal-body').html(`
            <div class="form-group">
                <p class="font-weight-bold mb-0">สมาชิก</p>
                <p class="font-weight-bold mb-0">#${data.user.code} ${data.user.first_name+' '+data.user.last_name}</p>
            </div>
            <div class="form-group row">
                <label class="col-12" for="total_amount">ก่อนปรับปรุงเทรดบาท</label>
                ${beforeElement}
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-12" for="total_amount">หลังปรับปรุงเทรดบาทล่าสุด</label>
                ${afterElement}
            </div>
        `)
        $('#details-modal').modal('show')
        
    })
    
</script>
@endsection
