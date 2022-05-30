<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "name",
        "description",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at"
    ];
}
