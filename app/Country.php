<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'name_en',
    ];

    public function provinces()
    {
        return $this->hasMany('App\Province');
    }
}
