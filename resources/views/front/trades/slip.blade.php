@extends('front.layouts.main')
@section('title','Trade success') 
@section('css')
<style>
    .icon-transfer-down {
        display: block;
        width: 56px;
        text-align: center;
        margin: 16px 0 14px 0;
    }
    .icon-transfer-down i {
        font-size: 30px;
        color: #28a745;
    }
    .signature {
        background-image: linear-gradient(to bottom, rgba(255,255,255,.96) 0%,rgba(255,255,255,.9) 100%), url("{{ asset('assets/images/'.env('LOGO_SLIP')) }}");
        background-size: contain;
        /* background-repeat: no-repeat; */
    }
    .user-block {
        float: inherit !important;
    }
    .user-block .description {
        color: black;
        font-size: 1rem;

    }
    .bbg-logo {
        width: 56px !important;
        height: auto !important;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">โอนเทรดบาทสำเร็จ</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3 col-12">
        <div class="card signature">
            <div class="card-body">
                <h5 class="text-center text-green">Barter Advance</h5>
                <p class="text-center text-muted text-r14">รหัสอ้างอิง #{{ strtoupper($data->code) }}</p>
                <div class="user-block d-inline-block w-100 mt-4">
                    {{-- <img class="img-circle bbg-logo mr-4" src="http://dev3.loc/assets/images/new_logo_160.png" alt="User Image"> --}}
                    <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                        <div class="avatars-one" style="background-image: url({{ ( empty($data->buyer_by_user) ||  empty($data->buyer_by_user->image) ) ? asset('assets/images/img_profile_default.jpg') : $data->buyer_by_user->image }})"></div>
                    </div>
                    <span class="username">{{ empty($data->buyer_by_user) ? 'BA system' : $data->buyer_by_user->first_name.' '.$data->buyer_by_user->last_name }}</span>
                    <span class="description">{!! empty($data->buyer_by_user) ? '&nbsp;' : 'รหัสผู้ซื้อ  #'.$data->buyer_by_user->code !!}</span>
                </div>
                <span class="icon-transfer-down">
                    <i class="fas fa-long-arrow-alt-down"></i>
                </span>
                <div class="user-block d-inline-block w-100">
                    <div class="avatars rounded-circle avatars-sm border-green  float-left mr-4 ">
                        <div class="avatars-one" style="background-image: url({{ empty($data->seller_by_user->image) ? asset('assets/images/img_profile_default.jpg') : $data->seller_by_user->image }})"></div>
                    </div>
                    <span class="username">{{ empty($data->seller_by_user) ? 'XXXXX' : $data->seller_by_user->first_name.' '.$data->seller_by_user->last_name }}</span>
                    <span class="description">รหัสผู้ขาย  #{{ empty($data->seller_by_user) ? 'XXXXX' : $data->seller_by_user->code }}</span>
                </div>
                <ul class="list-unstyled list-inline mt-4 mb-1">
                    <li class="d-inline">จำนวนเทรดบาท</li>
                    <li class="d-inline float-right"><span class="h5">{{ number_format($data->total_amount) }}</span> เทรดบาท</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="d-inline">วันที่ทำรายการ</li>
                    <li class="d-inline float-right">{{ date('d/m/Y H:i', strtotime($data->created_at)) }}</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="">บันทึกช่วยจำ</li>
                    <li class="">{{ $data->remark }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection