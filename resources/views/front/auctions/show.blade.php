@extends('front.layouts.main')
@section('title','Auctions') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ $auction->name }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row no-gutters">
                <aside class="col-sm-6 border-right">
                    <article class="gallery-wrap"> 
                        <div id="carouselIndicators" class="carousel slide  w-100 carousel-store" data-ride="carousel">
                            <div class="carousel-inner">
                                @for($i=1; $i<=4; $i++)
                                    @if( !empty($auction['image_'.$i]) )
                                    <div class="carousel-item {{ ($i==1) ? 'active':'' }}">
                                        <div class="img-big-wrap">
                                            <a><img src="{{$auction['image_'.$i]}}"></a>
                                        </div>
                                    </div>
                                    @endif
                                @endfor
                            </div>
                            <ol class="carousel-indicators" style="position: initial">
                                @for($i=1; $i<=4; $i++)
                                    @if( !empty($auction['image_'.$i]) )
                                        <li data-target="#carouselIndicators" data-slide-to="{{$i}}" class="{{ ($i==1) ? 'active':'' }}"></li>
                                    @endif
                                @endfor
                            </ol>
                        </div>
                    </article>
                </aside>
                
                <main class="col-sm-6">
                    <article class="content-body">
                        <div class="mb-4">
                            <h3 class="title mb-1">{{$auction->name}}</h3>
                        </div>
                        <h5 class="text-muted mb-1">เวลาคงเหลือ</h5>
                        <p class="text-muted mb-0">วัน : ชั่วโมง : นาที : วินาที</p>
                        <div class="row">
                            <div class="auction-timer">
                                <p id="days" class="customShadowBorder">{{$remaining['days']}}</p>
                                <span>:</span>
                                <p id="hours" class="customShadowBorder">{{$remaining['hours']}}</p>
                                <span>:</span>
                                <p id="minutes" class="customShadowBorder">{{$remaining['minutes']}}</p>
                                <span>:</span>
                                <p id="seconds" class="customShadowBorder">{{$remaining['seconds']}}</p>
                            </div>
                        </div>

                        <div class="row current-bid-block mt-4">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">ราคาล่าสุด:</p>
                                <h4 id="text-lastes-price">{{ number_format($latest_price)  }}</h4>
                            </div>
                            <div class="col-auto">
                                <p class="text-muted mb-1">ผู้ชนะในตอนนี้:</p>
                                <h5 id="winner-by-user">{!! (!empty($auction->winner)) ? '<i class="fas fa-trophy text-green mr-2"></i>'.$auction->winner_by_user['first_name'].' '.$auction->winner_by_user['last_name'] : '-' !!}</h5>
                            </div>
                        </div>
                     
                            <div class="row mt-4">
                                <div class="col-auto">
                                    <p class="text-muted mb-1">ราคาประมูล:</p>
                                </div>
                            </div>
                            @if(strtotime($auction->expired_at) >= strtotime(DATE('Y-m-d H:i:s')))
                                <div class="row mb-2" id="box-bid">
                                    <div class="col-7 col-md-8">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-default bg-white btn-minus">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        <input type="text" class="form-control text-center bg-white" id="amount" value="{{ number_format($latest_price+$auction->min_bid) }}" min="{{ ($latest_price+$auction->min_bid) }}" readonly>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-default bg-white btn-plus">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-success btn-block" id="btn-bid"><i class="fas fa-gavel"></i> ประมูล</button>
                                    </div>
                                </div>
                            @endif

                    </article>
                </main>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="text-muted">ประวัติการประมูล</h5>
                <div class="table-responsive">
                    <table class="table" id="auction-details-table">
                        <thead>
                            <tr>
                                <th style="width: 5px"></th>
                                <th>สมาชิก</th>
                                <th>จำนวนเทรดบาท</th>
                                <th>วันที่/เวลา</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auction->details as $key => $item)
                            <tr>
                                <td class="text-center">@if($key == 0)<i class="fas fa-trophy text-green"></i>@endif</td>
                                <td>{{ $item['created_by_user']['first_name'].' '.$item['created_by_user']['last_name'] }}</td>
                                <td>{{ number_format($item['amount']) }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime('+543 years', strtotime($item['created_at']) ) ) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card auction-detail">
            <div class="card-body">
                <h5 class="text-muted">รายละเอียด</h5>
                <div class="row">
                    <p class="col-auto">รหัสการประมูล</p>
                    <p class="col-auto">#{{$auction->id}}</p>
                </div>
                <div class="row my-2">
                    <div class="col-12">
                        <p>{{$auction->name}}</p>
                        <p>{{$auction->description}}</p>
                    </div>
                </div>
                <div class="row">
                    <p class="col-6">วันที่เริ่มประมูล:</p>
                    <p class="col-auto">{{ date('d/m/Y H:i', strtotime('+543 years', strtotime($auction->created_at) ) ) }}</p>
                </div>
                <div class="row">
                    <p class="col-6">วันที่จบการประมูล:</p>
                    <p class="col-auto">{{ date('d/m/Y H:i', strtotime('+543 years', strtotime($auction->expired_at) ) ) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.0.2/firebase-database.js"></script>
<script>
    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    var firebaseConfig = {
        apiKey: "AIzaSyCupw0rkW-8rnDfrrZbIOC35mHXHCZ8FnE",
        authDomain: "barteradvance-e7bc5.firebaseapp.com",
        databaseURL: "https://barteradvance-e7bc5.firebaseio.com",
        projectId: "barteradvance-e7bc5",
        appId: "1:511601199016:web:434fd9222ea9f2b990ff9d",
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    var auctionRef = firebase.database().ref('auctions/{{$auction->ref_id}}');
</script>
<script>
    // var countDownDate = new Date('{{$auction->expired_at}}').getTime();
    @if($is_expired)
        $('#days').html('00');
        $('#hours').html('00');
        $('#minutes').html('00');
        $('#seconds').html('00');
    @endif

    var countDownDate = moment('{{$auction->expired_at}}').valueOf();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        $('#days').html(days);
        $('#hours').html(hours);
        $('#minutes').html(minutes);
        $('#seconds').html(seconds);
        if (distance < 0) {
            clearInterval(x);
            $('#days').html('00');
            $('#hours').html('00');
            $('#minutes').html('00');
            $('#seconds').html('00');
            // $('#box-bid').remove();
        }
    },1000);

    $('#btn-bid').on('click',function(e){
        var __url = '{{ route("front.auctions.bidding","__id") }}';
        __url = __url.replace('__id', {{ $auction->id }})
        var __amount = $('#amount').val();
        __amount = __amount.replace(',','');
        $.ajax({
            url: __url,
            method: "POST",
            data:{
                    amount: __amount,
                    _token:'{{ csrf_token() }}'
                },
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            Swal.fire({
                icon:'success',
                title:'ประมูลสินค้าเรียบร้อย'
            }).then(function(){
                loader.close();
            });
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            if(errors){
                $.each(errors, function(key,v) {
                for( i=0; i < v.length; i++ ) {
                    Swal.fire(`คำเตือน`, v[i], `error`);
                }
            });
            }else {
                Swal.fire(`คำเตือน`, message, `error`);
            }
            // location.reload();
            loader.close();
        });
    });

    $(document).on('click', '.btn-minus',function(e){
        var amount = $('#amount').val();
        var minPrice = $('#amount').attr('min');
        var minBid = {{ $auction->min_bid }};
        amount = amount.replace(',','');
        amount = parseInt(amount) - parseInt(minBid);
        if( parseInt(amount) >= parseInt(minPrice) ){
            $('#amount').val(numberFormat(amount,0));
        }
    });

    $(document).on('click', '.btn-plus',function(e){
        var amount = $('#amount').val();
        var minBid = {{ $auction->min_bid }};
        amount = amount.replace(',','');
        amount = parseInt(amount) + parseInt(minBid);
        $('#amount').val(numberFormat(amount,0));
    });

    @if(count($auction->details) > 0)
    auctionRef.on('value', (snapshot) =>{
        var __url = '{{ route("front.auctions.get","__id") }}';
        __url = __url.replace('__id', {{ $auction->id }})
        $.ajax({
            url: __url,
            method: "GET",
            beforeSend: function( xhr ) {
                $('#btn-bid').addClass('disabled');
            }
        }).done(function(data){
            $('#text-lastes-price').html(numberFormat(data.latest_price,0));
            var winner = data.auction.winner_by_user.first_name+' '+data.auction.winner_by_user.last_name
            $('#winner-by-user').html('<i class="fas fa-trophy text-green mr-2"></i>'+winner);
            $('#auction-details-table');
            var elementDetails = ``;
            $.each(data.auction.details, function(key, val){
                elementDetails += `
                <tr>
                    <td class="text-center">${(key == 0)?`<i class="fas fa-trophy text-green"></i>`:''}</td>
                    <td>${val.created_by_user.first_name+' '+val.created_by_user.last_name}</td>
                    <td>${numberFormat(val.amount,0)}</td>
                    <td>${moment(val.created_at).add(543, 'years').format('DD/MM/YYYY H:mm')}</td>
                </tr>
                `;
            });
            $('#auction-details-table').find('tbody').html(elementDetails);
            var minBid = {{ $auction->min_bid }};
            var amount = parseInt(data.latest_price) + parseInt(minBid);
            $('#amount').attr('min',amount);
            $('#amount').val(numberFormat(amount,0));
            $('#btn-bid').removeClass('disabled');
        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            Swal.fire(`คำเตือน`, message, `error`);
            clearInterval(getData);
            $('#btn-bid').removeClass('disabled');
        });
    });
    @endif
</script>
@endsection