<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Branch;

class BranchController extends Controller
{
    public function about (Request $request)
    {
        $barnch = Branch::findOrFail(1);
        return view('front.about', $barnch);
    }
}
