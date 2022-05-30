<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Trade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'point_id',
        'total_amount',
        'remark',
        'status',
        'seller_id',
        'buyer_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function point()
    {
        return $this->belongsTo('App\point', 'point_id');
    }

    public function seller_by_user()
    {
        return $this->belongsTo('App\User', 'seller_id')->withTrashed();
    }

    public function buyer_by_user()
    {
        return $this->belongsTo('App\User', 'buyer_id')->withTrashed();
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\User', 'created_by')->withTrashed();
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by')->withTrashed();
    }

    public function billing ()
    {
        return $this->belongsTo('App\Billing', 'trade_id')->withTrashed();
    }

}
