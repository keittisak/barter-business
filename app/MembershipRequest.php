<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDO;

class MembershipRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email', 
        "title_name",
        "first_name",
        "last_name",
        "address",
        "country_id",
        "province_id",
        "district_id",
        "subdistrict_id",
        "postalcode",
        "phone",
        "recommended_by",
        "id_card_number",
        "approved_by",
        "approved_at",
        "deleted_by",
        "deleted_at"
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function province () {
        return $this->belongsTo('App\Province', 'province_id');
    }

    public function district () {
        return $this->belongsTo('App\District', 'district_id');
    }

    public function subdistrict () {
        return $this->belongsTo('App\Subdistrict', 'district_id');
    }

    public function recommended_by_user() {
        return $this->belongsTo('App\User', 'recommended_by');
    }

    public function approved_by_user () {
        return $this->belongsTo('App\User', 'recommended_by');
    }

    public function deleted_by_user () {
        return $this->belongsTo('App\User', 'recommended_by');
    }

    public function full_address () {
        if(isset($this->subdistrict->name) && isset($this->district->name) && isset($this->province->name) && isset($this->country->name)){
            return $this->address.' '.$this->subdistrict->name.' '.$this->district->name.' '.$this->province->name.' '.$this->postalcode.' '.$this->country->name;
        }
        return '-';
    }
}
