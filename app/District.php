<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{    
    protected $primaryKey = 'id';
    protected $fillable = [
        'province_id',
        'name',
        'name_en'
    ];

    public function subdistricts()
    {
        return $this->hasMany('App\Subdistrict');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }
}
