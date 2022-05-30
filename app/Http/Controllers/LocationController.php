<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\Province;
use App\District;
use App\Subdistrict;

class LocationController extends Controller
{
    public function genJson (Request $request)
    {

        $countries = Country::all();
        $countriesArray = array();
        foreach($countries as $data)
        {
            $countriesArray[] = $data;
        }

        $fp = fopen('assets/json/locations/countries.json', 'w');
        fwrite($fp, json_encode($countriesArray));
        fclose($fp);

        $provinces = Province::all();
        $provinceArray = array();
        foreach($provinces as $data)
        {
            $provinceArray[216][] = $data;
        }

        $fp = fopen('assets/json/locations/provinces.json', 'w');
        fwrite($fp, json_encode($provinceArray));
        fclose($fp);


        $districts = District::all();
        $districtArray = array();
        foreach($districts as $data)
        {
            $districtArray[$data->province_id][] = $data;
        }

        $fp = fopen('assets/json/locations/districts.json', 'w');
        fwrite($fp, json_encode($districtArray));
        fclose($fp);


        $subdistricts = Subdistrict::all();
        $subdistrictArray = array();
        foreach($subdistricts as $data)
        {
            $subdistrictArray[$data->district_id][] = $data;
        }

        $fp = fopen('json/locations/subdistricts.json', 'w');
        fwrite($fp, json_encode($subdistrictArray));
        fclose($fp);
        return response('success',200);
    }
}
