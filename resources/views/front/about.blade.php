@extends('front.layouts.main')
@section('title','About') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">Barter Advance</h5>
@endsection

@section('content')
{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {!!$description!!}
            </div>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-md-6 offset-md-3 col-12">
        <div id="accordion">
            @php
                $i = 0;
            @endphp
            @foreach(json_decode($about) as $key => $item)
            <div class="card">
                <div class="card-header" id="heading{{$key}}">
                    <button class="btn btn-link btn-block text-left text-r20 px-0 text-green" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                        {{$item->title}}
                    </button>
                </div>
                <div id="collapse{{$key}}" class="collapse @if($i==0) show @endif" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                    <div class="card-body">
                        {!!$item->description!!}
                    </div>
                </div>
            </div>
            @php
                $i++;
            @endphp
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection