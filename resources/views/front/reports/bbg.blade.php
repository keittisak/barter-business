@extends('front.layouts.main')
@section('title','BA Report') 
@section('css')
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ __('รายงาน BA') }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2 col-12">
        <div class="row">
            {{-- <div class="col-12">
                <div class="small-box bg-success small-box-report-bbg">
                    <div class="inner">
                      <h3>{{ number_format($balance) }}</h3>
      
                      <p class="text-r16">จำนวนเทรดบาท ในระบบ</p>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12">
                <div class="small-box bg-success small-box-report-bbg">
                    <div class="inner">
                      <h3>{{ number_format($tradeTotalAmount) }}</h3>
      
                      <p class="text-r16">จำนวนเทรดบาท ยอดแลกเปลี่ยนสินค้า</p>
                    </div>
                </div>
            </div> --}}
            <div class="col-12">
                <div class="small-box bg-success small-box-report-bbg">
                    <div class="inner">
                      <h3>{{ number_format($user) }}</h3>
      
                      <p class="text-r16">สมาชิกทั้งหมด</p>
                    </div>
                    {{-- <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p>ข้อมูลล่าสุด {{date('d/m/Y H:i')}}</p>
                        <p>Barter Advance เริ่มดำเนินการธุรกิจตั้งแต่ 5 พฤษจิกายน 2563</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
@endsection