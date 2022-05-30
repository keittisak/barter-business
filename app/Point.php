<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = [
        'id',
        'name',
        'total_amount',
        'trade_total_amount',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function point_balances()
    {
        return $this->hasMany('App\PointBlance', 'point_id');
    }

    
}
