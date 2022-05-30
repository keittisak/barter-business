<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ShopType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "name",
        "image",
        "status",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at"
    ];

    public function shops()
    {
        return $this->hasMany('App\Shop','type_id');
    }
}
