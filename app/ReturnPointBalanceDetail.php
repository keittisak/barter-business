<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnPointBalanceDetail extends Model
{
    protected $fillable = [
        'id',
        'document_id',
        'balance_id',
        'point_id',
        'before_total_amount',
        'after_total_amount',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function document()
    {
        return $this->belongsTo('App\ReturnPointBalance', 'document_id');
    }
}
