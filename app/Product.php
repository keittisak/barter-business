<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'shop_id',
        'description',
        'price',
        'discount',
        'image',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function shop ()
    {
        return $this->belongsTo('App\Shop', 'shop_id');
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
