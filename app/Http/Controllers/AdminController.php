<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function curator()
    {
        return view('admin.curator');
    }   

    public function user()
    {
        // Mengambil semua user (admin) beserta data sesi terakhirnya
        // Eager loading 'session' untuk performa yang lebih baik (menghindari N+1 query)
        $users = User::with('session')->get();

        return view('admin.user', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        // Hanya superadmin yang boleh mengubah role
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.users')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'role' => 'required|in:superadmin,admin,curator',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Role user berhasil diupdate.');
    }
}
