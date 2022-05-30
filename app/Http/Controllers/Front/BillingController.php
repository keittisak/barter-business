<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;
use App\Billing;
use DB;
use DataTables;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index (Request $request)
    {
        return view('front.billing.index');
    }

    public function data (Request $request)
    {
        $result = Billing::where('user_id', $request->user()->id)
        // ->when( !empty($request->status), function($q) use ($request) {
        //     $q->where('status', $request->status);
        // })
        ->where('status', '<>', 'cancel')
        ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
            $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');
            return $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
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
        ->make(true);
    }

    public function paymentForm (Request $request, $id)
    {
        $billing = Billing::where('user_id', $request->user()->id)->findOrFail($id);
        return view('front.billing.form', $billing);
    }

    public function update (Request $request, $id)
    {
        $billing = Billing::where('user_id', $request->user()->id)->findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'date' => [
                'required',
                'date_format:d/m/Y' 
            ],
            'time' => [
                'required',
                'date_format:H:i' 
            ],
            'image' => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ],
            'updated_by' => [

            ]
        ];
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
                }
                $transfered_at = Carbon::createFromFormat('d/m/Y H:i', $data['date'].' '.$data['time']);
                $data['transfered_at'] = $transfered_at;
                $data['status'] = 'pending';
                $billing->update($data);
                return $billing;
            });
            return response($billing, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
