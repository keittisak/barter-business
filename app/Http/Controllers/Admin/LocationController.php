<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function province (Request $request)
    {
        $url = asset('assets/json/locations/provinces.json');
        $datos = file_get_contents($url);
        $data = json_decode($datos, true);
        return response()->json($data);
    }
}
