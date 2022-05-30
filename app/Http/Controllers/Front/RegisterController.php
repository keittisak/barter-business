<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;

use App\User;
use App\Balance;
use App\Transaction;
use DB;
use Carbon\Carbon;
use App\Role;
use App\MembershipRequest;

class RegisterController extends Controller
{
    public function form(Request $request)
    {
        $user = new User();
        if( !empty($request->uId) ) {
            $user = $user->find($request->uId);
        }
        return view('front.register', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $request->merge(array('country_id' => 216));
        $validate = [
            'email' => [
                'required', 
                'email', 
                'unique:users,email',
            ],
            'first_name' => [
                'required', 
            ],
            'last_name' => [
                'required', 
            ],
            "phone" => [
                'required',
                'unique:users,phone',
                'unique:membership_requests,phone'
            ],
            'address' => [
                // 'nullable',
                'required'
            ],
            'country_id' => [
                // 'nullable',
                'required',
                'integer',
                'exists:countries,id'
            ],
            'province_id' => [
                // 'nullable',
                'required',
                'integer',
                'exists:provinces,id'
            ],
            'district_id' => [
                // 'nullable',
                'required',
                'integer',
                'exists:districts,id'
            ],
            'subdistrict_id' => [
                // 'nullable',
                'required',
                'integer',
                'exists:subdistricts,id'
            ],
            'postalcode' => [
                // 'nullable',
                'required',
                'integer',
                "digits:5",
                // 'exists:subdistricts,postalcode'
            ],
            'full_address' => [
                'nullable',
            ],
            "recommended_by" => [
                "nullable",
                // "required",
                // 'integer',
                // "exists:users,code"
            ],
            "id_card_number" => [
                // "required",
                // 'min:13'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $result = DB::transaction(function() use($request, $data) {
                $counter = new Counter();
                $recommendedBy = User::where('code', $data['recommended_by'])->first();
                if( $recommendedBy ){
                    $data['recommended_by'] = $recommendedBy->id;
                }
                $result = MembershipRequest::create($data);
                return $result;
            });
            return response($result, 201);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function pointIncoming ($user, $totalAmount, $type)
    {
        $counter = new Counter();
        $code = $counter->generateCode('bai');
        $_request = new Request();
        $_request->merge([
            'code' => $code,
            'point_id' => 1,
            'total_amount' => $totalAmount,
            'transferred_to' => $user->id,
            'remark' => 'ได้รับเทรดบาทจากการเป็นสมาชิกใหม่',
            'type' => 'income'
        ]);
        if( $type == 'recommen' ) {
            $_request->merge([
                'transferred_to' => $user->recommended_by,
                'remark' => 'ได้รับเทรดบาทจากการแนะนำ '.$user->first_name.' '.$user->last_name.' เป็นสมาชิกใหม่'
            ]);
        }
        $transaction = Transaction::create($_request->toArray());
        $balance = User::findOrFail($_request->transferred_to)->balances->where('point_id', $_request->point_id)->first();
        $balance->income($_request->total_amount);
    }
}
