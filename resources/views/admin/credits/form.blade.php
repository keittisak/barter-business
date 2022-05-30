@extends('admin.layouts.main')
@section('title','Credit') 
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
                <h1>โอนเงินเครดิต</h1>
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
                        <form id="form" action="{{ route('credits.process') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <p class="font-weight-bold mb-0"><span class="text-red">*</span> สมาชิก</p>
                                <select name="user_id" id="user_id" class="form-control select2">
                                    <option value="">-- เลือกสมาชิก --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">วงเงินเครดิต: <span id="text-credit-total-amount">0</span></label>
                                <br>
                                <label for="">เทรดบาทคงเหลือ: <span id="text-credit-balance">0</span></label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="total_amount"><span class="text-red">*</span> จำนวนเงินเครดิต</label>
                                <input type="number" name="total_amount" id="total_amount" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="remark"><span class="text-red">*</span> บันทึกช่วยจำ</label>
                                <textarea name="remark" id="remark" rows="2" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">โอนเงินเครดิต</button>
                        </form>
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
                title:'โอนเงินเครดิตเรียบร้อย'
            }).then(function(){
                // window.location.href = '{{ route("credits.form") }}';
                location.reload();
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
            Swal.fire({
                icon:'error',
                title:message
            })
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
                    type:2
                }
                return data
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: `#${item.code} ${item.first_name} ${item.last_name}`,
                            credit_total_amount: item.credit_total_amount,
                            balances:item.balances,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    $('#user_id').on('change', function(e) {
        var data = $(this).select2('data')[0];
        var balances = data.balances;
        var point = balances.find(v => v.point_id == 1);
        var credit = balances.find(v => v.point_id == 2);
        $('#text-credit-balance').html(pricceFormat( (point != undefined) ? point.total_amount : 0 ));
        $('#text-credit-total-amount').html(pricceFormat( (credit != undefined) ? credit.total_amount : 0 ));
    })
</script>
@endsection
