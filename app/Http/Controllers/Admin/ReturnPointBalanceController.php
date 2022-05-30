<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Balance;
use App\ReturnPointBalance;
use App\ReturnPointBalanceDetail;
use App\User;
use DB;
use Carbon\Carbon;
use DataTables;

class ReturnPointBalanceController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.return_point_balances.index');
    }

    public function data (Request $request)
    {
        $result = ReturnPointBalance::with(['user', 'details']);
        $result->when( !empty($request->created_at), function($q) use ($request){
            $dateExplode = explode(' - ', $request->created_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        });
        return DataTables::of($result)->make(true);
    }

    public function create ()
    {
        return view('admin.return_point_balances.form');
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'points.*.id' => [
                'required',
                'exists:balances,id',
            ],
            'points.*.before_total_amount' => [
                'required',
                'numeric',
                'min:0'
            ],
            'points.*.after_total_amount' => [
                'required',
                'numeric',
                'min:0'
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
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $result = DB::transaction(function() use($request, $data) {
                $result = ReturnPointBalance::create($data);
                foreach( $request->points as $item ){
                    $balance = Balance::findOrFail($item['id']);
                    $result->details()->create([
                        'balance_id' => $balance->id,
                        'point_id' => $balance->point_id,
                        'before_total_amount' => $item['before_total_amount'],
                        'after_total_amount' => $item['after_total_amount'],
                        'created_by' => $request->created_by,
                        'updated_by' => $request->created_by
                    ]);
                    $balance->update([
                        'total_amount' => $item['after_total_amount']
                    ]);

                    if( $balance->point_id == 2){
                        $user = User::findOrFail($request->user_id);
                        $user->update([
                            'credit_total_amount' => $item['after_total_amount']
                        ]);
                    }
                }
                return $result;
            });
            return response($result, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
