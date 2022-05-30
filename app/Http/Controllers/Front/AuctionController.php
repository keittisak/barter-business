<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Auction;
use App\AuctionDetail;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AuctionController extends Controller
{
    public function index (Request $request)
    {
        $auctions = Auction::with(['winner_by_user','details' => function($q){
            // return $q->orderBy('created_at','desc')->limit(6);
        }])
        ->where('started_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->get();
        $data = [
            'auctions' => $auctions,
        ];
        return view('front.auctions.index', $data);
    }

    public function show (Request $request, $id)
    {
        $auction = Auction::with(['winner_by_user', 'details' => function($q){
            return $q->orderBy('id', 'desc')->limit(6);
        }, 'details.created_by_user'])->findOrFail($id);
        $latestPrice = $auction->details()->orderBy('id','desc')->first('amount');
        $remaining = strtotime($auction->expired_at) - time();
        $_remaining = $remaining;
        $days = floor($remaining/60/60/24);
        $hours   = floor( ($remaining-($days*60*60*24))/60/60 );
        $minutes = floor(($remaining-($days*60*60*24)-($hours*60*60))/60);
        $seconds = floor(($remaining-($days*60*60*24)-($hours*60*60))-($minutes*60));
        $remaining = [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ];
        $data = [
            'auction' => $auction,
            'remaining' => $remaining,
            'latest_price' => (count($auction->details) == 0) ? $auction->price : $latestPrice->amount,
            'is_expired' => ($_remaining < 0)
        ];
        return view('front.auctions.show', $data);
    }

    public function bidding (Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $validate = [
            'amount' => [
                'required',
                'numeric',
                function($attribute, $value, $fail) use($request, $auction) {
                    $auctionDetail = $auction->details()->orderBy('id', 'DESC')->first('amount');
                    $pintBalanceAmount = $request->user()->balances()->sum('total_amount');
                    $auctionAmount = $auction->price;
                    if( !empty($auctionDetail) ){
                        $auctionAmount = $auctionDetail->amount;
                    }
                    if($pintBalanceAmount < $auctionAmount){
                        return $fail('จำนวนเทรดบาทไม่เพียงพอ.');
                    }
                },
                function($attribute, $value, $fail) use($request, $auction) {
                    $auctionDetail = $auction->details()->orderBy('id', 'DESC')->first('amount');
                    if($auctionDetail){
                        if($request->amount <= $auctionDetail->amount){
                            return $fail('จำนวนเทรดบาทไม่ถูกต้องลองใหม่อีกครั้ง.');
                        }
                    }
                }
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $auction = DB::transaction(function() use($request, $data, $auction) {
                $result = $auction->update([
                    'winner' => $request->user()->id
                ]);
                $_request = new Request;
                $_request = $_request->merge([
                    'auction_id' => $auction->id,
                    'amount' => $data['amount'],
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);
                $detail = AuctionDetail::create($_request->toArray());
                $latestPrice = $auction->details()->orderBy('id','desc')->first('amount');
                $data = [
                    'auction' => Auction::with(['winner_by_user','details' => function($q){
                        $q->orderBy('id','desc')->limit(10);
                    }, 'details.created_by_user'])->findOrFail($auction->id),
                    'latest_price' => $latestPrice->amount
                ];

                $auctionRef = Http::get("https://barteradvance-e7bc5.firebaseio.com/auctions/{$auction->ref_id}.json");
                $auctionRef = json_decode($auctionRef->body());
                $bidQuantity = ($auctionRef->bid_quantity +1);
                $response = Http::put("https://barteradvance-e7bc5.firebaseio.com/auctions/{$auction->ref_id}.json",[
                    'bid_quantity' => $bidQuantity
                ]);
                return $data;
            });
            return response($auction, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function getAuction ($id)
    {
        try{
            $auction = DB::transaction(function() use($id) {
                $auction = Auction::with(['winner_by_user', 'details' => function($q){
                    return $q->orderBy('id', 'desc')->limit(10);
                }, 'details.created_by_user'])->findOrFail($id);
                $latestPrice = $auction->details()->orderBy('id','desc')->first('amount');
                $data = [
                    'auction' => $auction,
                    'latest_price' => (count($auction->details) == 0) ? $auction->price : $latestPrice->amount
                ];
                return $data;
            });
            return response()->json($auction);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function data (Request $request)
    {
        try{
            $auctions = DB::transaction(function() {
                $auctions = Auction::with(['winner_by_user','details' => function($q){
                    // return $q->orderBy('id', 'desc')->limit(6);
                }])
                ->where('started_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
                ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
                ->get();
                return $auctions;
            });
            return response()->json($auctions);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function winnerByUser (Request $request)
    {
        $auctions = Auction::with(['winner_by_user','details' => function($q){
            return $q->orderBy('id', 'desc');
        }])
        // ->where('winner', $request->user()->id)
        ->where('expired_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
        ->get();
        $data = [
            'auctions' => $auctions,
        ];
        return view('front.auctions.winner_by_user', $data);
    }

}
