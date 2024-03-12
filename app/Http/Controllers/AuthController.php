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
        $credentials = $request->only('email','password');
        if(auth()->attempt($credentials)){
            $user = Auth::user();
            if($user->is_active != 1){
                Auth::logout();
                return redirect()->back()->with('error','Tài khoản không hoạt động');
            }

            if($user->is_delete!= 0){
                Auth::logout();
                return redirect()->back()->with('error','Tài khoản đã bị xóa');
            }

            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();
            session()->put('user_id', $user->id);

            if($request->filled('remember')){
                $remember_token = Str::random(20);
                $user->remember_token = $remember_token;
                session()->put('remember_token', $remember_token);
                $user->save();
            }
            return redirect()->route('product.index');
        }
        else{
            return redirect()->back()->with('error','Sai thông tin đăng nhập ');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->forget('remember_token');
        $request->session()->forget('user_id');
        return redirect()->route('login');
    }
}
