@extends('front.layouts.main')
@section('title','Category') 
@section('css')
<style>
    .set-max-height {
        /* max-height: 50px; */
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">ร้านค้า</h5>
@endsection

@section('content')
<div class="row">
    @foreach($shopTypes as $item)
    <div class="col-md-6">
        <div class="info-box category-box">
            <span class="info-box-icon">
                {{-- <img class="set-max-height" src="{{ !empty($item->image) ? $item->image : asset('assets/images/default.jpg') }}"> --}}
                <div class="avatars avatars-xxl d-block my-0 mx-auto">
                    <div class="avatars-one" style="background-image: url({{ empty($item->image) ? asset('assets/images/default.jpg') : $item->image }}"></div>
                  </div>
            </span>
            <div class="info-box-content pl-3">
                <span class="info-box-text text-r18">{{ $item->name }}</span>
                <span class="info-box-number text-r14 text-dark mt-2">{{ count($item->shops) }} ร้านค้า</span>
            </div>
            @auth
            <a href="{{ route('front.shops.category.show', $item->id) }}" class="stretched-link"></a>
            @endauth
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('javascript')
@endsection