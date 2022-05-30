@extends('admin.layouts.main')
@section('title','Sender SMS') 
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
                <h1>ส่ง SMS</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-12">
                        <form id="form" action="{{route('sms.sender')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="message"><span class="text-red">*</span> {{ __('ข้อความ') }}</label>
                                <textarea name="message" id="message" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                            <div id="display-phone">
                                <p><span class="text-red">*</span> {{ __('เบอร์โทรศัพท์') }}</p>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                    <button type="button" class="btn btn-primary float-left" onclick="addPhone()"><i class="fas fa-plus mr-2"></i>เพิ่มเบอร์โทรศัพท์</button>
                                <button type="button" class="btn btn-primary float-right" onclick="{$('#exampleModal').modal('show')}"><i class="fas fa-search mr-2"></i>ค้นหาเบอร์โทรศัพท์สมาชิก</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center mt-5">
                                    <button type="button" class="btn btn-success bg-green" id="btn-sender"><i class="far fa-paper-plane mr-2"></i>ส่ง SMS</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">สมาชิก</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
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
            <div class="row">
                <div class="col">
                    <table class="table" id="userTable" style="width: 100%; max-height: 120px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>รหัส</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>ประเภท</th>
                                <th>วันที่หมดอายุ</th>
                                <th>SMS ล่าสุด</th>
                                <th>SMS วันที่ล่าสุด</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            <button type="button" class="btn btn-primary" onclick="{addPhone('user')}"><i class="fas fa-plus mr-2"></i>เพิ่มเบอร์โทรศัพท์</button>
        </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script>
    var usersChecked = []

    $('#btn-sender').on('click', function(e){
        e.preventDefault();
        Swal.fire({
            title: `ยืนยันการส่ง SMS`,
            icon: `warning`,
            showCancelButton: true,
            confirmButtonText: `ตกลง`,
            cancelButtonText:`ยกเลิก`,
        }).then((result) => {
            $('#form').submit();
        })
        
    })

    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            Swal.fire({
                icon:'success',
                title:'ส่ง SMS เรียบร้อย'
            }).then(function(){
                window.location.href = '{{ route("sms.index") }}';
            });
            
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });

            loader.close();
            if( jqXHR.status == 500 ){
                Swal.fire({
                    icon:'error',
                    title:message
                })
            }else if( jqXHR.status == 422 ){
                Swal.fire({
                    icon:'warning',
                    title:'กรุณาระบุข้อมูลให้ถูกต้อง!'
                })
            }

        }
    });
    var userTable;
    var $dt = $('#userTable');
    userTable = $dt.DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{!! route('users.data') !!}",
            data: function (d) {
                var expired_at = $('#expired_at').val();
                if(expired_at != "") {
                    d.expired_at = expired_at
                }
            }
        },
        columns: [
            { 
                data: 'id', name: 'id', 
                render: function (data, type, full, meta){
                    let checked = ''
                    let index = usersChecked.findIndex( (user) => user.id == full.id  )
                
                    if( index != -1){
                        console.log(index)
                        checked = 'checked'
                    }
                    return `<input type="checkbox" id="table-checkbox-user-id-${full.id}" class="form-control" style="width:35%" ${checked}>`
                }
            },
            { data: 'code', name: 'code' },
            { data: 'first_name', name: 'first_name' },
            { data: 'phone', name: 'phone' },
            { 
                data: 'type', name: 'type',
                render: function (data, type, full, meta){
                    return full.user_type.name;
                }
            },
            { data: 'expired_at', name: 'expired_at' },
            { 
                data: 'sms', name: 'sms',
                render: function (data, type, full, meta){
                    if( data.length ){
                        return data[data.length-1].message
                    }
                    return '-';
                }
            },
            { 
                data: 'sms', name: 'sms',
                render: function (data, type, full, meta){
                    if( data.length ){
                        return moment(data[data.length-1].created_at).format('DD/MM/YYYY H:mm')
                    }
                    return '-';
                }
            },
        ],
        order: [[ 0, "desc" ]],
        createdRow : function( row, data, dataIndex ) {
            $(row).attr('data-code', data.code);
            let index = usersChecked.findIndex( (user) => user.id == data.id  )
            if( index != -1){
                $(row).css('background-color','rgba(0,0,0,.05)')
            }
        },
        drawCallback: function (settings) {
            if (!$dt.parent().hasClass("table-responsive")) {
                $dt.wrap("<div class='table-responsive'></div>");
            }
            loader.close();
        },
    });

    $(document).on('change', '#userTable input[type="checkbox"]', function(e){
        let _tr = $(this).closest('tr')
        let data = userTable.row( _tr ).data()
        _tr.css('background-color','rgba(0,0,0,.05)')
        if( $(this).is(':checked') ){
            usersChecked.push(data)
        }else{
            let index = usersChecked.findIndex( (user) => user.id == data.id  )
            usersChecked.splice(index,1)
        }
    })

    $(document).on('click', '.remove-phone', function(e){
        let userId = $(this).data('user-id')
        if( userId === undefined ) {
            $(this).closest('.input-group').remove()
        }else{
            let index = usersChecked.findIndex( (user) => user.id == userId  )
            usersChecked.splice(index,1)
            $(this).closest('.input-group-by-user').remove()
            $(`#table-checkbox-user-id-${userId}`).prop('checked',false)
            $(`#table-checkbox-user-id-${userId}`).closest('tr').css('background-color','')
        }
    })

    function addPhone (by=null) {
        if( by == 'user' ){
            $.map(usersChecked, (user) => {
                if( !$(`#input-phone-user-${user.id}`).length ){
                    $('#display-phone').append(`
                        <div class="input-group-by-user">
                            <label for="message"><span class="text-muted">${user.first_name+' '+user.last_name}</label>
                            <div class="input-group mb-3">
                                <input type="hidden" class="form-control" name="phones[${user.id}][user_id]" value="${user.id}" readonly>
                                <input type="number" pattern=".{10,}" class="form-control phone" name="phones[${user.id}][number]" value="${user.phone}" id="input-phone-user-${user.id}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-phone" data-user-id="${user.id}"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    `);
                }
            })
            $('#exampleModal').modal('hide')
        }else{
            $('#display-phone').append(`
                <div class="input-group mb-3 mt-4">
                    <input type="number" pattern=".{10,}" class="form-control phone" name="phones[][number]">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-phone"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            `);
        }
        // $('.phone').inputmask("999-999-9999");
    }
    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        userTable.search(text).draw();
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
