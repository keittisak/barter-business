<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branner extends Model
{
    protected $fillable = [
        'id',
        'description',
        'image',
        'after_login',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
