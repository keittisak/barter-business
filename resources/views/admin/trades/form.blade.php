@extends('admin.layouts.main')
@section('title','Trade form') 
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
                <h1>โอนเทรดบาทซื้อขาย</h1>
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
                        <form id="form" action="{{ route('trades.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <p class="font-weight-bold mb-0"><span class="text-red">*</span> ผู้ซื้อ</p>
                                <select name="buyer_id" id="buyer_id" class="form-control select2">
                                    <option value="">-- เลือกสมาชิก --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">เทรดบาทคงเหลือ: <span id="text-point-balance">0</span></label>
                                <br>
                                <label for="">วงเงินเครดิตคงเหลือ: <span id="text-credit-balance">0</span></label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <p class="font-weight-bold mb-0"><span class="text-red">*</span> ผู้ขาย</p>
                                <select name="seller_id" id="seller_id" class="form-control select2">
                                    <option value="">-- เลือกสมาชิก --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total_amount"><span class="text-red">*</span> จำนวนเทรดบาท</label>
                                <input type="number" name="total_amount" id="total_amount" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="remark"><span class="text-red">*</span> บันทึกช่วยจำ</label>
                                <textarea name="remark" id="remark" rows="2" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">โอนเทรดบาท</button>
                        </form>
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
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            Swal.fire({
                icon:'success',
                title:'โอนเทรดบาทเรียบร้อย'
            }).then(function(){
                // window.location.href = '{{ route("trades.create") }}';
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

    $('#buyer_id').select2({
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
                            credit_total_amount: item.credit_total_amount,
                            balances:item.balances,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
    $('#seller_id').select2({
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
                            credit_total_amount: item.credit_total_amount,
                            balances:item.balances,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    $('#buyer_id').on('change', function(e) {
        var data = $(this).select2('data')[0];
        var balances = data.balances;
        var pointBalance = balances.find(v => v.point_id == 1);
        var creditBalance = balances.find(v => v.point_id == 2);
        $('#text-point-balance').html(utilities.numberFormat(pointBalance.total_amount,0));
        $('#text-credit-balance').html(utilities.numberFormat((creditBalance)?creditBalance.total_amount:'0',0));
    })
</script>
@endsection
