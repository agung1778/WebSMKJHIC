@extends('layouts.admin-app')

@section('title', 'Update Admin — ' . $user->name)

@section('content')
    @php
        $isOnline = optional($user->session)->last_activity && (time() - $user->session->last_activity) < 300;
        $initials = strtoupper(collect(explode(' ', $user->name))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
        $userStatus = old('status', $user->status ?? 'active');
        $roleOld = old('role', $user->role);
    @endphp

    <div class="fade-up mx-auto space-y-6 max-w-6xl">
        <x-admin-components::page-header
            icon="fa-solid fa-user-pen"
            kicker="Akses"
            title="Update Data Admin"
            :subtitle="'Perbarui informasi akun ' . $user->name . '.'">
            <x-slot:actions>
                <a class="app-btn app-btn-lg" href="{{ route('admin.users') }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </x-slot:actions>
        </x-admin-components::page-header>

        {{-- Profil ringkas --}}
        <div class="app-card p-5">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-5">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    @if ($user->avatar && \Storage::disk('public')->exists($user->avatar))
                        <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}"
                            class="thumb-round" style="width:56px;height:56px" />
                    @else
                        <span class="initials-avatar" style="font-size:20px">{{ $initials }}</span>
                    @endif
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="card-title mb-0" style="font-size:17px">{{ $user->name }}</h3>
                            <span class="badge {{ $user->role === 'superadmin' ? 'badge-info' : 'badge-published' }}">
                                @if ($user->role === 'superadmin')
                                    <i class="fa-solid fa-crown" style="margin-right:4px"></i>Super Admin
                                @else
                                    <i class="fa-solid fa-shield-halved" style="margin-right:4px"></i>Admin
                                @endif
                            </span>
                        </div>
                        <p class="text-sm" style="color:var(--text-3)">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="badge {{ $isOnline ? 'badge-published' : 'badge-archived' }}">
                    <span class="status-dot {{ $isOnline ? 'online' : '' }}" style="margin-right:6px"></span>
                    {{ $isOnline ? 'Session Aktif' : 'Offline' }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" id="editAdminForm">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-3 items-start">
                <div class="space-y-6 lg:col-span-2">
                    <div class="form-section p-6 space-y-5">
                        <h4 class="form-section-title"><i class="fa-solid fa-user-pen" style="color:var(--brand)"></i> Informasi Akun</h4>
                        <div class="form-grid-2">
                            <div>
                                <label class="app-label" for="name">Nama Lengkap <span class="req">*</span></label>
                                <x-admin-components::field name="name" placeholder="Masukkan nama lengkap" icon="fa-solid fa-user"
                                    :value="$user->name" required />
                            </div>
                            <div>
                                <label class="app-label" for="email">Email <span class="req">*</span></label>
                                <x-admin-components::field name="email" type="email" placeholder="admin@smkamaliah.sch.id"
                                    icon="fa-solid fa-envelope" :value="$user->email" required />
                            </div>
                            <div>
                                <label class="app-label" for="username">Username</label>
                                <x-admin-components::field name="username" placeholder="username_opsional" icon="fa-solid fa-at"
                                    :value="$user->username" />
                            </div>
                            <div>
                                <label class="app-label" for="phone">Nomor HP</label>
                                <x-admin-components::field name="phone" placeholder="08xxxxxxxxxx" icon="fa-solid fa-phone"
                                    :value="$user->phone" />
                            </div>
                        </div>
                    </div>

                    <div class="form-section p-6 space-y-5">
                        <h4 class="form-section-title"><i class="fa-solid fa-lock" style="color:var(--brand)"></i> Informasi Login</h4>
                        <div class="form-grid-2">
                            <div>
                                <label class="app-label" for="password">Password Baru</label>
                                <div class="relative">
                                    <i class="fa-solid fa-lock" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text-3)"></i>
                                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak diubah"
                                        class="app-input" style="padding-left:38px;padding-right:38px" autocomplete="new-password">
                                    <button type="button" onclick="togglePw()" tabindex="-1" aria-label="Tampilkan password"
                                        style="position:absolute;right:11px;top:50%;transform:translateY(-50%);color:var(--text-3);background:none;border:none;cursor:pointer">
                                        <i class="fa-solid fa-eye" id="pwEye"></i>
                                    </button>
                                </div>
                                <p class="field-hint">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah.</p>
                            </div>
                            <div>
                                <label class="app-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="relative">
                                    <i class="fa-solid fa-lock" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--text-3)"></i>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        placeholder="Ulangi password baru" class="app-input"
                                        style="padding-left:38px" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section p-6 space-y-5">
                        <h4 class="form-section-title"><i class="fa-solid fa-sliders" style="color:var(--brand)"></i> Hak Akses &amp; Status</h4>
                        <div class="form-grid-2">
                            <div>
                                <label class="app-label" for="role">Role <span class="req">*</span></label>
                                <x-admin-components::field name="role" type="select" :options="['admin' => 'Admin', 'superadmin' => 'Super Admin']"
                                    :value="$roleOld" required />
                            </div>
                            <div>
                                <label class="app-label">Status Akun</label>
                                <input type="hidden" name="status" value="inactive">
                                <label class="flex items-center gap-3 cursor-pointer select-none w-fit">
                                    <span class="toggle-switch">
                                        <input type="checkbox" name="status" value="active" @checked($userStatus === 'active')>
                                        <span class="track"></span>
                                    </span>
                                    <span class="text-sm font-semibold" id="statusLabel" style="color:{{ $userStatus === 'active' ? '#15803D' : 'var(--text-3)' }}">
                                        {{ $userStatus === 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="form-section p-6">
                        <h4 class="form-section-title mb-4"><i class="fa-solid fa-camera" style="color:var(--brand)"></i> Foto Profil</h4>
                        <div class="text-center">
                            <div id="avatarPreviewArea">
                                @if ($user->avatar && \Storage::disk('public')->exists($user->avatar))
                                    <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="avatar" id="avatarPreview"
                                        class="initials-avatar" style="width:96px;height:96px;font-size:34px;border-radius:50%;object-fit:cover">
                                @else
                                    <span class="initials-avatar" id="avatarPreviewArea" style="width:96px;height:96px;font-size:34px;border-radius:50%">{{ $initials }}</span>
                                @endif
                            </div>
                            <p class="field-hint mt-3" style="margin-top:14px">JPG, PNG, WebP — Maks 2 MB</p>
                        </div>
                        <div class="mt-4">
                            <label class="app-label">Ganti Foto</label>
                            <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp"
                                class="app-file" onchange="previewAvatar(this)">
                        </div>
                    </div>

                    <div class="form-section p-6">
                        <h4 class="form-section-title mb-4"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Informasi Tambahan</h4>
                        <div class="space-y-3">
                            <div>
                                <div class="app-label">Tanggal Dibuat</div>
                                <p class="text-sm font-semibold" style="color:var(--text)">{{ $user->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <div class="app-label">Terakhir Login</div>
                                <p class="text-sm font-semibold" style="color:var(--text)">
                                    @if ($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        <span style="color:var(--text-3);font-weight:400;font-style:italic">Belum pernah login</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <div class="app-label">Status Aktivitas</div>
                                <p class="text-sm font-semibold" style="color:var(--text)">
                                    @if ($isOnline)
                                        <span class="status-dot online" style="display:inline-block;margin-right:6px"></span> Online
                                    @else
                                        <span style="color:var(--text-3);font-weight:400;font-style:italic">Offline</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <div class="app-label">IP Terakhir Login</div>
                                <p class="text-sm font-semibold" style="color:var(--text)">{{ $user->last_login_ip ?? '—' }}</p>
                            </div>
                            <div>
                                <div class="app-label">Browser Terakhir</div>
                                <p class="text-sm font-semibold" style="color:var(--text)">{{ $user->last_login_browser ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a class="app-btn app-btn-lg" href="{{ route('admin.users') }}"><i class="fa-solid fa-arrow-left"></i> Batal</a>
                <button type="submit" class="app-btn app-btn-primary app-btn-lg" id="btnSubmit">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusInput = document.querySelector('input[name="status"][value="active"]');
            const statusLabel = document.getElementById('statusLabel');
            if (statusInput && statusLabel) {
                statusInput.addEventListener('change', () => {
                    if (statusInput.checked) {
                        statusLabel.textContent = 'Aktif';
                        statusLabel.style.color = '#15803D';
                    } else {
                        statusLabel.textContent = 'Nonaktif';
                        statusLabel.style.color = 'var(--text-3)';
                    }
                });
            }
            const editForm = document.getElementById('editAdminForm');
            const btn = document.getElementById('btnSubmit');
            if (editForm && btn) {
                editForm.addEventListener('submit', () => {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                });
            }
        });

        function togglePw() {
            const inp = document.getElementById('password');
            const eye = document.getElementById('pwEye');
            if (inp.type === 'password') {
                inp.type = 'text';
                eye.className = 'fa-solid fa-eye-slash';
            } else {
                inp.type = 'password';
                eye.className = 'fa-solid fa-eye';
            }
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                if (input.files[0].size > 2 * 1024 * 1024) {
                    AppToast('Ukuran file maksimal 2 MB.', 'warning');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('avatarPreviewArea').innerHTML =
                        '<img src="' + e.target.result + '" alt="avatar" class="initials-avatar" id="avatarPreview" style="width:96px;height:96px;font-size:34px;border-radius:50%;object-fit:cover">';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush