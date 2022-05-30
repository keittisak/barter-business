<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'trade_id',
        'user_id',
        'total_amount',
        'remark',
        'status',
        'transfered_img',
        'transfered_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }

    public function trade ()
    {
        return $this->belongsTo('App\Trade', 'trade_id')->withTrashed();
    }
}
