<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Image;
use App\Auction;
use App\AuctionDetail;
use DB;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Http;

class AuctionController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.auctions.index');
    }
    public function data (Request $request)
    {
        $result = Auction::with(['winner_by_user', 'details' => function($q){
            // return $q->orderBy('id', 'desc')->limit(6);
        }]);
        if( !empty($request->is_datatable) ){
            return response()->json($result->get());
        }
        return DataTables::of($result)
        ->editColumn('started_at', function($result){

            return Carbon::createFromFormat('Y-m-d H:i:s', $result->started_at)->addYears(543)->format('d/m/Y H:i');
        })
        ->editColumn('expired_at', function($result){
            return Carbon::createFromFormat('Y-m-d H:i:s', $result->expired_at)->addYears(543)->format('d/m/Y H:i');
        })
        ->addColumn('latest_price', function($request){
            return $request->details->sum('price');
        })
        ->make(true);
    }
    public function create (Request $request)
    {
        return view('admin.auctions.form');
    }
    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $request->merge(array('started_at' => $request->started_date.' '.$request->started_time.':00'));
        $request->merge(array('expired_at' => $request->expired_date.' '.$request->expired_time.':00'));
        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            'description' => [
                'nullable',
            ],
            'price' => [
                'required',
                'numeric',
                'min:1'
            ],
            'min_bid' => [
                'required',
                'numeric',
                'min:1'
            ],
            'image_1' => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'image_2' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'image_3' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'image_4' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'started_at' => [
                'required',
                'date_format:d/m/Y H:i:s'
            ],
            'expired_at' => [
                'required',
                'date_format:d/m/Y H:i:s'
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $auction = DB::transaction(function() use($request, $data) {
                $image = new Image();
                for($key=1; $key <= 4; $key++){
                    if( $request->hasFile("image_{$key}") ) {
                        $file = $request->file("image_{$key}");
                        $path = 'images/auctions/'.date('Ymd');
                        $imageUrl = $image->upload($file, $path, 'l');
                        $data["image_".($key)] = $imageUrl;
                    }
                }
                $data['started_at'] = Carbon::createFromFormat('d/m/Y H:i:s', $data['started_at'])->format('Y-m-d H:i:s');
                $data['expired_at'] = Carbon::createFromFormat('d/m/Y H:i:s', $data['expired_at'])->format('Y-m-d H:i:s');
                $auction = Auction::create($data);
                $auctionRef = Http::post("https://barteradvance-e7bc5.firebaseio.com/auctions.json",[
                    'bid_quantity' => 0
                ]);
                $auctionRef = json_decode($auctionRef->body());
                $auction->update([
                    'ref_id' => $auctionRef->name
                ]);
                return $auction;
            });
            return response($auction, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function show (Request $request, $id)
    {
        $auction = Auction::with(['winner_by_user', 'details' => function($q){
            return $q->orderBy('id', 'desc')->limit(10);
        }, 'details.created_by_user'])->findOrFail($id);
        $latestPrice = $auction->details()->orderBy('id','desc')->first('amount');
        $remaining = strtotime($auction->expired_at) - time();
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
            'latest_price' => (count($auction->details) == 0) ? $auction->price : $latestPrice->amount
        ];
        return view('admin.auctions.show', $data);
    }

    public function edit (Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $data = [
            'auction' => $auction
        ];
        return view('admin.auctions.form',$data);
    }

    public function update (Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $request->merge(array('started_at' => $request->started_date.' '.$request->started_time.':00'));
        $request->merge(array('expired_at' => $request->expired_date.' '.$request->expired_time.':00'));
        $validate = [
            'name' => [
                'required',
                'max:255'
            ],
            'description' => [
                'nullable',
            ],
            'price' => [
                'required',
                'numeric',
                'min:1'
            ],
            'min_bid' => [
                'required',
                'numeric',
                'min:1'
            ],
            'images_2' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'images_3' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'images_4' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'started_at' => [
                'required',
                'date_format:d/m/Y H:i:s'
            ],
            'expired_at' => [
                'required',
                'date_format:d/m/Y H:i:s'
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];
        if ($request->hasFile('image_1')) {
            $validate['image_1'] = [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $auction = DB::transaction(function() use($request, $data, $auction) {
                $image = new Image();
                for($key=1; $key <= 4; $key++){
                    if( $request->hasFile("image_{$key}") ) {
                        $file = $request->file("image_{$key}");
                        $path = 'images/auctions/'.date('Ymd');
                        $imageUrl = $image->upload($file, $path, 'l');
                        $data["image_".($key)] = $imageUrl;
                    }
                }
                $data['started_at'] = Carbon::createFromFormat('d/m/Y H:i:s', $data['started_at'])->format('Y-m-d H:i:s');
                $data['expired_at'] = Carbon::createFromFormat('d/m/Y H:i:s', $data['expired_at'])->format('Y-m-d H:i:s');
                $auction->update($data);
                return $auction;
            });
            return response($auction, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
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

    public function destroy (Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        try{
            $auction->delete();
            return response('','204');
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
