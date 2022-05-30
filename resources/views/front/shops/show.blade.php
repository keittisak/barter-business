@extends('front.layouts.main')
@section('title', $shop->name) 
@section('css')
<style>
    .config-max-height {
        max-height: 6.125rem;
    }
    /* box-shadow: 0 3px 20px 0 rgba(18,106,211,.1); */
</style>
@endsection

@section('nav_header')
    <a href="{{ route('front.shops.category.show', $shop->type_id) }}" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ $shop->name }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row no-gutters">
                @if( count($images) )
                <aside class="col-sm-6 border-right">
                    <article class="gallery-wrap"> 
                        <div id="carouselIndicators" class="carousel slide  w-100 carousel-store" data-ride="carousel">
                            <div class="carousel-inner">
                                @for( $i=0; $i < count($images); $i++ )
                                    <div class="carousel-item @if( $i === 0 ) active @endif">
                                        <div class="img-big-wrap">
                                            <a><img src="{{ $images[$i]->image }}"></a>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <ol class="carousel-indicators" style="position: initial">
                                @for( $i=0; $i < count($images); $i++ )
                                    <li data-target="#carouselIndicators" data-slide-to="{{ $i }}"  @if( $i == 0 ) class="active" @endif></li>
                                @endfor
                            </ol>
                        </div>
                    </article>
                </aside>
                @endif

                <main class="col-sm-6">
                    <article class="content-body">
                        <h3 class="title">{{ $shop->name }}</h3>
                        <div class="rating-wrap mb-3">
                            {{-- <span class="badge badge-warning"> <i class="fa fa-star"></i> 3.8</span> --}}
                            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> กรุงเทพมหานคร, ประเทศไทย</small>
                        </div>
                        <h5 class="title-section text-r18">รหัสผู้ขาย #{{$user->code}}</h5>
                        <h5 class="title-section text-r18 text-muted">รายละเอียด</h5>

                        <p>{{ $shop->description }}</p>
                        @auth
                            <h5 class="title-section text-r18 text-muted">ข้อมูลติดต่อ</h5>
                            <p class="m-0 pb-1"><i class="fas fa-user text-muted mr-2"></i>{{ $user->first_name.' '.$user->last_name }}</p>
                            <p class="m-0 pb-1"><i class="fas fa-mobile-alt text-muted mr-2"></i><a class="text-black" href="tel:{{ $user->phone }}">{{ $user->phone }}</a></p>
                            <p class="m-0 pb-1"><i class="fas fa-map-marker-alt text-muted mr-2"></i>{{ $shop->full_address }}</p>
                        @endauth
                    </article>
                    <div class="px-4">
                    </div>
                </main> <!-- col.// -->
            </div> <!-- row.// -->
        </div>
    </div>
</div>
@if( count($products) > 0 )
<div class="heading-line mt-4 mb-2">
    <h3 class="title-section bg-background">สินค้า</h3>
</div>
@endif
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
            <div class="card-body px-2 pt-2 pb-3">
                <h5 class="product-title text-r16 text-center">{{ $product->name }}</h5>
                <div class="product-price text-center mt-3">{{ number_format($product->price) }}</div>
            </div>
            @auth
                @if(Auth::user()->id != $shop->user_id) 
                <div class="card-footer p-0">
                    @if($product->status == 'sold_out')
                    <a class="btn btn-link btn-block bg-default border-topx-radius-0"><s>ซื้อสินค้า</s></a>
                    @else 
                    <a href="{{ route('front.users.trade.form',['product_id'=> $product->id]) }}" class="btn btn-link btn-block bg-green border-topx-radius-0">ซื้อสินค้า</a>
                    @endif
                </div>
                @endif
            @endauth
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('javascript')
@endsection