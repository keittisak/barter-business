<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class Counter
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateCode($prefix=null, $mark=null, $digit=null)
    {
        $prefix = is_null($prefix) ? "ba" : strtolower(trim($prefix));
        $mark = is_null($mark) ? Carbon::today()->format('ymd') : strtolower(trim($mark));
        $digit = is_null($digit) ? 5 : $digit;
        $counter = \App\Counter::where('prefix', $prefix)->where('mark', $mark)->first();

        if ($counter){
            $number = $counter->counter;
        }else{
            $counter = new \App\Counter();
            $number = 0;
        }
        $number = $number + 1;
        $counter->prefix = $prefix;
        $counter->mark = $mark;
        $counter->counter = $number;
        $counter->save();

        return $prefix . $mark . str_pad($number, $digit, '0', STR_PAD_LEFT);
    }

    public function next($prefix=null, $mark=null, $digit=null)
    {
        $prefix = is_null($prefix) ? "ba" : strtolower(trim($prefix));
        $mark = is_null($mark) ? Carbon::today()->format('ymd') : strtolower(trim($mark));
        $digit = is_null($digit) ? 5 : $digit;
        $counter = \App\Counter::where('prefix', $prefix)->where('mark', $mark)->first();

        if ($counter){
            $number = $counter->counter;
        }else{
            $counter = new \App\Counter();
            $number = 0;
        }
        $number = $number + 1;
        $counter->prefix = $prefix;
        $counter->mark = $mark;
        $counter->counter = $number;
        $counter->save();

        return $number;
    }

}
