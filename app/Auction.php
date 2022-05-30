<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'min_bid',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'started_at',
        'expired_at',
        'winner',
        'ref_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function details () {
        return $this->hasMany('App\AuctionDetail');
    }

    public function isExpire () {

    }

    public function winner_by_user()
    {
        return $this->belongsTo('App\User', 'winner')->withTrashed();
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
