@extends('front.layouts.main')
@section('title','Products') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
<nav class="main-header navbar nav-top bg-green nva-top">
    <a href="{{ route('front.users.profile') }}" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ __('สินค้า') }}</h5>
</nav>
@endsection

@section('content')
<div class="row">

    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="item-center-div text-center">
            <a class="btn btn-info btn-circle bg-green"><i class="fas fa-plus"></i></a>
            <h5 class="mt-1">เพิ่มสินค้า</h5>
            <a href="#" class="stretched-link"></a>
        </div>
    </div>

    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card card-product">
            <div class="card-img-overlay justify-content-end badge-sale">
                <a href="#" class="badge badge-primary bg-primary float-right">แก้ไข</a>
            </div>
            <div class="img-wrap">
                <img class="card-img-top" src="http://bootstrap-ecommerce.com/templates/alistyle-html/images/items/1.jpg">
            </div>
            <div class="card-body px-2 pt-1">
                <h5 class="product-title text-r16 text-center">เสื้อคอกลม BOHO</h3>
                <div class="product-price text-center mt-3">600<span class="icon-g-point"><i class="fab fa-google text-gold"></i></span>
            </div>
        </div>
    </div>

</div>
@endsection

@section('javascript')
@endsection