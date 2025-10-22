<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\User;

class AuthController extends Controller
{
    // ================================
    // Halaman Login
    // ================================
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    // ================================
    // Login Mahasiswa (pakai npm)
    // ================================
    public function proseslogin(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'password' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('npm', $request->npm)->first();

        if ($mahasiswa && Hash::check($request->password, $mahasiswa->password)) {
            Auth::guard('mahasiswa')->login($mahasiswa);
            $request->session()->regenerate();
            return redirect()->intended('/mahasiswa/dashboard');
        }

        return back()->withErrors(['loginError' => 'NPM atau Password salah.']);
    }

    // ================================
    // Login Admin (kolom email diisi npm)
    // ================================
    public function prosesloginadmin(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'password' => 'required',
        ]);

        // login menggunakan kolom email yang isinya NPM
        $admin = User::where('email', $request->npm)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('web')->login($admin);
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['loginError' => 'NPM atau Password salah.']);
    }

    // ================================
    // Logout Mahasiswa
    // ================================
    public function proseslogout(Request $request)
    {
        Auth::guard('mahasiswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/welcome')->with('status', 'Anda telah logout.');
    }

    // ================================
    // Logout Admin
    // ================================
    public function proseslogoutadmin(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/welcome')->with('status', 'Anda telah logout.');
    }
}
