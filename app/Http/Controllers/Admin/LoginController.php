<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\User;
use DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        return view('admin.login');
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
                'email'
            ],
            'password' => [
                'required'
            ]
        ];
        $request->validate($validate);
        $data = Arr::only($request->all(), array_keys($validate));

        $fieldType = 'email';
        if ( Auth::attempt( array($fieldType => $data['username'], 'password' => $data['password']) ) ) {
            // Authentication passed...
            // return redirect()->intended($this->redirectTo);
            return response(['message' => 'success'], 200);
        }
        return response(['errors' => [ 'username' => ['อีเมล์หรือรหัสผ่านไม่ถูกต้อง']]],422);
        // return back()->withErrors(['username' => 'Username or password are wrong.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login.form');
    }


}
