<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{    
    protected $primaryKey = 'id';
    protected $fillable = [
        'district_id',
        'name',
        'name_en',
        'postalcode'
    ];

    public function district()
    {
        return $this->belongsTo('App\District');
    }

    public function couriers()
    {
        return $this->belongsToMany('App\Courier');
    }
}
