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
        dd(session()->all());

        return view('auth.login');
    }
    public function login(LoginFormRequest $request){
       
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
           $user = Auth::user();
           $user->last_login_at = now();
           $user->last_login_ip = $request->ip();
           $user->save();
            if($request->filled('remember')){
               $remember_token = Str::random(10);
               $user->remember_token = $remember_token;
               $request->session()->put('user_id',$user->id);
               $request->session()->put('remember_token',$remember_token);
            }
           return redirect()->route('product');
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        
    }
    
    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login');
    }
}
