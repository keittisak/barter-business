<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name', 
        'email', 
        'password',
        "title_name",
        "first_name",
        "last_name",
        "image",
        "address",
        "country_id",
        "province_id",
        "district_id",
        "subdistrict_id",
        "postalcode",
        "phone",
        "full_address",
        "status",
        "expired_at",
        "recommended_by",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at",
        "id_card_number",
        "type",
        "trade_fee",
        "credit_total_amount",
        "purchase_fee",
        "sales_fee"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->hasOne('App\Shop');
    }

    public function shops()
    {
        return $this->hasMany('App\Shop');
    }

    public function balances()
    {
        return $this->hasMany('App\Balance', 'user_id');
    }

    public function findForUsername($username)
    {
        if (strpos($username,'0')!== false) {
            return $this->where('phone', $username)->first();
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return $this->where('email', $username)->first();
        } else {
            return $this->where('name', $username)->first();
        }
    }

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

    public function roles () {
        return $this->belongsToMany('App\Role');
    }

    public function isAdmin () {
        return $this->roles()->where('name','admin')->exists();
    }

    public function isMember () {
        return $this->roles()->where('name','member')->exists();
    }

    public function isSubAdmin () {
        return $this->roles()->where('name','sub_admin')->exists();
    }

    public function isExpire () {
        // return $this->where('status', 'inactive')->exists();
        $expiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->expired_at);
        $today = Carbon::now();
        if( $today->timestamp >  $expiredAt->timestamp) {
            return true;
        }
        return false;
    }

    public function beneficiary () {
        return $this->hasOne('App\Beneficiary');
    }

    public function full_name () {
        return $this->first_name.' '.$this->last_name;
    }

    public function full_address () {
        if(isset($this->subdistrict->name) && isset($this->district->name) && isset($this->province->name) && isset($this->country->name)){
            return $this->address.' '.$this->subdistrict->name.' '.$this->district->name.' '.$this->province->name.' '.$this->postalcode.' '.$this->country->name;
        }
        return '-';    }

    public function recommended_by_user () {
        return $this->belongsTo('App\User', 'recommended_by');
    }

    public function user_type () {
        return $this->belongsTo('App\UserType', 'type');
    }

    public function billings () {
        return $this->hasMany('App\Billing', 'user_id');
    }

    public function countBillingUnpaid () {
        return $this->billings()->where('status', 'unpaid')->count();
    }

    public function isRoleAccess ($role = null){
        $isAccess = false;
        $roles = explode('|', $role);
        $userRoles = [];
        foreach($this->roles as $role){
            $userRoles[] = $role->name;
        }

        foreach($this->roles as $item){
            if($item->name == 'member'){
                continue;
            }
            if( in_array($item->name, $roles) ){
                $isAccess = true;
            }else{
                $isAccess = false;
            }
        }
// dd($isAccess);
        return $isAccess;
    }

    public function sms () {
        return $this->hasMany('App\Sms','user_id');
    }
}
