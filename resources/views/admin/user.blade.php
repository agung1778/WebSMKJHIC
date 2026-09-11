@extends('layouts.admin-app')

@section('title', 'Daftar Admin')

@section('content')
    @php
        $totalUsers = $users->count();
        $onlineUsers = $users->filter(function ($user) {
            return optional($user->session)->last_activity && (time() - $user->session->last_activity) < 300;
        })->count();
        $isSuperadmin = auth()->user()->role === 'superadmin';
    @endphp

    <div class="fade-up space-y-6">

        <x-admin-components::page-header
            icon="fa-solid fa-users-gear"
            kicker="Akses"
            title="Daftar Admin"
            subtitle="Kelola akun admin dan super admin yang memiliki akses ke panel ini." />

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin-components::stat-card label="Total Admin" :value="$totalUsers" icon="fa-solid fa-users-gear" tone="brand" />
            <x-admin-components::stat-card label="Sedang Online" :value="$onlineUsers" icon="fa-solid fa-wifi" tone="green"
                :sub="'Aktif dalam 5 menit terakhir'" />
        </div>

        <div data-filter-root>
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari nama, email, role…" data-filter-input>
                </div>
                <select class="app-select" style="width:auto" data-filter-cat>
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
                </select>
                <span class="toolbar-spacer"></span>
            </div>

            <div class="table-wrap">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aktivitas Terakhir</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                            @php
                                $lastActivity = optional($u->session)->last_activity;
                                $online = $lastActivity && (time() - $lastActivity) < 300;
                                $lastBy = $lastActivity
                                    ? \Carbon\Carbon::createFromTimestamp($lastActivity)->locale('id')->diffForHumans()
                                    : 'Belum pernah login';
                            @endphp
                            <tr data-row data-cat="{{ $u->role }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="initials-avatar">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</span>
                                        <div>
                                            <div class="cell-main">{{ $u->name }}</div>
                                            <div class="cell-sub">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ $u->role === 'superadmin' ? 'badge-info' : 'badge-published' }}">{{ $u->role === 'superadmin' ? 'Super Admin' : 'Admin' }}</span></td>
                                <td><span class="cell-sub" style="white-space:nowrap">{{ $u->created_at->format('d M Y, H:i') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="status-dot {{ $online ? 'online' : '' }}"></span>
                                        <span class="cell-sub">{{ $lastBy }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($isSuperadmin)
                                        <div class="flex items-center justify-end gap-2">
                                            <a class="app-btn app-btn-sm" href="{{ route('admin.users.edit', $u) }}" title="Edit profil admin"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                            <form action="{{ route('admin.users.updateRole', $u) }}" method="POST">
                                                @csrf
                                                <select name="role" class="app-select" style="width:auto;padding:7px 30px 7px 12px;font-size:12px" onchange="this.form.submit()">
                                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>admin</option>
                                                    <option value="superadmin" {{ $u->role === 'superadmin' ? 'selected' : '' }}>superadmin</option>
                                                </select>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-sm" style="color:var(--text-3)">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr data-row data-empty>
                                <td colspan="5">
                                    <div class="p-6 text-center">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa-solid fa-users-gear"></i></div>
                                            <h3>Belum ada admin</h3>
                                            <p>Akun admin akan muncul di sini setelah ditambahkan.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.tables._filter')
        </div>
    </div>
@endsection