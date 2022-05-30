<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $fillable = [
        'name', 'user_id', 'address','phone','id_card_number','relationship'
    ];
}
