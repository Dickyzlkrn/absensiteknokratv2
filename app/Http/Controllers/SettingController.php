<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        return view('pages.admin.settingakun', [
            'level' => 'Admin'
        ]);
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $user = Auth::guard('web')->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        Auth::logout();

        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }
}
