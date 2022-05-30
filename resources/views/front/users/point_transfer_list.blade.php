@extends('front.layouts.main')
@section('title','Product create') 
@section('css')
<style>
    .modal-info-box-icon img {
        max-height: 5rem;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">รายการ</h5>
@endsection

@section('content')

@foreach( $transactions  as $item)
<div class="row">
    <div class="col-12">
        <div class="d-flex mb-2">
            <small>{{ $item['date_th'] }}</small>
            <small class="ml-auto">{{ $item['data_title'] }}</small>
        </div>
        <div class="card">
            <div class="card-body p-0">
                @foreach( $item['data'] as $key => $data)
                @php
                    $isIncome = ($data->transferred_to_user->id == auth()->user()->id) ? true : false;
                    $isSystem = (empty($data->created_by_user)) ? true : false;
                    $dataJson = json_encode([
                        'isIncome' => $isIncome,
                        'code' => strtoupper($data->code),
                        'total_amount' => (($isIncome) ? '' : '-').number_format($data->total_amount),
                        'created_at' => date('d/m/Y H:i', strtotime('+543 years', strtotime($data->created_at) ) ),
                        'remark' => $data->remark,
                        'created_by_user' => [
                            'full_name' => ($isSystem) ? 'BA System' : $data->created_by_user->first_name.' '.$data->created_by_user->last_name,
                            'phone' => ($isSystem) ? '' : $data->created_by_user->phone,
                            'shop' => [
                                'name' => ($isSystem) ? '' : ($data->created_by_user->shop) ? $data->created_by_user->shop->name : '',
                                'image' => ($isSystem) ? '' : ($data->created_by_user->shop) ? $data->created_by_user->shop->image : ''
                            ]
                        ],
                        'transferred_to_user' => [
                            'full_name' => $data->transferred_to_user->first_name.' '.$data->transferred_to_user->last_name,
                            'phone' => $data->transferred_to_user->phone,
                            'shop' => [
                                'name' => (!empty($data->transferred_to_user->shop)) ? $data->transferred_to_user->shop->name : '',
                                'image' => (!empty($data->transferred_to_user->shop)) ? $data->transferred_to_user->shop->image : ''
                            ]
                        ],
                    ]);
                @endphp
                <div class="info-box  box-shadow-none mb-0 report-info-box" data-json='{{ $dataJson }}'>
                    @if( $isIncome )
                        <span class="info-box-icon">
                            <span class="icon-transfer">
                                <i class="fas fa-exchange-alt"></i>
                            </span>
                        </span>
                    @else
                        <span class="info-box-icon">
                            <img src="{{ $data->transferred_to_user->shop->image }}">
                        </span>
                    @endif
                    <div class="info-box-content">
                        <span class="info-box-text">{{ ($isIncome) ? 'รับเทรดบาทจาก '.($isSystem) ? 'BA System' : $data->created_by_user->first_name.' '.$data->created_by_user->last_name : $data->transferred_to_user->shop->name  }}</span>
                        <div class="d-block mt-2">
                            <span class="info-box-number small text-muted d-inline"><i class="far fa-clock"></i> {{ date('H:i', strtotime($data->created_at)) }}</span>
                            <div class="float-right">{{ ($isIncome) ? '' : '-'  }}{{ number_format($data->total_amount) }}<span class="icon-g-point"><i class="fab fa-google text-gold"></i></span></div>
                        </div>
                    </div>
                </div>
                @if(count($item['data']) > ($key+1)) <hr class="py-0 my-1"> @endif

                @endforeach

            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade modal-report-detail" id="modal-report-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header pb-2">
                <div class="info-box box-shadow-none p-0 m-0">
                    <span class="info-box-icon">
                        <img src="#">
                    </span>
                    <div class="info-box-content px-4">
                        <span class="info-box-text">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
    <div class="btn-colose-modal" data-dismiss="modal">
        <button type="button" class="btn btn-success btn-circle btn-lg"><i class="fas fa-times text-r24"></i></button>
        <p class="text-white">ปิด</p>
    </div>
</div>



@endsection

@section('javascript')
<script>
    $(document).on('click','.report-info-box',function(e){
        var data = $(this).data('json');
        var header = ``;
        var body = ``;
        if( data.isIncome == false ) {
            header = `<div class="info-box box-shadow-none p-0 m-0">
                            <span class="info-box-icon modal-info-box-icon">
                                <img src="${data.transferred_to_user.shop.image}">
                            </span>
                            <div class="info-box-content px-4">
                                <span class="info-box-text">${data.transferred_to_user.shop.name}</span>
                            </div>
                        </div>`;
                body = `<div class="row border-bottom mb-2">
                            <div class="col-12">
                                <small class="text-muted">บันทึก</small>
                                <p class="" id="text-report-type">${(data.remark == null) ? '' : data.remark }</p>
                            </div>
                        </div>
                        <div class="row border-bottom mb-2">
                            <div class="col-6">
                                <small class="text-muted" id="text-report-type">โอนเทรดบาทให้</small>
                                <p class="mb-0" id="text-report-type">${data.transferred_to_user.full_name}</p>
                                <p class="mb-0" id="text-report-type">${data.transferred_to_user.phone}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">จำนวน</small>
                                <div class="d-block">
                                    <span id="text-report-amount">${data.total_amount}<span>
                                    <span class="icon-g-point"><i class="fab fa-google text-gold"></i></span></span></span>
                                </div>
                            </div>
                        </div>`;
        } else {
            header = `<p class="pb-0 mb-0">รับเทรดบาทจาก</p>`
            body = `<div class="row border-bottom mb-2">
                        <div class="col-12">
                            <small class="text-muted">บันทึก</small>
                            <p class="" id="text-report-type">${(data.remark == null) ? '' : data.remark }</p>
                        </div>
                    </div>
                    <div class="row border-bottom mb-2">
                        <div class="col-6">
                            <small class="text-muted">ชื่อผู้ส่ง</small>
                            <p class="mb-0" id="text-report-type">${data.created_by_user.full_name}</p>
                            <p class="mb-0" id="text-report-type">${data.created_by_user.phone}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">จำนวน</small>
                            <div class="d-block">
                                <span id="text-report-amount">${data.total_amount}<span>
                                <span class="icon-g-point"><i class="fab fa-google text-gold"></i></span></span></span>
                            </div>
                        </div>
                    </div>`;
        }

        body += `<div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">วันที่-เวลา</small>
                        <p>${data.created_at}</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">เลขที่อ้างอิง</small>
                        <p>${data.code}</p>
                    </div>
                </div>`;
        $('#modal-report-detail').find('.modal-header').html(header);
        $('#modal-report-detail').find('.modal-body').html(body);
        $('#modal-report-detail').modal('show');
    });
</script>
@endsection