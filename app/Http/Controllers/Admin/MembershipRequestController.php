<?php

namespace App\Http\Controllers\Admin;

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
use App\Point;
use DataTables;
use App\Income;
use App\Branch;
use App\Billing;

class MembershipRequestController extends Controller
{
    public function index ()
    {
        return view('admin.membership_requests.index');
    }

    public function data (Request $request)
    {
        $result = MembershipRequest::with('recommended_by_user')
        ->when( !empty($request->created_at), function($q) use ($request){
            $dateExplode = explode(' - ', $request->created_at);
            $startDate = Carbon::createFromFormat('d/m/Y', $dateExplode[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $dateExplode[1])->format('Y-m-d');
            $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        })
        ->when( !empty($request->approved_by), function($q) use ($request){
            if($request->approved_by == 'is_null'){
                $q->whereNull('approved_by');
            }else{
                $q->where('approved_by', $request->approved_by);
            }
        })
        ->get();
        return DataTables::of($result)
                ->editColumn('created_at', function($result){
                    return $result->created_at->addYears(543)->format('d/m/Y H:i');
                })
                ->addColumn('full_address', function($result){
                    return $result->full_address();
                })
                ->make(true);
    }

    public function create (Request $request)
    {
        return view('admin.membership_requests.form');
    }

    public function store (Request $request)
    {
        $request->merge(array('country_id' => 216));
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
            "recommended_by" => [
                // "nullable",
                "required",
                'integer',
                "exists:users,code"
            ],
            "id_card_number" => [
                // "required",
                // 'min:13'
            ],
            'credit_total_amount' =>[

            ],
            'trade_fee' => [

            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));
        try{
            $result = DB::transaction(function() use($request, $data) {
                $counter = new Counter();
                $userModal = new User();
                $recommendedBy = $userModal->where('code', $data['recommended_by'])->firstOrFail();
                $data['recommended_by'] = $recommendedBy->id;
                $result = MembershipRequest::create($data);
                return $result;
            });
            return response($result, 201);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function process (Request $request, $id)
    {
        $membershipRequest = MembershipRequest::findOrFail($id);
        try{
            $user = DB::transaction(function() use($request, $membershipRequest) {
                $data = $membershipRequest->toArray();
                if($request->type == 'approve'){
                    $counter = new Counter();
                    $user = new User();
                    // $randomPassword = Str::random(6);
                    $randomPassword = $this->quickRandom(6);
                    $data['password'] = Hash::make($randomPassword);
                    $data['code'] = $counter->next('user','code');
                    $data['expired_at'] = Carbon::now()->addMonth($request->month)->format('Y-m-d H:i:s');
                    $data['status'] = 'active';
                    $data['type'] = env('DEFAULT_USER_TYPE',1);
                    $data['credit_total_amount'] = 0;
                    $data['trade_fee'] = 0;
                    $user = $user->create($data);
                    $role = Role::where('name','member')->firstOrfail();
                    $user->roles()->attach($role->id);
                    $data = [
                        'point_id' => Point::findOrFail(1)->id,
                        'user_id' => $user->id,
                        'total_amount' => 0,
                    ];
                    $balance = Balance::create($data);
                    $membershipRequest->update([
                        'approved_by' => $request->user()->id,
                        'approved_at' => Carbon::now()
                    ]);

                    Billing::create([
                        'code' => $counter->generateCode('bab'),
                        'total_amount' => 600,
                        'user_id' => $user->id,
                        'remark' => 'ค่าระบบรายปี',
                        'status' => 'unpaid',
                        'created_by' => $request->user()->id
                    ]);

                    // $branch = Branch::findOrFail(1);
                    // $this->incoming($user, $branch->new_member, 'new_member');
                    // if( !empty($membershipRequest->recommended_by) ) {
                    //     $this->incoming($user, $branch->recommend, 'recommen');
                    // }
                    return array('user' => $user, 'random_password' => $randomPassword);
                }else{
                    $membershipRequest->update(['deleted_by' => $request->user()->id]);
                    $membershipRequest->delete();
                    return array();
                }
                
            });
            if( empty($user) ){
                return response('',204);
            }
            return response($user, 201);
        }catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }

    public function destroy (Request $request, $id) 
    {
        $membershipRequest = MembershipRequest::findOrFail($id);
        $membershipRequest->delete();
        return response('','204');   
    }

    private function incoming ($user, $totalAmount, $type)
    {
        $counter = new Counter();
        $code = $counter->generateCode('bai');
        $_request = new Request();
        $_request->merge([
            'code' => $code,
            'point_id' => 1,
            'total_amount' => $totalAmount,
            'user_id' => $user->id,
            'remark' => 'ได้รับเทรดบาทจากการเป็นสมาชิกใหม่',
            'status' => 'success',
        ]);
        if( $type == 'recommen' ) {
            $_request->merge([
                'user_id' => $user->recommended_by,
                'remark' => 'ได้รับเทรดบาทจากการแนะนำ '.$user->first_name.' '.$user->last_name.' เป็นสมาชิกใหม่'
            ]);
            $user = User::findOrFail($user->recommended_by);
        }
        Income::create($_request->toArray());
        $balance = $user->balances()->where('point_id', $_request->point_id)->first();
        $balance->add($_request->total_amount);
    }

    public function quickRandom($length = 16)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}


