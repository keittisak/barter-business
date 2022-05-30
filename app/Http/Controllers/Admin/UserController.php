<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Image;
use App\User;
use App\Role;
use App\Trade;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Income;

class UserController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.users.index');
    }

    public function data (Request $request)
    {
        $result = User::with(['balances','shop','roles', 'recommended_by_user', 'user_type','sms'])
        ->when( !empty($request->q), function($q) use ($request){
            $q->where('first_name', 'like', '%' . $request->q . '%')
            ->orWhere('last_name', 'like', '%' . $request->q . '%')
            ->orWhere('code', 'like', '%' . $request->q . '%');;

        })
        ->when( !empty($request->created_at), function($q) use ($request){
            $dateExplode = explode(' - ', $request->created_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        })
        ->when( !empty($request->expired_at), function($q) use ($request){
            $dateExplode = explode(' - ', $request->expired_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $q->whereBetween(DB::raw('DATE(expired_at)'), [$startDate, $endDate]);
        })
        ->when( !empty($request->type), function($q) use ($request){
            $q->where('type', $request->type);
        })
        ->when( $request->user()->isSubAdmin(), function($q) use ($request){
            $q->whereNotIn('code', [1]);
        })
        ->get();
        return DataTables::of($result)
        ->editColumn('first_name', function($result){
            return $result->full_name();
        })
        ->editColumn('created_at', function($result){
            return $result->created_at->addYears(543)->format('d/m/Y H:i');
        })
        ->editColumn('expired_at', function($result){
            
            if(!empty($result->expired_at)) {
                $expiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $result->expired_at);
                return $expiredAt->addYears(543)->format('d/m/Y');
            }
            return '-';
        })
        ->addColumn('total_amount',function($result){
            return $result->balances[0]->total_amount;
        })
        ->addColumn('shop_name',function($result){
            if(!empty($result->shop)){
                return $result->shop->name;
            }
            return '-';
        })
        ->addColumn('credit_balance_amount', function ($result){
            $balances = collect($result->balances);
            $creditBalance = $balances->firstWhere('point_id', 2);
            if( $creditBalance ){
                return $creditBalance->total_amount;
            }
            return 0;
        })
        ->make(true);
    }

    public function create (Request $request)
    {
        $user = new User();
        $data = [
            'user' => $user,
            'isAdmin' => $user->isAdmin(),
            'isMember' => $user->isMember()
        ];
        return view('admin.users.form', $data);
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)) {
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'email' => [
                'required', 
                'email', 
                'unique:users,email'
            ],
            'first_name' => [
                'required', 
            ],
            'last_name' => [
                'required', 
            ],
            "phone" => [
                'required',
                'unique:users,phone'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:6',
            ],
            'image' => [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:200000',
            ],
            'created_by' => [

            ],
            'updated_by' => [
                
            ]

        ];

        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data) {
                $image = new Image();
                $data["password"] = Hash::make($data["password"]);
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'images/users/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $user = User::create($data);
                foreach($request->roles as $role){
                    $role = Role::where('name',$role)->first();
                    $user->roles()->attach($role->id);
                }
                return $user;
            });
            return response($user, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function show (Request $request, $id)
    {
        $user = User::with(['balances','shop.images', 'shop.country', 'shop.province','shop.products','roles', 'recommended_by_user','user_type'])->findOrFail($id);
        $income = Income::where('status', 'success')->where('user_id', $id)->sum('total_amount');
        $purchase = Trade::where('status', 'success')->where('buyer_id', $id)->sum('total_amount');
        $sales = Trade::where('status', 'success')->where('seller_id', $id)->sum('total_amount');
        $balances = collect($user->balances);
        $pointBalance = $balances->firstWhere('point_id', 1);
        $creditBalance = $balances->firstWhere('point_id', 2);
        $shops = $user->shops()->with(['shop_type'])->get();
        $data = [
            'user' => $user,
            'shops' => $shops,
            'products' => !empty($user->shop) ? $user->shop->products : null,
            'createdAt' => $user->created_at->addYears(543)->format('d/m/Y H:i'),
            'expiredAt' => !empty($user->expired_at) ? Carbon::createFromFormat('Y-m-d H:i:s',$user->expired_at)->addYears(543)->format('d/m/Y H:i') : '-',
            'totalAmount' => [
                'income' => $income,
                'purchase' => $purchase,
                'sales' => $sales
            ],
            'pointBalance' => $pointBalance,
            'creditBalance' => $creditBalance
        ];
        return view('admin.users.show', $data);
    }

    public function edit (Request $request,$id)
    {
        $user = User::with('roles')->findOrFail($id);
        $data = [
            'user' => $user,
            'isAdmin' => $user->isAdmin(),
            'isMember' => $user->isMember(),
            'isSubAdmin' => $user->isSubAdmin()
        ];
        return view('admin.users.form', $data);
    }

    public function update (Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (isset($request->user()->id)) {
            $request->merge(array('updated_by' => $request->user()->id));
        }
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
            'type' => [
                'required',
                'exists:user_types,id'
            ],
            "phone" => [
                'required',
                'unique:users,phone,'.$user->id
            ],
            'updated_by' => [
                
            ],
            'trade_fee' => [
                'numeric',
                'min:0'
            ],
            'purchase_fee' => [
                'numeric',
                'min:0'
            ],
            'sales_fee' => [
                'numeric',
                'min:0'
            ],
        ];

        if ($request->hasFile('image')) {
            $validate['image'] = [
                'nullable',
                'mimes:jpeg,bmp,png',
                'max:6000',
            ];
        }
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $user = DB::transaction(function() use($request, $data, $user) {
                $image = new Image();
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'images/users/'.date('Ymd');
                    $imageUrl = $image->upload($file, $path, 'l');
                    $data['image'] = $imageUrl;
                }
                $expired_at = Carbon::createFromFormat('d/m/Y',$request->expired_at)->format('Y-m-d H:i');
                $data['expired_at'] = $expired_at;
                $user->update($data);
                $roleIds = array();
                if(!empty($request->roles)){
                    foreach($request->roles as $role){
                        $role = Role::where('name',$role)->first();
                        $roleIds[] =  $role->id;
                    }
                    $user->roles()->sync($roleIds);
                }else{
                    $user->roles()->detach();
                }
                return $user;
            });
            return response($user, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function updatePassword (Request $request, $id)
    {
        $user = User::findOrFail($id);
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

    public function renew (Request $request, $id)
    {
        $user = User::findOrFail($id);
        try{
            $user = DB::transaction(function() use($request, $user) {
                $expiredAt = Carbon::now()->addMonth(12)->format('Y-m-d H:i:s');
                $user->update(['expired_at' => $expiredAt]);
                return $user;
            });
            return response($user, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }

    }

    
}
