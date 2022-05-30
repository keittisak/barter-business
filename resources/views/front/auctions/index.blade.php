@extends('front.layouts.main')
@section('title','Auctions') 
@section('css')
<style>
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">ประมูลสินค้า</h5>
@endsection

@section('content')
<div class="row">
    @foreach($auctions as $auction)
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card card-sm card-product-grid" id="{{$auction->ref_id}}">
            <div class="img-wrap">
                <img src="{{ $auction->image_1 }}">
            </div>
            <div class="card-body px-2 pt-2">
                <h5 class="product-title text-r16 text-center">{{ $auction->name }}</h3>
                <div class="product-price text-center mt-3 text-green lastes-price"><i class="fas fa-gavel"></i> {{ (count($auction->details) > 0) ? number_format($auction->details[count($auction->details)-1]->amount) : number_format($auction->price) }}</div>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">สิ้นสุดการประมูล</p>
                <p class="mb-0 auction-expired-at" data-date="{{$auction->expired_at}}" data-ref-id="{{$auction->ref_id}}">{{ date('d/m/Y H:i', strtotime('+543 years', strtotime($auction->expired_at) ) ) }}</p>
            </div>
            <a href="{{ route('front.auctions.show', $auction->id) }}" class="stretched-link"></a>
        </div>
    </div>
    @endforeach

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
    var auctionRef = firebase.database().ref('auctions');
</script>
<script>
    var x = setInterval(function() {
        $('.auction-expired-at').each(function(key, element){
            // console.log(element)
            // var countDownDate = new Date($(element).data('date')).getTime();
            var countDownDate = moment($(element).data('date')).valueOf();
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            $(element).html(`${days}:${hours}:${minutes}:${seconds}`);
            if (distance < 0) {
                $(element).parents('.card').remove();
            }
        });
    },1000);

    auctionRef.on('value', (snapshot) =>{
        var __url = '{{ route("front.auctions.data") }}';
        $.ajax({
            url: __url,
            method: "GET",
            beforeSend: function( xhr ) {

            }
        }).done(function(data){
            $.each(data, function(key, item){
                $(`#${item.ref_id}`).find('.lastes-price').html(`<i class="fas fa-gavel"></i> ${ (item.details[0] != undefined) ? numberFormat(item.details[(item.details).length-1].amount,0) : numberFormat(item.price,0) }`);
            })

        }).fail(function( jqxhr, textStatus ) {
            var message = jqxhr.responseJSON.message
            var errors = jqxhr.responseJSON.errors
            Swal.fire(`คำเตือน`, message, `error`);
        });
    });
</script>
@endsection