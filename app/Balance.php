<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{    
    protected $fillable = [
        'id',
        'point_id',
        'user_id',
        'total_amount',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function point()
    {
        return $this->belongsTo('App\Point', 'point_id');
    }

    public function add ($total_amount)
    {
        $data = array(
            'total_amount' => $this->total_amount + $total_amount
        );
        $this->update($data);
    }

    public function reduce ($total_amount)
    {
        $data = array(
            'total_amount' => $this->total_amount - $total_amount
        );
        $this->update($data);
        return array('success' => true);
    }
}
