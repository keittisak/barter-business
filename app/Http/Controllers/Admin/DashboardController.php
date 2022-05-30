<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Balance;
use App\Trade;
use App\User;
use App\MembershipRequest;
use App\Point;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index (Request $request)
    {
        $balance = Balance::select(DB::raw('SUM(total_amount) as total_amount'), 'point_id')->with(['point'])->groupBy('point_id')->get();
        $today = Trade::where(DB::raw('DATE(created_at)'),'=', Carbon::now()->format('Y-m-d'))->where('status','success')->sum('total_amount');
        $yesterday = Trade::where(DB::raw('DATE(created_at)'),'=', Carbon::yesterday()->format('Y-m-d'))->where('status','success')->sum('total_amount');
        $thisMonth = Trade::whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->where('status','success')->sum('total_amount');
        // $lastMonth = Trade::whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->whereYear('created_at', Carbon::now()->subMonth()->format('Y'))->sum('total_amount');
        $tradeTotalAmount = Trade::where('status','success')->sum('total_amount');
        $countMemeber = User::whereHas('roles', function($q){
            $q->where('name', '=', 'member');
        })->count();
        $countMemeberToday = User::where(DB::raw('DATE(created_at)'),'=', Carbon::now()->format('Y-m-d'))->whereHas('roles', function($q){
            $q->where('name', '=', 'member');
        })->count();
        $countMemeberYesterday = User::where(DB::raw('DATE(created_at)'),'=', Carbon::yesterday()->format('Y-m-d'))->whereHas('roles', function($q){
            $q->where('name', '=', 'member');
        })->count();
        $countMembershipRequest = MembershipRequest::whereNull('approved_by')->count();
        return view('admin.dashboard',[
            'member_total' => $countMemeber,
            'member_today' => $countMemeberToday,
            'member_yesterday' => $countMemeberYesterday,
            'membership_request' => $countMembershipRequest,
            'balance' => $balance,
            'trade' => [
                'today' => $today,
                'yesterday' => $yesterday,
                'this_month' => $thisMonth,
                'total_amount' => $tradeTotalAmount
            ]
        ]);
    }
}
