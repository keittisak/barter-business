<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{    
    protected $fillable = [
        "id,", "name", "commission", "trade_fee", "renewal_fee", "new_member", "recommend", 'description', 'about', 'created_by', 'updated_by','created_at','updated_at',
    ];

    public function updated_by_user()
    {
        return $this->belongsTo('App\User', 'updated_by')->withTrashed();
    }
}
