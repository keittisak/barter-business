<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Libraries\Counter;
use App\Libraries\Image;

use App\User;
use App\Balance;
use App\Transaction;
use DB;
use Carbon\Carbon;

class UserController extends Controller
{
    
    public function profile(Request $request)
    {
        $id = $request->user()->id;
        $user = User::with('balances')->findOrFail($id);
        $balances = collect($user->balances);
        $point = $balances->firstWhere('point_id',1);
        $credit = $balances->firstWhere('point_id',2);
        $data = [
            'user' => $user,
            'point_balance' => ($point) ? $point->total_amount : 0,
            'credit_balance' => ($credit) ? $credit->total_amount : 0
        ];
        return view('front.users.profile', $data);
    }

    public function shops (Request $request)
    {
        $user = User::with(['shops'])->findOrFail($request->user()->id);
        return view('front.users.shops', $user);
    }

    public function shopShow (Request $request, $id)
    {
        $user = User::findOrFail($request->user()->id);
        $shop = $user->shops()->with(['images', 'country', 'province','shop_type'])->findOrFail($id);
        $data = [
            'user' => $user,
            'shop' => $shop,
            'images' => !empty($shop->images) ? $shop->images : array(),
            'products' => $shop->products
        ];
        return view('front.users.shop_show', $data);
    }

    public function search (Request $request) 
    {
        $validate = [
            'text' => [
                // 'required',
            ],
            'code'=>[

            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data) {
                $user = new User();
                // $user = $user->where('phone', $data['text'])->orWhere('email', $data['text'])->first();
                $user = $user->when( !empty($request->code), function($q) use ($request){
                    $q->where('code', $request->code);
                })
                ->first();
                return $user;
            });
            return response($user, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function edit (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $data = [
            'user' => $user,
        ];
        return view('front.users.profile_edit', $data);
    }

    public function update (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $validate = [
            'email' => [
                'required', 
                'email', 
                'unique:users,email,'.$user->id
            ],
            'first_name' => [
                'required', 
            ],
            'last_name' => [
                'required', 
            ],
            "phone" => [
                'required',
                'unique:users,phone,'.$user->id
            ],
            'address' => [
                'nullable',
            ],
            'country_id' => [
                'nullable',
                'integer',
                'exists:countries,id'
            ],
            'province_id' => [
                'nullable',
                'integer',
                'exists:provinces,id'
            ],
            'district_id' => [
                'nullable',
                'integer',
                'exists:districts,id'
            ],
            'subdistrict_id' => [
                'nullable',
                'integer',
                'exists:subdistricts,id'
            ],
            'postalcode' => [
                'nullable',
                'integer',
                "digits:5",
                // 'exists:subdistricts,postalcode'
            ],
            'full_address' => [
                'nullable',
            ],
            "id_card_number" => [
                "required",
                'min:13'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data, $user) {
                $user->update($data);
                return $user;
            });
            return response($user, 200);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function uploadImageProfile (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $validate = [
            'image' => [
                'required',
                'mimes:jpeg,bmp,png',
                'max:100000',
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data, $user) {
                $image = new Image();
                $currentImage = $user->image;
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'images/users/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                    if( !empty($currentImage) ) {
                        $currentImageFile = str_replace(url('/').'/storage/','',$currentImage);
                        $image->delete($currentImageFile);
                    }
                }
                $user->update($data);
                return $user;
            });
            return response($user, 200);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updatePassword (Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $validate = [
            'password' => [
                'required', 
                'min:6', 
                'confirmed'
            ],
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data, $user) {
                $data["password"] = Hash::make($data["password"]);
                $user->update($data);
                return $user;
            });
            return response($user, 200);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
    public function recommendedMember (Request $request)
    {
        $users = User::where('recommended_by', $request->user()->id)->get();
        $data = [
            'users' => $users
        ];
        return view('front.users.recommended_member', $data);
    }
}
