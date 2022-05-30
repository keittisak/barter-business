<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use SoftDeletes;

class ShopImage extends Model
{
    protected $fillable = [
        'id',
        'shop_id',
        'image',
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at"
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
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
        return $this->belongsTo('App\User', 'deleted_by')->withTrashed();
    }
}
