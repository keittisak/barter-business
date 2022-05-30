<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Income;
use App\Balance;
use App\User;
use DB;
use DataTables;
use Carbon\Carbon;

class CreditController extends Controller
{
    public function form (Request $request)
    {
        return view ('admin.credits.form');
    }

    public function process (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('point_id' => 2));
            $request->merge(array('status' => 'success'));
        }
        $validate = [
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'point_id' => [
                'required',
                'exists:points,id'
            ],
            'total_amount' => [
                'required',
                'numeric',
                'min:1',
            ],
            'remark' => [
                'required'
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
            $income = DB::transaction(function() use($request, $data) {
                $counter = new Counter();
                $code = $counter->generateCode('bai');
                $data['code'] = $code;
                $income = Income::create($data);

                $user = User::findOrFail($request->user_id);
                $balance = $user->balances()->where('point_id', $request->point_id)->first();
                if( empty($balance) ){
                    $balance = Balance::create([
                        'point_id' => $request->point_id,
                        'user_id' => $request->user_id,
                        'total_amount' => $request->total_amount
                    ]);
                }else{
                    $balance->add($request->total_amount);
                }
                $user->update([
                    'credit_total_amount' => ($user->credit_total_amount + $request->total_amount)
                ]);
                return $income;
            });
            return response($income, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
