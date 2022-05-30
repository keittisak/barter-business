<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Trade;
use App\Income;
use App\User;
use App\Balance;
use App\Billing;
use DB;
use DataTables;
use Carbon\Carbon;

class TradeController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.trades.index');
    }
    
    public function data (Request $request)
    {
        $result = Trade::with(['seller_by_user', 'buyer_by_user','billing'])
            ->when( !empty($request->buyer_id), function($q) use ($request) {
                $q->where('buyer_id', $request->buyer_id);
            })
            ->when( !empty($request->seller_id), function($q) use ($request) {
                $q->where('seller_id', $request->seller_id);
            })
            ->when( !empty($request->created_at), function($q) use ($request){
                $dateExplode = explode(' - ', $request->created_at);
                $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
                $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->when( !empty($request->status), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when( $request->user()->isSubAdmin(), function($q) use ($request){
                $q->whereNotIn('buyer_id', [1])->whereNotIn('seller_id', [1]);
            })
            ->get();
        return DataTables::of($result)
            ->editColumn('total_amount', function($result){
                return number_format($result->total_amount);
            })
            ->editColumn('created_at', function($result){
                return $result->created_at->format('d/m/Y H:i');
            })
            ->addColumn('buyer_full_name', function($result){
                if(!empty($result->buyer_by_user)){
                    return $result->buyer_by_user->first_name.' '.$result->buyer_by_user->last_name;
                }
                return '-';
            })
            ->addColumn('seller_full_name', function($result){
                if(!empty($result->seller_by_user)){
                    return $result->seller_by_user->first_name.' '.$result->seller_by_user->last_name;
                }
                return '-';
            })
            ->make(true);
    }


    public function show (Request $request, $id) 
    {
        $trade = Trade::with(['seller_by_user', 'buyer_by_user'])->firstOrFail($id);
        return response($trade,200);
    }

    public function purchaeData (Request $request)
    {
        $result = Trade::with(['seller_by_user', 'buyer_by_user'])
            ->when( !empty($request->user_id), function($q) use ($request) {
                $q->where('buyer_id', $request->user_id);
            })
            ->when( !empty($request->created_at), function($q) use ($request){
                $dateExplode = explode(' - ', $request->created_at);
                $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
                $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->get();
        return DataTables::of($result)
            ->editColumn('total_amount', function($result){
                return number_format($result->total_amount);
            })
            ->editColumn('created_at', function($result){
                return $result->created_at->format('d/m/Y H:i');
            })
            ->addColumn('user_full_name', function($result){
                if(!empty($result->seller_by_user)){
                    return $result->seller_by_user->first_name.' '.$result->seller_by_user->last_name;
                }
                return '-';
            })
            ->make(true);
    }

    public function salesData (Request $request)
    {
        $result = Trade::with(['seller_by_user', 'buyer_by_user'])
            ->when( !empty($request->user_id), function($q) use ($request) {
                $q->where('seller_id', $request->user_id);
            })
            ->when( !empty($request->created_at), function($q) use ($request){
                $dateExplode = explode(' - ', $request->created_at);
                $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
                $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->get();
        return DataTables::of($result)
            ->editColumn('total_amount', function($result){
                return number_format($result->total_amount);
            })
            ->editColumn('created_at', function($result){
                return $result->created_at->format('d/m/Y H:i');
            })
            ->addColumn('user_full_name', function($result){
                if(!empty($result->buyer_by_user)){
                    return $result->buyer_by_user->first_name.' '.$result->buyer_by_user->last_name;
                }
                return '-';
            })
            ->make(true);
    }

    public function cancel (Request $request, $id)
    {
        $trade = Trade::findOrFail($id);
        try{
            $trade = DB::transaction(function() use($request, $trade) {
                $data = [
                    'status' => 'cancel',
                ];
                if(isset($request->user()->id)){ $data['updated_by'] =  $request->user()->id; }
                $trade->update($data);

                $buyer = User::findOrFail($trade->buyer_id);
                if( $buyer->type == 2){
                    $balances = collect($buyer->balances);
                    $point = $balances->firstWhere('point_id',1);
                    $credit = $balances->firstWhere('point_id',2);
                    if( empty($credit) || $buyer->credit_total_amount ==  $credit->total_amount)
                    {
                        $balance = $buyer->balances()->where('point_id', 1)->first();
                        $balance->add($trade->total_amount);
                    }else
                    {
                        $creditBalanceAmount = ($buyer->credit_total_amount - $credit->total_amount);
                        if( ($creditBalanceAmount - $trade->total_amount) >= 0 ){
                            $balance = $buyer->balances()->where('point_id', 2)->first();
                            $balance->add($trade->total_amount);
                        }else{
                            $balance = $buyer->balances()->where('point_id', 2)->first();
                            $balance->add($creditBalanceAmount);
                            $balance = $buyer->balances()->where('point_id', 1)->first();
                            $balance->add( abs(($creditBalanceAmount - $trade->total_amount)) );
                        }
                    }
                }else{
                    $buyerBalance = $buyer->balances()->where('point_id', $trade->point_id)->firstOrFail();
                    $buyerBalance->add($trade->total_amount);
                }
                

                $seller = User::findOrFail($trade->seller_id);
                if( $seller->type == 2){
                    $balances = collect($seller->balances);
                    $point = $balances->firstWhere('point_id',1);
                    $credit = $balances->firstWhere('point_id',2);
                    if( ($point->total_amount - $trade->total_amount) < 0 ){
                        $balance = $seller->balances()->where('point_id', 1)->first();
                        $balance->reduce($point->total_amount);

                        $balance = $seller->balances()->where('point_id', 2)->first();
                        $balance->reduce( abs(($point->total_amount - $trade->total_amount)) );
                    }else{
                        $balance = $seller->balances->where('point_id', 1)->first();
                        $balance->reduce($trade->total_amount);
                    }
                }else{
                    $sellerBalance = $seller->balances()->where('point_id', $trade->point_id)->firstOrFail();
                    $sellerBalance->reduce($trade->total_amount);
                }

                $income = Income::where('trade_id', $trade->id);
                if( $income->exists() ) {
                    $income = $income->first();
                    $data = [
                        'status' => 'cancel',
                    ];
                    if(isset($request->user()->id)){ 
                        $data['updated_by'] =  $request->user()->id; 
                    }
                    $income->update($data);

                    // $balance = User::findOrFail($income->user_id)->balances()->where('point_id', $income->point_id)->firstOrFail();
                    // $balance->reduce($income->total_amount);
                }
                return $trade;
            });
            return response($trade, 200);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function create (Request $request)
    {
        return view ('admin.trades.form');
    }

    public function store (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('point_id' => 1));
            $request->merge(array('status' => 'success'));
        }
        $validate = [
            'point_id' => [
                'required',
                'exists:points,id'
            ],
            'total_amount' => [
                'required',
                'numeric',
                'min:1',
                function($attribute, $value, $fail) use($request) {
                    $buyerByUser = User::findOrFail($request->buyer_id);
                    $pintBalanceAmount = $buyerByUser->balances()->sum('total_amount');
                    if( $value > $pintBalanceAmount ) {
                        return $fail('จำนวนเทรดบาทไม่เพียงพอ.');
                    }
                }
            ],
            'seller_id' => [
                'required',
                'exists:users,id',
                function($attribute, $value, $fail) use($request) {
                    if($request->buyer_id == $request->seller_id){
                        return $fail('รหัสผู้ขายไม่ถูกต้อง.');
                    }
                }
            ],
            'buyer_id' => [
                'required',
                'exists:users,id',
                function($attribute, $value, $fail) use($request) {
                    $buyerByUser = User::findOrFail($request->buyer_id);
                    if($buyerByUser->isExpire()){
                        return $fail('รหัสผู้ซื้อหมดอายุ.');
                    }
                }
            ],
            'remark' => [
                // 'nullable',
                'required',
                'max:255'
            ],
            'created_by' => [
                'nullable'
            ],
            'updated_by' => [
                'nullable'
            ],
            'status' => [
                'in:success,cancel'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $trade = DB::transaction(function() use($request, $data, $user) {
                $counter = new Counter();
                $code = $counter->generateCode('bat');
                $data['code'] = $code;
                $trade = Trade::create($data);
                
                //-- Purchase --//
                $buyerByUser = User::findOrFail($request->buyer_id);
                if($buyerByUser->type == 2){
                    $balances = collect($buyerByUser->balances);
                    $point = $balances->firstWhere('point_id',1);
                    $credit = $balances->firstWhere('point_id',2);
                    if( ($point->total_amount - $request->total_amount) < 0 ){
                        $balance = $buyerByUser->balances()->where('point_id', 1)->first();
                        $balance->reduce($point->total_amount);

                        $balance = $buyerByUser->balances()->where('point_id', 2)->first();
                        $balance->reduce( abs(($point->total_amount - $request->total_amount)) );

                    }else{
                        $balance = $buyerByUser->balances->where('point_id', 1)->first();
                        $balance->reduce($request->total_amount);
                    }
                    if($buyerByUser->purchase_fee > 0){
                        $billingData = [
                            'code' => $counter->generateCode('bab'),
                            'trade_id' => $trade->id,
                            'total_amount' => $request->total_amount * ($buyerByUser->purchase_fee / 100),
                            'user_id' => $buyerByUser->id,
                            'status' => 'unpaid',
                            'remark' => 'ค่าธรรมเนียมการซื้อ '.$trade->remark.' จำนวน '.$buyerByUser->purchase_fee.'%',
                            'created_by' => $user->id,
                            'updated_by' => $user->id
                        ];
                        Billing::create($billingData);
                    }

                }else{
                    $balance = $buyerByUser->balances->where('point_id', 1)->first();
                    $balance->reduce($request->total_amount);
                }

                //-- Selling --//
                $sellingByUser = User::findOrFail($request->seller_id);
                if($sellingByUser->type == 2)
                {
                    $balances = collect($sellingByUser->balances);
                    $point = $balances->firstWhere('point_id',1);
                    $credit = $balances->firstWhere('point_id',2);
                    if( empty($credit) || $sellingByUser->credit_total_amount ==  $credit->total_amount)
                    {
                        $balance = $sellingByUser->balances()->where('point_id', 1)->first();
                        $balance->add($request->total_amount);
                    }else
                    {
                        $creditBalanceAmount = ($sellingByUser->credit_total_amount - $credit->total_amount);
                        if( ($creditBalanceAmount - $request->total_amount) >= 0 ){
                            $balance = $sellingByUser->balances()->where('point_id', 2)->first();
                            $balance->add($request->total_amount);
                        }else{
                            $balance = $sellingByUser->balances()->where('point_id', 2)->first();
                            $balance->add($creditBalanceAmount);
                            $balance = $sellingByUser->balances()->where('point_id', 1)->first();
                            $balance->add( abs(($creditBalanceAmount - $request->total_amount)) );
                        }
                    }
                    if( $sellingByUser->sales_fee > 0){
                        $billingData = [
                            'code' => $counter->generateCode('bab'),
                            'trade_id' => $trade->id,
                            'total_amount' => $request->total_amount * ($sellingByUser->sales_fee / 100),
                            'user_id' => $sellingByUser->id,
                            'status' => 'unpaid',
                            'remark' => 'ค่าธรรมเนียมการขาย '.$trade->remark.' จำนวน '.$sellingByUser->sales_fee.'%',
                            'created_by' => $user->id,
                            'updated_by' => $user->id
                        ];
                        Billing::create($billingData);
                    }

                }else
                {
                    $balance = $sellingByUser->balances()->where('point_id', $request->point_id)->first();
                    $balance->add($request->total_amount);
                }
                
                return $trade;
            });
            return response($trade, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function report (Request $request)
    {
        return view('admin.trades.report');
    }
    public function reportData (Request $request)
    {
        // ->when( !empty($request->created_at), function($q) use ($request){
        //     $dateExplode = explode(' - ', $request->created_at);
        //     $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
        //     $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
        //     $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        // })
        $mysql = 'SELECT id, code, first_name, last_name, IFNULL(tb_seller.total_amount,0) as seller, IFNULL(tb_buyer.total_amount,0) as buyer
            FROM users
            LEFT JOIN (SELECT seller_id as user_id, sum(total_amount) as total_amount FROM `trades` WHERE status = "success" GROUP BY seller_id) tb_seller
            ON users.id = tb_seller.user_id
            LEFT JOIN (SELECT buyer_id as user_id, sum(total_amount) as total_amount FROM `trades` WHERE status = "success" GROUP BY buyer_id) tb_buyer
            ON users.id = tb_buyer.user_id
            WHERE users.code <> 1 ';
        if($request->created_at){
            $dateExplode = explode(' - ', $request->created_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $mysql .= "AND  DATE(created_at) BETWEEN '{$startDate}' AND '{$endDate}'";
        }
        $result = DB::select($mysql);
        return DataTables::of($result)
        ->make(true);
    }
}

