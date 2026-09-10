<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolProgram;
use App\Models\News;
use App\Models\Teacher;
use App\Models\Achievement;
use App\Models\Facility;
use App\Models\Major;
use App\Models\Extracurricular;
use App\Models\Image;
use App\Models\Writing;
use App\Models\SchoolSetting;
use App\Models\SpmbSetting;
use App\Models\TrafficVisitor;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\PkkProject;
use App\Models\Navigation;
use App\Models\InstaPost;
use App\Services\TrafficService;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(TrafficService $traffic)
    {
        $stats = [
            'programs'        => SchoolProgram::count(),
            'programsPublished' => SchoolProgram::where('status', 'published')->count(),
            'programsDraft'   => SchoolProgram::where('status', 'draft')->count(),
            'news'            => News::count(),
            'teachers'        => Teacher::count(),
            'achievements'    => Achievement::count(),
            'facilities'      => Facility::count(),
            'majors'          => Major::count(),
            'extracurriculars' => Extracurricular::count(),
            'images'          => Image::count(),
            'writings'        => Writing::count(),
            'users'           => User::count(),
            'partners'        => Partner::count(),
            'testimonials'    => Testimonial::count(),
            'pkk'             => PkkProject::count(),
            'navigations'     => Navigation::count(),
            'instaPosts'      => InstaPost::count(),
            'students'        => optional(SchoolSetting::first())->jumlah_siswa ?? 0,
            'visitorsToday'   => TrafficVisitor::whereDate('created_at', today())->count(),
        ];

        $trafficCards = $traffic->summaryCards();

        $latestNews = News::latest('date_published')->take(4)->get();
        $recentUsers = User::latest('created_at')->take(4)->get();

        $spmb = SpmbSetting::first();

        return view('admin.dashboard', compact('stats', 'trafficCards', 'latestNews', 'recentUsers', 'spmb'));
    }

    public function curator()
    {
        return view('admin.curator');
    }   

    public function user()
    {
        // Mengambil semua user (admin) beserta data sesi terakhirnya
        // Eager loading 'session' untuk performa yang lebih baik (menghindari N+1 query)
        $users = User::with('session')->orderBy('role')->get();

        return view('admin.user', compact('users'));
    }

    /**
     * Perbarui role pengguna (hanya dapat dipanggil oleh superadmin).
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:admin,superadmin'],
        ]);

        // Cegah superadmin menurunkan dirinya sendiri agar tidak terkunci.
        if (
            auth()->user()->is($user)
            && auth()->user()->role === 'superadmin'
            && $request->input('role') !== 'superadmin'
        ) {
            return back()->withErrors(['role' => 'Kamu tidak dapat menurunkan role kamu sendiri.']);
        }

        $user->update(['role' => $request->input('role')]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->input('role')}.");
    }

    /**
     * Tampilkan form edit profil admin.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data profil admin.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', 'in:admin,superadmin'],
            'status'   => ['required', 'string', 'in:active,inactive'],
        ]);

        // Cegah superadmin menonaktifkan diri sendiri
        if (
            auth()->user()->is($user)
            && auth()->user()->role === 'superadmin'
            && $validated['role'] !== 'superadmin'
        ) {
            return back()->withErrors(['role' => 'Kamu tidak dapat menurunkan role kamu sendiri.']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Password hanya diupdate jika diisi
        if (!empty($validated['password'])) {
            $user->fill($validated)->save();
        } else {
            unset($validated['password']);
            $user->fill($validated)->save();
        }

        return redirect()->route('admin.users')->with('success', "Data admin \"{$user->name}\" berhasil diperbarui.");
    }
}
