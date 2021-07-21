<?php


namespace Modules\Login\controllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\enum\UserStatus;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function show()
    {
        if(Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('login::login');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(User::whereEmail($credentials['email'])->whereStatus(UserStatus::ACTIVE)->exists()) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->logged_on = date('Y-m-d H:i:s');
                $user->save();

                if($request->has('remember_me')) {
                    Cookie::queue(Cookie::forever('login_email', $credentials['email']));
                    Cookie::queue(Cookie::forever('login_password', $credentials['password']));
                }else {
                    Cookie::queue(Cookie::forget('login_email'));
                    Cookie::queue(Cookie::forget('login_password'));
                }

                return redirect()->route('dashboard');

            } else {
                return back()->withInput()->with('error', 'Invalid Credentials');
            }
        } else {
            return back()->withInput()->with('error', 'Account suspended, contact administrator.');
        }

    }

    public function logout()
    {
        $user = Auth::user();
        $user->logged_on = null;
        $user->save();
        Auth::logout();
        return redirect()->route('login');
    }
}
