@extends('admin.layouts.main')
@section('title','Billing Create') 
@section('css')
<style>
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>เพิ่มค่าธรรมเนียม</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-6 offset-md-3 col-12">
                            <form id="form" action="{{ route('billing.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="user_id"><span class="text-red">*</span> สมาชิก</label>
                                    <select name="user_id" id="user_id" class="form-control select2">
                                        <option value="">-- เลือกสมาชิก --</option>
                                    </select>
                                    </div>
                                <div class="form-group">
                                    <label for="total_amount"><span class="text-red">*</span> จำนวนเงิน/บาท</label>
                                    <input type="number" name="total_amount" id="total_amount" class="form-control" value="{{ !empty($user->id) ? $user->id : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="remark"><span class="text-red">*</span> หมายเหตุ</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">บันทึกข้อมูล</button>
                            </form>
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
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                window.location.href = '{{ route("billing.index") }}';
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
        }
    });
    $('#user_id').select2({
        theme: 'bootstrap4',
        ajax:{
            url:'{{ route('users.data') }}',
            dataType:"json",
            delay: 250,
            data: function (params) {
                var data = {
                    q:params.term,
                }
                return data
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: `#${item.code} ${item.first_name} ${item.last_name}`,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
</script>
@endsection
