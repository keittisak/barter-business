<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{    
    protected $primaryKey = 'id';
    protected $fillable = [
        'country_id',
        'name',
        'name_en'
    ];

    public function districts()
    {
        return $this->hasMany('App\District');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
