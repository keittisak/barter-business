@extends('admin.layouts.main')
@section('title','Dashboard') 
@section('css')
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>จำนวนเทรดบาท</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($balance[0]->total_amount) }}</h3>
                        <p class="text-center">เทรดบาท</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($balance[1]->total_amount) }}</h3>
                        <p class="text-center">เทรดบาทเครดิต</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($balance[0]->total_amount+$balance[1]->total_amount) }}</h3>
                        <p class="text-center">รวมทั้งหมด</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>การซื้อขาย</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($trade['today']) }}</h3>
                        <p class="text-center">วันนี้</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($trade['yesterday']) }}</h3>
                        <p class="text-center">เมื่อวาน</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($trade['this_month']) }}</h3>
                        <p class="text-center">เดือนนี้</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($trade['total_amount']) }}</h3>
                        <p class="text-center">รวมทั้งหมด</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>จำนวนสมาชิก</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($membership_request) }}</h3>
                        <p class="text-center">คำขอสมัครสมาชิก</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($member_today) }}</h3>
                        <p class="text-center">วันนี้</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($member_yesterday) }}</h3>
                        <p class="text-center">เมื่อวาน</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="small-box bg-white">
                    <div class="inner">
                        <h3 class="text-center mt-3">{{ number_format($member_total) }}</h3>
                        <p class="text-center">สมาชิกทั้งหมด</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
@endsection
