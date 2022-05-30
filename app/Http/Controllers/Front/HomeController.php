<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Branner;
use Illuminate\Support\Carbon;
class HomeController extends Controller
{
    public function index (Request $request)
    {
        $branners = Branner::when( !empty($request->user()), function($q){
            $q->where('after_login', 'y');
        })
        ->when( empty($request->user()), function($q){
            $q->where('after_login', 'n');
        })
        ->get();
        $data = [
            'branners' => $branners
        ];
        return view('front.home',$data);
    }

    public function telegram (Request $request)
    {
        return view('front.telegram');
    }
}
