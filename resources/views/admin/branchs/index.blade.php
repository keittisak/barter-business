@extends('admin.layouts.main')
@section('title','Setting') 
@section('css')
<style>
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>ตั้งค่า</h1>
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
                            <form id="form" action="{{ route('branchs.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="mb-4">
                                    <label for="new_member"><span class="text-red">*</span> จำนวนเทรดบาทที่เป็นสมาชิกครั้งแรก</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="new_member" id="new_member" class="form-control" value="{{ $new_member }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">เทรดบาท</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="recommend"><span class="text-red">*</span> จำนวนเทรดบาทแนะนำสมาชิกใหม่</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="recommend" id="recommend" class="form-control" value="{{ $recommend }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">เทรดบาท</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="mb-4">
                                    <label for="commission"><span class="text-red">*</span> ค่าคอมมิสชั่น (ค่าตอบแทนจากการซื้อขายของสมาชิกที่ได้แนะนำ)</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="commission" id="commission" class="form-control" value="{{ $commission*100 }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="mb-4">
                                    <label for="trade_fee"><span class="text-red">*</span> ค่าธรรมเนียมการซื้อขาย (สมาชิกประเภท Business)</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="trade_fee" id="trade_fee" class="form-control" value="{{ $trade_fee*100 }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="mb-4">
                                    <label for="renewal_fee"><span class="text-red">*</span> ค่าธรรมเนียมต่ออายุสมาชิก</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="renewal_fee" id="renewal_fee" class="form-control" value="{{ $renewal_fee }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">บาท</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">บันทึกข้อมูล</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Barter advance  คือ</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pad" style="display: none;">
                        <form id="form-description" action="{{ route('branchs.description.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="mb-3">
                                <textarea class="description" placeholder="Place some text here">{{ $description }}</textarea>
                            </div>
                            <div class="col-md-6 offset-md-3 col-12 mt-4">
                                <button type="button" class="btn btn-success btn-block update-description">บันทึกข้อมูล</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-md-12 text-center mt-4">
                <p class="mb-0"><span class="text-red">*</span> อัพเดทข้อมูลล่าสุด</p>
                <p class="mb-0">{{ $updated_by_user['first_name'].' '.$updated_by_user['last_name'] }}</p>
                <p class="mb-0">{{ date('d/m/Y H:i', strtotime($updated_at.' +543 years')) }}</p>
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
            loader.close();
        }
    });

    $(function () {
        $('.description').summernote({
            tabsize: 2,
            height: 400
        })
    });

    $('.update-description').on('click',function(e){
        var markup = $('.description').summernote('code');
        $.ajax({
            url: "{{ route('branchs.description.update') }}",
            method: "POST",
            data:{
                    _token:'{{ csrf_token() }}',
                    _method:'PUT',
                    description: markup
                },
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                location.reload();
            });
        }).fail(function( jqxhr, textStatus ) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            Swal.fire(message, ``, `error`);
            loader.close();
        });
    });
</script>
@endsection
