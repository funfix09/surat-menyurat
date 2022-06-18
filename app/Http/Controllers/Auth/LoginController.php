<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            if (Auth::user()->hasRole('admin-bidang|karyawan') && Auth::user()->division_id == NULL) {
                $request->session()->invalidate();
                return redirect('/login')->withInput()->withErrors([
                    'division' => 'Akun anda tidak terdaftar pada divisi manapun, silakan menghubungi administrator sistem.',
                ]);
            }
            
            $request->session()->regenerate();
 
            return redirect()->intended('/');
        }
 
        return back()->withInput()->withErrors([
            'email' => 'Email atau password anda salah !',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
