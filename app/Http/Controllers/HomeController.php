<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\IncomingMail;
use App\Models\IncomingMailDivision;
use App\Models\OutgoingMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $total = [
            'user'     => User::count(),
            'division' => Division::count(),
            'mail_in'  => (Auth::user()->hasRole('superadmin|admin-surat')) ? IncomingMailDivision::count() : IncomingMailDivision::where('division_id', Auth::user()->division_id)->count(),
            'mail_out' => (Auth::user()->hasRole('superadmin|admin-surat')) ? OutgoingMail::count() : OutgoingMail::where('division_id', Auth::user()->division_id)->count()
        ];

        return view('home', compact('total'));
    }

    public function account()
    {
        return view('account');
    }

    public function accountUpdate(Request $request)
    {
        $data = $request->only('email', 'name');

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::id()
        ]);

        if ($request->has('password') && $request->password != null) {
            $this->validate($request, [
                'password' => 'required|min:8|confirmed'
            ]);

            $data['password'] = Hash::make($request->password);
        }
        
        User::where('id', Auth::id())->update($data);

        return redirect()->route('account')->with('success', 'Akun anda berhasil diupdate.');
    }
}
