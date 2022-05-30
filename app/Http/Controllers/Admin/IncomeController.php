<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Income;
use App\User;
use DB;
use DataTables;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.incomes.index');
    }

    public function data (Request $request)
    {
        $result = Income::with(['user'])
        ->when( !empty($request->user_id), function($q) use ($request) {
            $q->where('user_id', $request->user_id);
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
            $q->whereNotIn('user_id', [1]);
        })
        ->get();
        return DataTables::of($result)
            ->editColumn('total_amount', function($result){
                return number_format($result->total_amount);
            })
            ->editColumn('created_at', function($result){
                return $result->created_at->format('d/m/Y H:i');
            })
            ->addColumn('transaction_by', function($result){
                return '-';
            })
            ->addColumn('user_full_name', function($result){
                if(!empty($result->user)){
                    return $result->user->first_name.' '.$result->user->last_name;
                }
                return '-';
            })
            ->make(true);
    }

    public function create (Request $request)
    {
        $user = User::findOrFail(1);
        $data = [
            'user' => $user
        ];
        return view('admin.incomes.form', $data);
    }

    public function store (Request $request)
    {
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
            ],
            'user_code' => [
                'required',
                'exists:users,code'
            ],
            'trade_id' => [
                'exists:trades,id',
                'nullable'
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
                $user = User::where('code', $request->user_code)->firstOrFail();
                $data['code'] = $code;
                $data['user_id'] = $user->id;
                $income = Income::create($data);
                $balance = $user->balances()->where('point_id', $request->point_id)->first();
                $balance->add($request->total_amount);
                return $income;
            });
            return response($income, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function cancel (Request $request, $id)
    {
        $income = Income::findOrFail($id);
        try{
            $income = DB::transaction(function() use($request, $income) {
                $data = [
                    'status' => 'cancel',
                ];
                if(isset($request->user()->id)){ $data['updated_by'] =  $request->user()->id; }
                $user = User::findOrFail($income->user_id);
                $balance = $user->balances()->where('point_id', $income->point_id)->firstOrFail();
                if( ($balance->total_amount - $income->total_amount) > 1 ){
                    $income->update($data);
                    $balance->reduce($income->total_amount);
                    if( $income->point_id == 2){
                        $user->update([
                            'credit_total_amount' => ($user->credit_total_amount - $income->total_amount)
                        ]);
                    }
                }
                return $income;
            });
            if($income->status != 'cancel'){
                return response(['message' => 'ไม่สามารถยกเลิกรายได้เลขที่ #'.$income->id.' เนื่องจากเทรดบาทสมาชิกไม่เพียงพอ'],422);
            }
            return response($income, 204);
        } catch (\Exception $e) {   
            return response(['message' => $e->getMessage()],500);
        }
    }
}
