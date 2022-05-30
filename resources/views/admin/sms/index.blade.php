@extends('admin.layouts.main')
@section('title','SMS') 
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
                <h1>SMS</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                    <h3 id="sms-balance">0</h3>

                    <p>SMS คงเหลือ</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-envelope"></i>
                    </div>
                    <a href="#" class="small-box-footer" id="btn-reload-sms-balance">รีเฟช <i class="fas fa-redo-alt"></i></a>
                </div>
            </div>
        
        </div>
        <div class="row">
            <div class="col text-right">
                <a href="{{route('sms.form')}}" class="btn btn-info btn-sm align-bottom"><i class="far fa-paper-plane mr-2"></i>ส่ง SMS</a>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="card-title">SMS ที่ส่งทั้งหมด</h3>
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
                                    <th>รหัส</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ข้อความ</th>
                                    <th>สมาชิก</th>
                                    <th>วันที่ส่ง</th>
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
    $( document ).ready(function() {
        checkBalance()
    })
    var $dt = $('#table');
    table = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('sms.data') !!}",
            data: function (d) {
                var created_at = $('#created_at').val();
                if(created_at != ""){
                    d.created_at = created_at
                }
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'phone', name: 'phone' },
            { data: 'message', name: 'message' },
            { 
                data: 'user', name: 'user',
                render: function (data, type, full, meta){
                    if( data ){
                        return data.first_name+' '+data.last_name
                    }
                    return '-'
                }
            },
            { 
                data: 'created_at', name: 'created_at',
                render: function (data, type, full, meta){
                    return moment(data).format('DD/MM/YYYY H:mm')
                }
            },
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
    $('#btn-reload-sms-balance').on('click',function(e){
        checkBalance()
    })
    function checkBalance(){
        $.ajax({
            url: '{{route("sms.check-balance")}}',
            method: "GET",
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            $('#sms-balance').text(data)
            loader.close();
        })
    }
    
</script>
@endsection
