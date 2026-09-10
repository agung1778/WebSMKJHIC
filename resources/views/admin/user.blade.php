@extends('layouts.admin-app')

@section('title', 'Daftar Admin')

@section('content')
    @php
        $totalUsers = $users->count();
        $onlineUsers = $users->filter(function ($user) {
            return optional($user->session)->last_activity && (time() - $user->session->last_activity) < 300;
        })->count();
        $items = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'sub' => $u->email,
                'role' => $u->role,
                'initial' => strtoupper(mb_substr($u->name, 0, 1)),
                'created' => $u->created_at->format('d M Y, H:i'),
                'by' => optional($u->session)->last_activity
                    ? \Carbon\Carbon::createFromTimestamp($u->session->last_activity)->locale('id')->diffForHumans()
                    : 'Belum pernah login',
                'online' => optional($u->session)->last_activity && (time() - $u->session->last_activity) < 300 ? 1 : 0,
                'ts' => $u->created_at->timestamp,
                'updated' => $u->created_at->locale('id')->diffForHumans(),
            ];
        })->values();
        $isSuperadmin = auth()->user()->role === 'superadmin';
    @endphp

    <div class="fade-up space-y-6" x-data="tableIndex({
        raw: @json($items),
        statuses: {
            admin: { label: 'Admin', class: 'badge-published' },
            superadmin: { label: 'Super Admin', class: 'badge-info' }
        },
        statusKey: 'role',
        searchKeys: ['name', 'sub', 'role', 'by'],
        perPage: 10,
        emptyHead: 'Belum ada admin',
        emptyBody: 'Akun admin akan muncul di sini setelah ditambahkan.'
    })">

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

        <div class="app-card overflow-hidden">
            <div class="toolbar">
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="app-input" type="search" placeholder="Cari nama, email, role…" x-model="q">
                </div>
                <span class="toolbar-spacer"></span>
                <select class="app-select" style="width:auto" x-model="statusFilter" @change="applyFilter">
                    <option value="all">Semua Role</option>
                    <template x-for="(s, k) in statuses" :key="k">
                        <option :value="k" x-text="s.label"></option>
                    </template>
                </select>
                <button class="icon-btn" @click="refresh" title="Muat ulang" aria-label="Muat ulang"><i class="fa-solid fa-rotate-right" :class="{'fa-spin': loading}"></i></button>
            </div>

            <div class="table-wrap" x-show="!loading">
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
                        <template x-for="u in paged" :key="u.id">
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="initials-avatar" x-text="u.initial"></span>
                                        <div>
                                            <div class="cell-main" x-text="u.name"></div>
                                            <div class="cell-sub" x-text="u.sub"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" :class="badgeClass(u.role)" x-text="statusLabel(u.role)"></span></td>
                                <td><span class="cell-sub" style="white-space:nowrap" x-text="u.created"></span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="status-dot" :class="u.online ? 'online' : ''"></span>
                                        <span class="cell-sub" x-text="u.by"></span>
                                    </div>
                                </td>
                                <td>
                                    @if ($isSuperadmin)
                                        <div class="flex items-center justify-end gap-2">
                                            <a class="app-btn app-btn-sm" :href="'/admin/users/' + u.id + '/edit'" title="Edit profil admin"><i class="fa-solid fa-pen"></i><span class="hide-mob">Edit</span></a>
                                            <form method="POST" :action="'/admin/users/' + u.id + '/role'">
                                                <input type="hidden" name="_token" :value="csrf">
                                                <select name="role" class="app-select" style="width:auto;padding:7px 30px 7px 12px;font-size:12px" @change="this.form.submit()">
                                                    <option value="admin" :selected="u.role === 'admin'">admin</option>
                                                    <option value="superadmin" :selected="u.role === 'superadmin'">superadmin</option>
                                                </select>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-sm" style="color:var(--text-3)">—</span>
                                    @endif
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="loading" class="p-5 grid gap-4">
                <template x-for="i in 4" :key="i">
                    <div class="flex items-center gap-4">
                        <div class="skeleton" style="width:48px;height:48px;border-radius:999px"></div>
                        <div style="flex:1">
                            <div class="skeleton" style="height:14px;width:45%;margin-bottom:8px"></div>
                            <div class="skeleton" style="height:11px;width:70%"></div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="!loading && empty">
                <x-admin-components::empty-state icon="fa-users-gear" :title="'Belum ada admin'"
                    :description="'Akun admin akan muncul di sini setelah ditambahkan.'" />
            </div>

            <x-admin-components::pagination client countLabel="admin" />
        </div>
    </div>
@endsection