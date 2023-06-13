<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function verify(LoginRequest $request){
        if(Auth::attempt($request->only('email', 'password'))){
            return redirect(route('dashboard'));
        }else{
            return back()->with('error', 'Email atau Password Salah, Silahkan Coba Lagi!');
        }
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}
