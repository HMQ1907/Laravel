<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginFormRequest $request)
    {

        $remember = $request->has('remember') ? true : false;

        if ((Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))) {
            $user = Auth::user();
            if ($user->is_active != 1 || $user->is_delete != 0) {
                Auth::logout();
                return redirect()->back()->with('error', 'Vui lòng đăng nhập lại');
            }

            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();

            return redirect()->route('product.index');
        } else {
            return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Sai thông tin đăng nhập');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('remember_token');
        return redirect()->route('login');
    }
}
