@extends('front.layouts.main')
@section('title', $shopType->name) 
@section('css')
@endsection

@section('nav_header')
    <a href="{{ route('front.shops.category') }}" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ $shopType->name }}</h5>
@endsection

@section('content')
@if(!count($shopType->shops))
<div class="row">
    <div class="col-12">
        <h4 class="text-center text-green mt-5">ไม่พบข้อมูลร้านค้า</h4>
    </div>
</div>
@else
<div class="row">
    @foreach($shopType->shops as $shop)
    @if($shop->status === 'active')
    <div class="col-md-6">
        <div class="info-box shop-box">
            <span class="info-box-icon">
                <img src="{{ $shop->image }}">
            </span>
            <div class="info-box-content pl-3 pr-1">
                <span class="info-box-text text-r18">{{ $shop->name }}</span>
                <span class="info-box-number text-r14 text-dark mt-1">{{ $shop->description }}</span>
            </div>
            <a href="{{ route('front.shops.show',$shop->id) }}" class="stretched-link"></a>
        </div>
    </div>
    @endif
    @endforeach
</div>
@endif
@endsection

@section('javascript')
@endsection