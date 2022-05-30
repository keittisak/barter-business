@extends('admin.layouts.main')
@section('title','Shop types') 
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
                <h1>ประเภทร้านค้า</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('shop-types.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> เพิ่มประเภทร้านค้า</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ประเภทร้านค้าทั้งหมด</h3>
            </div>
            <div class="card-body px-0">
                <div class="px-4 mb-4">
                    <div class="row">
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
                                    <th>ลำดับ</th>
                                    <th>ภาพ</th>
                                    <th>ชื่อ</th>
                                    <th>วันที่สร้าง</th>
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
            url:"{!! route('shop-types.data') !!}",

        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
        ],
        columnDefs : [
            // { className: "text-nowrap", targets : [ 0,9 ] },
            {
                targets:1,
                render: function (data, type, full, meta){
                    return `<img src="${data}" style="width:80px">`
                }
            },
            {
                targets:3,
                render: function (data, type, full, meta){
                    return moment(data).format('DD/MM/YYYY h:mm');
                }
            },
            {
                targets:4,
                render: function (data, type, full, meta){
                    var __url_edit = "{{ route('shop-types.edit','__id') }}";
                    __url_edit = __url_edit.replace('__id', full.id);
                    return `
                    <a href="${full.image}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></a>
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

    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        table.search(text).draw();
    });

    $(document).on('click', '.btn-delete', function(e){
        var __id = $(this).data('id');
        Swal.fire({
            title: `ประเภทร้านค้า<br>`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
            }).then((result) => {
            if (result.isConfirmed) {
                var __url = "{{ route('shop-types.delete','__id') }}";
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
                    Swal.fire(`ประเภทร้านค้าเรียบร้อย`, ``, `success`);
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
