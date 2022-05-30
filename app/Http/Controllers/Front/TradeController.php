<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Trade;
use App\Income;
use App\User;
use DB;
use Carbon\Carbon;
use App\Branch;
use App\Billing;
use App\Product;

class TradeController extends Controller
{
    public function create (Request $request)
    {
        $data = array();
        if( !empty($request->product_id) ) {
            $product = Product::with('shop')->find($request->product_id);
            if($product) { $data['product'] = $product; }
        }
        
        $countBill = Billing::where('user_id', $request->user()->id)
                ->whereNotNull('trade_id')
                ->whereIn('status', ['unpaid','pending'])
                ->whereDate('created_at', '<', Carbon::now()->subDays(7)->format('Y-m-d'))
                ->count();
        $data['countBill'] = $countBill;
        return view('front.trades.form', $data);
    }

    public function store (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('point_id' => 1));
            $request->merge(array('buyer_id' => $request->user()->id));
            $request->merge(array('status' => 'success'));
        }
        $sellingByUserId = User::where('code', $request->seller_code)->firstOrFail()->id;
        $request->merge(array('seller_id' => $sellingByUserId));

        $validate = [
            'point_id' => [
                'required',
                'exists:points,id'
            ],
            'total_amount' => [
                'required',
                'numeric',
                'min:1',
                function($attribute, $value, $fail) use($request, $user) {
                    $pintBalanceAmount = $user->balances()->sum('total_amount');
                    if( $value > $pintBalanceAmount ) {
                        return $fail('จำนวนเทรดบาทไม่เพียงพอ.');
                    }
                }
            ],
            'seller_code' => [
                'required',
                'exists:users,code',
                function($attribute, $value, $fail) use($request, $sellingByUserId) {
                    if( $request->buyer_id == $sellingByUserId ) {
                        return $fail('รหัสผู้ขายไม่ถูกต้อง.');
                    }
                }
            ],
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->password)) {
                        return $fail(__('รหัสผ่านไม่ถูกต้อง'));
                    }
                }
            ],
            'seller_id' => [
                'required',
                'exists:users,id'
            ],
            'buyer_id' => [
                'required',
                'exists:users,id'
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
                event(new \App\Events\TradeNotification(Trade::with(['seller_by_user','buyer_by_user'])->find($trade->id)));
                
                //-- Purchase --//
                if($user->type == 2){
                    $balances = collect($user->balances);
                    $point = $balances->firstWhere('point_id',1);
                    $credit = $balances->firstWhere('point_id',2);
                    if( ($point->total_amount - $request->total_amount) < 0 ){
                        $balance = $user->balances()->where('point_id', 1)->first();
                        $balance->reduce($point->total_amount);

                        $balance = $user->balances()->where('point_id', 2)->first();
                        $balance->reduce( abs(($point->total_amount - $request->total_amount)) );

                    }else{
                        $balance = $user->balances->where('point_id', 1)->first();
                        $balance->reduce($request->total_amount);
                    }
                    if($user->purchase_fee > 0){
                        $billingData = [
                            'code' => $counter->generateCode('bab'),
                            'trade_id' => $trade->id,
                            'total_amount' => $request->total_amount * ($user->purchase_fee / 100),
                            'user_id' => $user->id,
                            'status' => 'unpaid',
                            'remark' => 'ค่าธรรมเนียมการซื้อ '.$trade->remark.' จำนวน '.$user->purchase_fee.'%',
                            'created_by' => $user->id,
                            'updated_by' => $user->id
                        ];
                        Billing::create($billingData);
                    }

                }else{
                    $balance = $user->balances->where('point_id', 1)->first();
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

                //-- Comition --//
                // if( !empty($sellingByUser->recommended_by) ){
                //     $_total_amount = $data['total_amount'] * $branch->commission;
                //     $code = $counter->generateCode('bai');
                //     $_request = new Request();
                //     $_request->merge([
                //         'code' => $code,
                //         'point_id' => $request->point_id,
                //         'trade_id' => $trade->id,
                //         'total_amount' => $_total_amount,
                //         'user_id' => $sellingByUser->recommended_by,
                //         'remark' => 'ได้รับเทรดบาท '.($branch->commission*100).'% จากการซื้อขายของสมาชิกที่ได้แนะนำ #'.$sellingByUser->first_name.' '.$sellingByUser->last_name,
                //         'status' => 'success'
                //     ]);
                //     Income::create($_request->toArray());
                //     $balance = User::findOrFail($sellingByUser->recommended_by)->balances()->where('point_id', $_request->point_id)->first();
                //     $balance->add($_request->total_amount);
                // }
                
                return $trade;
            });
            return response($trade, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function slip (Request $request, $id)
    {
        $code = base64_decode($id);
        $trade = Trade::with(['seller_by_user', 'buyer_by_user'])
        ->where('code',$code)
        ->firstOrFail();
        return view ('front.trades.slip',['data' => $trade]);
    }

    public function show (Request $request, $id) 
    {
        $code = base64_decode($id);
        $trade = Trade::with(['seller_by_user', 'buyer_by_user'])
        ->where('code',$code)
        ->firstOrFail();
        return response($trade,200);
    }
}
