<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

use App\User;
use DB;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('front.login');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $validate = [
            'username' => [
                'required',
            ],
            'password' => [
                'required'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));

        // $fieldType = 'phone';
        // if(in_array(substr($data['username'],0,2), ['00','01','02','03','04','05','06','07','08','09'])) {
        //     $fieldType = 'phone';
        // } elseif(filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
        //     $fieldType = 'email';
        // }
        if ( Auth::attempt( array('code' => $data['username'], 'password' => $data['password']) ) ) {
            $today = Carbon::now();
            $expiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->user()->expired_at);
            if( $today >  $expiredAt) { 
                Auth::logout();
                return response([
                    'errors' => [ 
                        'expired' => $expiredAt->addYears(543)->format('d/m/Y')
                    ]
                ],422); 
            }
            return response(['message' => 'success'], 200);
        }
        return response(['errors' => [ 'username' => ['Username or password are wrong.']]],500);
        // return back()->withErrors(['username' => 'Username or password are wrong.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('front.home');
    }


}
