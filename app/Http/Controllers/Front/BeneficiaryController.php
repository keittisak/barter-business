<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\User;
use App\Beneficiary;
use DB;
use Carbon\Carbon;

class BeneficiaryController extends Controller
{
    public function show (Request $request)
    {
        $user = User::with('beneficiary')->findOrFail($request->user()->id);
        return response($user->beneficiary);
    }
    public function create (Request $request) 
    {
        $user = User::with('beneficiary')->findOrFail($request->user()->id);
        if( !empty($user->beneficiary) ) {
            return abort(404);
        }
        return view('front.users.beneficairy_form', $user);
    }

    public function store (Request $request) 
    {
        $user = User::findOrFail($request->user()->id);
        $request->merge(array('user_id' => $request->user()->id));
        $validate = [
            'name' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'relationship' => [
                'required',
            ],
            'phone' => [
                'required',
            ],
            'user_id' => [
                'required',
                'unique:beneficiaries,user_id',
            ],
            'id_card_number' => [
                "required",
                'min:13',
                'integer'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $result = DB::transaction(function() use($request, $data, $user) {
                $result = Beneficiary::create($data);
                return $result;
            });
            return response($result, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request) 
    {
        $user = User::with('beneficiary')->findOrFail($request->user()->id);
        if( empty($user->beneficiary) ) {
            return abort(404);
        }
        $data = [
            'user' => $user,
            'beneficiary' => $user->beneficiary
        ];
        return view('front.users.beneficairy_form', $data);
    }

    public function update (Request $request) 
    {
        $user = User::findOrFail($request->user()->id);
        $request->merge(array('user_id' => $request->user()->id));
        $validate = [
            'name' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'relationship' => [
                'required',
            ],
            'phone' => [
                'required',
            ],
            'user_id' => [
                'required',
                'unique:beneficiaries,user_id,'.$user->id,
            ],
            'id_card_number' => [
                "required",
                'min:13',
                'integer'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data, $user) {
                $result = $user->beneficiary()->update($data);
                return $user;
            });
            return response($user, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
}
