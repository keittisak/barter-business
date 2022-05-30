@extends('front.layouts.main')
@section('title','Products') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">สินค้า</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12 text-right mb-2">
        <a href="{{ route('front.users.products.create') }}" class="btn btn-link text-blue"><i class="fas fa-plus mr-2"></i>เพิ่มสินค้า</a>
    </div>
</div>
<div class="row">
    {{-- <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="item-center-div text-center">
            <a class="btn btn-info btn-circle bg-green"><i class="fas fa-plus"></i></a>
            <h5 class="mt-1">เพิ่มสินค้า</h5>
            <a href="{{ route('front.users.products.create') }}" class="stretched-link"></a>
        </div>
    </div> --}}
    @foreach($products as $product)
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card card-sm card-product-grid">
            @if($product->status == 'sold_out')
            <div class="card-img-overlay justify-content-end badge-sale">
                <a class="badge badge-warning float-right">SOLD OUT</a>
            </div>
            @endif
            <div class="img-wrap">
                <img src="{{ $product->image }}">
            </div>
            <div class="card-body px-2 pt-2">
                <h5 class="product-title text-r16 text-center">{{ $product->name }}</h3>
                <div class="product-price text-center mt-3">{{ number_format($product->price) }}</div>
            </div>
            <div class="card-footer p-0">
                <a href="{{ route('front.users.products.edit', $product->id) }}" class="btn btn-link btn-block bg-default border-topx-radius-0">แก้ไข</a>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection

@section('javascript')
@endsection