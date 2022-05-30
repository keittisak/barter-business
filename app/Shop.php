<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        "id",
        "code",
        "name",
        "user_id",
        "type_id",
        "description",
        "image",
        "address",
        "country_id",
        "province_id",
        "district_id",
        "subdistrict_id",
        "postalcode",
        "phone",
        "full_address",
        "contact_name",
        "line_id",
        "facebook_id",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function shop_type()
    {
        return $this->belongsTo('App\ShopType', 'type_id');
    }
    public function created_by_user()
    {
        return $this->belongsTo('App\User', 'created_by')->withTrashed();
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by')->withTrashed();
    }

    public function images()
    {
        return $this->hasMany('App\ShopImage','shop_id');
    }

    public function products ()
    {
        return $this->hasMany('App\Product');
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function province () {
        return $this->belongsTo('App\Province', 'province_id');
    }

}
