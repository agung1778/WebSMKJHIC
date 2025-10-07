<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Proses permintaan login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'], // Menambahkan validasi 'required'
        ]);

        // Coba proses autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Arahkan sesuai role
            // Contoh sederhana:
            if ($user && $user->role === 'admin') {
                 return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang admin!');
            }

            return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil!');
        }

        // Jika autentikasi gagal, kembalikan ke halaman login
        return back()->withErrors([
            'email' => 'Email atau password anda salah',
        ])->onlyInput('email');
    }

    /**
     * Proses logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda Berhasil Logout');
    }
}
