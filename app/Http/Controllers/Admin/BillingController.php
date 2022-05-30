<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;
use App\Billing;
use DB;
use DataTables;
use Carbon\Carbon;
use App\User;

class BillingController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.billing.index');
    }

    public function data (Request $request)
    {
        $result = Billing::with(['user','trade'])
        ->when( !empty($request->user_id), function($q) use ($request){
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
        ->get();
        return DataTables::of($result)
            ->editColumn('total_amount', function($result){
                return number_format($result->total_amount);
            })
            ->editColumn('created_at', function($result){
                return $result->created_at->addYears(543)->format('d/m/Y H:i');
            })
            ->editColumn('transfered_at', function($result){
                return $result->created_at->addYears(543)->format('d/m/Y H:i');
            })
            ->addColumn('user_full_name', function($result){
                if(!empty($result->user)){
                    return $result->user->first_name.' '.$result->user->last_name;
                }
                return '-';
            })
            ->addColumn('user_code' ,function($result){
                return $result->user->code;
            })
            ->make(true);
    }

    public function create (Request $request)
    {
        return view('admin.billing.form');
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
            $request->merge(array('status' => 'unpaid'));
        }
        $validate = [
            'total_amount' => [
                'required',
                'numeric',
                'min:1',
            ],
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'trade_id' => [
                'exists:trades,id',
                'nullable'
            ],
            'remark' => [
                'required'
            ],
            'status' => [
                'in:paid,pending,unpaid,cancel'
            ],
            'transfered_img' => [
                'nullable'
            ],
            'transfered_at' => [
                'nullable'
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
            $billing = DB::transaction(function() use($request, $data) {
                $counter = new Counter();
                $code = $counter->generateCode('bab');
                $data['code'] = $code;
                $billing = Billing::create($data);
                return $billing;
            });
            return response($billing, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request, $id)
    {
        $billing = Billing::with(['user'])->findOrFail($id);
        return view('admin.billing.update', $billing);
    }

    public function update (Request $request, $id)
    {
        $billing = Billing::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'status' => [
                'in:paid,pending,unpaid,cancel'
            ],
            'date' => [
                'required',
                'date_format:d/m/Y' 
            ],
            'time' => [
                'required',
                'date_format:H:i' 
            ],
            // 'image' => [
            //     'required',
            //     'mimes:jpeg,bmp,png',
            //     'max:6000',
            // ],
            'updated_by' => [

            ],
            'remark' => [
                'required'
            ],

        ];
        if ($request->hasFile('image')) {
            $validate['image'] = [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $billing = DB::transaction(function() use($request, $data, $billing) {
                $image = new Image();
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'images/billing/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path);
                    $data['transfered_img'] = $imageUrl;
                }else{
                    $data['transfered_img'] = $request->image_url;
                }
                $transfered_at = Carbon::createFromFormat('d/m/Y H:i', $data['date'].' '.$data['time']);
                $data['transfered_at'] = $transfered_at;
                $billing->update($data);
                return $billing;
            });
            return response($billing, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function changeStatus (Request $request, $id)
    {
        $billing = Billing::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'status' => [
                'in:paid,unpaid,cancel'
            ],
            'updated_by' => [
                'nullable'
            ],
        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $billing = DB::transaction(function() use($request, $data, $billing) {
                $billing->update($data);
                return $billing;
            });
            return response($billing, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function show ($id)
    {
        try{
            $billing = Billing::findOrfail($id);
            return response($billing, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function makeBilling(Request $request)
    {
        if(Carbon::now()->format('Y-m-d') != '2021-01-27'){
            return response('',500);
        }
        $users = User::whereHas('roles', function($q){
            $q->where('name','member');
        })->orderBy('code', 'asc')->get();
        $counter = new Counter();
        foreach($users as $user){
            $code = $counter->generateCode('bab');
            $data['code'] = $code;
            Billing::create([
                'code' => $code,
                'total_amount' => 600,
                'user_id' => $user->id,
                'remark' => 'ค่าธรรมเนียม ราย 6 เดือน',
                'status' => 'pending'
            ]);
        }
    }
    
}
