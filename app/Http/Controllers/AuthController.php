<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

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
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // **[IMPROVEMENT]** Buat kunci unik untuk rate limiter berdasarkan email dan IP
        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        // **[IMPROVEMENT]** Cek apakah sudah terlalu banyak percobaan
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) { // 5x percobaan
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // 2. Coba proses autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // **[IMPROVEMENT]** Hapus catatan rate limiter jika berhasil
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')->with('status', 'Login berhasil!');
        }

        // **[IMPROVEMENT]** Tambahkan 1 percobaan gagal ke rate limiter
        RateLimiter::hit($throttleKey);

        // 3. Logika jika autentikasi gagal
        $userExists = User::where('email', $credentials['email'])->exists();

        if ($userExists) {
            return back()
                ->withErrors(['email' => 'Password yang Anda masukkan salah.'])
                ->with('show_reset_link', true)
                ->onlyInput('email');
        }

        return back()
            ->withErrors(['email' => 'Email tidak terdaftar di sistem kami.'])
            ->onlyInput('email');
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
