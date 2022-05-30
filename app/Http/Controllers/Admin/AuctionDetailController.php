<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Auction;
use App\AuctionDetail;
use DB;
use Carbon\Carbon;
use DataTables;

class AuctionDetailController extends Controller
{
    public function data (Request $request)
    {
        $result = AuctionDetail::with(['created_by_user'])
        ->when( !empty($request->auction_id), function($q) use ($request){
            $q->where('auction_id', $request->auction_id);
        })
        ->when( !empty($request->sort_id), function($q) use ($request){
            $q->orderBy('id', $request->sort_id);
        });
        if( !$request->is_datatable ){
            return response()->json($result->get());
        }
        return DataTables::of($result)
        ->editColumn('created_at', function($result){
            return Carbon::createFromFormat('Y-m-d H:i:s', $result->created_at)->addYears(543)->format('d/m/Y H:i');
        })
        ->make(true);
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'auction_id' => [
                'required',
                'exists:auctions,id',
            ],
            'amount' => [
                'required',
                'numeric',
                function($attribute, $value, $fail) use($request) {
                    $auctionDetail = AuctionDetail::where('auction_id', $request->auction_id)->orderBy('id', 'DESC')->first('amount');
                    $pintBalanceAmount = $request->user()->balances()->sum('total_amount');
                    if($auctionDetail){
                        if($pintBalanceAmount < $auctionDetail->amount){
                            return $fail('จำนวนเทรดบาทไม่เพียงพอ.');
                        }
                    }
                },
                function($attribute, $value, $fail) use($request) {
                    $auctionDetail = AuctionDetail::where('auction_id', $request->auction_id)->orderBy('id', 'DESC')->first('amount');
                    if($auctionDetail){
                        if($request->amount < $auctionDetail->amount){
                            return $fail('จำนวนเทรดบาทไม่ถูกต้องลองใหม่อีกครั้ง.');
                        }
                    }
                }
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $detail = DB::transaction(function() use($request, $data) {
                $detail = AuctionDetail::create($data);
                return $detail;
            });
            return response($detail, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
