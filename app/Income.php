<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'id',
        'code',
        'trade_id',
        'point_id',
        'user_id',
        'total_amount',
        'remark',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function point()
    {
        return $this->belongsTo('App\point', 'point_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function trade()
    {
        return $this->belongsTo('App\Trade', 'trade_id');
    }
}
