@extends('front.layouts.main')
@section('title','Shop') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
    <a href="{{ route('front.users.shops.index') }}" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">ร้านค้า</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="card-tools">
                    <a href="{{ route('front.users.shops.edit', $shop->id) }}" class="btn btn-tool text-blue">แก้ไขร้านค้า</a>
                </div>
                <!-- /.card-tools -->
            </div>

            <div class="row no-gutters">
                <aside class="col-sm-6 border-right">
                    <article class="gallery-wrap"> 
                        <div id="carouselIndicators" class="carousel slide  w-100 carousel-store" data-ride="carousel" data-interval="false">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="img-big-wrap">
                                        <a><img src="{{ $shop->image }}"></a>
                                    </div>
                                </div>
                                @if( count($images) )
                                    @for( $i=0; $i < count($images); $i++ )
                                        <div class="carousel-item">
                                            <div class="img-big-wrap">
                                                <a><img src="{{ $images[$i]->image }}"></a>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <ol class="carousel-indicators" style="position: initial">
                                <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
                                @if( count($images) )
                                    @for( $i=1; $i <= count($images); $i++ )
                                        <li data-target="#carouselIndicators" data-slide-to="{{ $i }}" ></li>
                                    @endfor
                                @endif
                            </ol>
                        </div>
                    </article>
                </aside>
                <main class="col-sm-6">
                    <article class="content-body">
                        <h3 class="title">{{ $shop->name }}</h3>
                        <div class="rating-wrap mb-3">
                            {{-- <span class="badge badge-warning"> <i class="fa fa-star"></i> 3.8</span> --}}
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ !empty($shop->province) ? $shop->province->name.', ' : '' }}{{ !empty($shop->country) ? $shop->country->name : 'ประเทศไทย' }}</small>
                        </div>
                        {{-- <h5 class="title-section text-r18"><i class="fas fa-hashtag"></i>{{$shop->code}}</h5> --}}
                        <h5 class="title-section text-r18 text-muted">รายละเอียด</h5>
                        <p>{{ $shop->description }}</p>
                        <h5 class="title-section text-r18 text-muted">ประเภทร้านค้า</h5>
                        <p>{{ $shop->shop_type->name }}</p>
                        <h5 class="title-section text-r18 text-muted">ข้อมูลติดต่อ</h5>

                        <p class="m-0 pb-1"><i class="fas fa-user text-muted mr-2"></i>{{ $user->first_name.' '.$user->last_name }}</p>
                        <p class="m-0 pb-1"><i class="fas fa-mobile-alt text-muted mr-2"></i><a class="text-black" href="tel:{{ $user->phone }}">{{ $user->phone }}</a></p>
                        <p class="m-0 pb-1"><i class="fas fa-map-marker-alt text-muted mr-2"></i>{{ $shop->full_address }}</p>
                    </article>
                    <div class="px-4">
                    </div>
                </main> <!-- col.// -->
            </div> <!-- row.// -->
        </div>
    </div>
</div>

@if( count($products) > 0 )
<div class="heading-line mt-4">
    <h3 class="title-section bg-background">สินค้า</h3>
</div>
@endif
<div class="row mb-4">
    <div class="col-12 text-center">
        <a href="{{ route('front.users.shops.products.create', $shop->id) }}" class="btn btn-success mt-2"><i class="fas fa-plus"></i> {{ __('เพิ่มข้อมูลสินค้า') }}</a>
    </div>
</div>
<div class="row">
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
                <a href="{{ route('front.users.shops.products.edit', [$shop->id,$product->id]) }}" class="btn btn-link btn-block bg-default border-topx-radius-0">แก้ไข</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@section('javascript')
@endsection