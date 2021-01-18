<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login() {
		if (Auth::check()) {
			return redirect('beranda');
		}
		return view('login');
	}

	public function ceklogin(Request $request){
		$credentials = $request->only('nisn', 'password');
		if (Auth::attempt($credentials)) {
			return redirect()->intended('beranda');
		}
		Auth::logout();
		$request->session()->flush();
		return redirect('/')->with('error', 'Login Gagal');
	}

	public function logout(Request $request) {
	  Auth::logout();
	  $request->session()->flush();
	  return redirect('/');
	}
}
