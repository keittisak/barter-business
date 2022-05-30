<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionDetail extends Model
{
    protected $fillable = [
        'id',
        'auction_id',
        'amount',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function auction (){
        return $this->belongsTo('App\Auction', 'auction_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\User', 'created_by')->withTrashed();
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by')->withTrashed();
    }

    public function deleted_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by')->withTrashed();
    }
}
