@extends('front.layouts.main')
@section('title', 'ข้อมูลร้านค้า') 
@section('css')
@endsection

@section('nav_header')
<a href="{{ route('front.users.profile') }}" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">ข้อมูลร้านค้า</h5>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center">
        <a href="{{ route('front.users.shops.creaste') }}" class="btn btn-success mt-2"><i class="fas fa-plus"></i> {{ __('เพิ่มร้านค้า') }}</a>
    </div>
</div>
<div class="row">
    @foreach($shops as $shop)
    @if($shop['status'] == 'active')
    <div class="col-md-6">
        <div class="info-box shop-box">
            <span class="info-box-icon">
                <img src="{{ $shop['image'] }}">
            </span>
            <div class="info-box-content pl-3 pr-1">
                <span class="info-box-text text-r18">{{ $shop['name'] }}</span>
                <span class="info-box-number text-r14 text-dark mt-1">{{ $shop['description'] }}</span>
            </div>
            <a href="{{ route('front.users.shops.show',$shop['id']) }}" class="stretched-link"></a>
        </div>
    </div>
    @endif
    @endforeach
</div>
@endsection

@section('javascript')
@endsection