<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
//            'captcha' => 'required|captcha'
        ]);

        $user = User::where('email', $request->input('email'))
            ->orWhere('username', $request->input('email'))->first();

        if (!$user) {
            return redirect(route('login'))
                ->withInput($request->input())
                ->withErrors([
                    'email' => 'Tên đăng nhập chưa đúng',
                ]);
        }

        if (!Hash::check($request->input('password'), $user->password))
        {
            return redirect(route('login'))
                ->withInput($request->input())
                ->withErrors([
                    'password' => 'Mật khẩu chưa đúng',
                ]);
        }

        Auth::loginUsingId($user->id);

        return redirect()->intended($this->redirectPath());
    }
}
