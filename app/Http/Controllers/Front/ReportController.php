<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Balance;
use App\User;
use App\Trade;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Income;

class ReportController extends Controller
{
    public function income (Request $request)
    {
        return view('front.reports.income');
    }
    public function incomeData (Request $request)
    {
        $result = Income::with('user')->where('user_id', $request->user()->id)
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
                    return $result->created_at->format('d/m/Y H:i');
                })
                ->make(true);
    }

    public function purchase (Request $request)
    {
        return view('front.reports.purchase');
    }

    public function purchaseData (Request $request)
    {
        $result = Trade::with('seller_by_user')
            ->where('buyer_id', $request->user()->id)
            ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
                $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');
                return $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
            })
            ->get();

        return DataTables::of($result)
                ->addColumn('seller_full_name', function($result){
                    return $result->seller_by_user->first_name.' '.$result->seller_by_user->last_name;
                })
                ->editColumn('total_amount', function($result){
                    return number_format($result->total_amount);
                })
                ->editColumn('created_at', function($result){
                    return $result->created_at->format('d/m/Y H:i');
                })
                ->make(true);
    }

    public function sales (Request $request)
    {
        return view('front.reports.sales');
    }

    public function salesData (Request $request)
    {
        $result = Trade::with('buyer_by_user')
            ->where('seller_id', $request->user()->id)
            ->whereNotNull('created_by')
            ->when( !empty($request->start_date) && !empty($request->end_date), function($q) use ($request){
                $start_date = Carbon::createFromFormat('d/m/Y',$request->start_date)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d/m/Y',$request->end_date)->format('Y-m-d');
                return $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
            })
            ->get();

        return DataTables::of($result)
                ->addColumn('buyer_full_name', function($result){
                    return $result->buyer_by_user->first_name.' '.$result->buyer_by_user->last_name;
                })
                ->editColumn('total_amount', function($result){
                    return number_format($result->total_amount);
                })
                ->editColumn('created_at', function($result){
                    return $result->created_at->format('d/m/Y H:i');
                })
                ->make(true);
    }

    public function bbg (Request $request)
    {
        $user = User::count();
        $balance = Balance::sum('total_amount');
        $tradeTotalAmount = Trade::where('status','success')->sum('total_amount');
        $data = [
            'user' => $user,
            'balance' => $balance,
            'tradeTotalAmount' => $tradeTotalAmount
        ];
        return view('front.reports.bbg',$data);
    }
}
