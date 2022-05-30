<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnPointBalance extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'remark',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function details()
    {
        return $this->hasMany('App\ReturnPointBalanceDetail','document_id');
    }
}
