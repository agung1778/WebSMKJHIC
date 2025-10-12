<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman form untuk meminta link reset password.
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.ForgotPassword');
    }

    /**
     * Mengirim link reset password ke email user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi email
        $request->validate(['email' => 'required|email']);

        // 2. Kirim link reset (Laravel akan menangani pembuatan token & pengiriman email)
        $status = Password::sendResetLink($request->only('email'));

        // 3. Beri feedback ke user
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Kami telah mengirim email berisi link reset password Anda!')
            : back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }

    /**
     * Menampilkan form untuk mereset password.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.auth.ResetPassword')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Memproses reset password.
     */
    public function reset(Request $request)
    {
        // 1. Validasi input (data yang divalidasi disimpan dalam variabel)
        $validatedData = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // 2. Proses reset password HANYA menggunakan data yang sudah tervalidasi
        $status = Password::reset($validatedData, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        });

        // 3. Arahkan user setelah berhasil atau gagal
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password Anda berhasil diubah!')
            : back()->withErrors(['email' => 'Gagal mereset password, token mungkin tidak valid.']);
    }
}
