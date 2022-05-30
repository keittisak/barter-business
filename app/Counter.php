<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        "id,", "prefix", "mark", "counter"
    ];
}
