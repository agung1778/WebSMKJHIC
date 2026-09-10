@extends('layouts.admin-app')

@section('title', 'Update Admin — ' . $user->name)

@section('content')
<style>
    .neon-page { --neon: #7DFF00; --neon-dim: #339900; --navy: #0F172A; --gray-bg: #F8F9FB; }
    .neon-page .card-neon { background: #fff; border-radius: 20px; box-shadow: 0 1px 3px rgba(15,23,42,.04), 0 4px 24px rgba(15,23,42,.06); border: 1px solid #E5E7EB; transition: transform .2s, box-shadow .2s; }
    .neon-page .card-neon:hover { transform: translateY(-2px); box-shadow: 0 2px 6px rgba(15,23,42,.06), 0 12px 36px rgba(15,23,42,.10); }
    .neon-page .input-neon { border-radius: 12px; border: 1px solid #D1D5DB; padding: 10px 14px 10px 40px; font-size: 14px; width: 100%; background: var(--gray-bg); color: var(--navy); transition: border-color .2s, box-shadow .2s; font-family: 'Poppins', sans-serif; }
    .neon-page .input-neon:focus { outline: none; border-color: var(--neon); box-shadow: 0 0 0 3px rgba(125,255,0,.25); background: #fff; }
    .neon-page .input-neon.has-error { border-color: #e53e3e; }
    .neon-page .icon-field { position: relative; }
    .neon-page .icon-field > i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 14px; pointer-events: none; z-index: 1; }
    .neon-page .field-label { font-size: 13px; font-weight: 600; color: #4B5563; margin-bottom: 5px; display: block; }
    .neon-page .btn-neon { border-radius: 12px; font-weight: 600; font-size: 14px; padding: 10px 20px; border: none; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; font-family: 'Poppins', sans-serif; }
    .neon-page .btn-neon-primary { background: var(--neon); color: var(--navy); }
    .neon-page .btn-neon-primary:hover { background: #6bdf00; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(125,255,0,.35); }
    .neon-page .btn-neon-secondary { background: #E5E7EB; color: #4B5563; }
    .neon-page .btn-neon-secondary:hover { background: #D1D5DB; }
    .neon-page .badge-role { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
    .neon-page .badge-superadmin { background: #F3E8FF; color: #9333EA; }
    .neon-page .badge-admin { background: #E8FBF0; color: #16A34A; }
    .neon-page .toggle-wrap { position: relative; width: 48px; height: 26px; display: inline-block; }
    .neon-page .toggle-wrap input { opacity: 0; width: 0; height: 0; position: absolute; }
    .neon-page .toggle-slider { position: absolute; inset: 0; background: #CBD5E1; border-radius: 26px; cursor: pointer; transition: background .25s; }
    .neon-page .toggle-slider::before { content: ''; position: absolute; left: 3px; top: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: transform .25s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
    .neon-page .toggle-wrap input:checked + .toggle-slider { background: var(--neon); }
    .neon-page .toggle-wrap input:checked + .toggle-slider::before { transform: translateX(22px); }
    .neon-page .drop-zone { border: 2px dashed #D1D5DB; border-radius: 16px; padding: 24px; text-align: center; cursor: pointer; transition: all .2s; background: var(--gray-bg); position: relative; }
    .neon-page .drop-zone:hover, .neon-page .drop-zone.dragover { border-color: var(--neon); background: rgba(125,255,0,.06); }
    .neon-page .drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .neon-page .avatar-preview { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--neon); margin: 0 auto 12px; display: block; }
    .neon-page .avatar-initials { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 700; margin: 0 auto 12px; color: #fff; background: linear-gradient(135deg, var(--neon), #339900); border: 3px solid rgba(125,255,0,.4); }
    .neon-page .readonly-item { padding: 12px 16px; border-radius: 12px; background: var(--gray-bg); border: 1px solid #E5E7EB; }
    .neon-page .readonly-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #9CA3AF; }
    .neon-page .readonly-value { font-size: 13px; font-weight: 600; color: var(--navy); margin-top: 2px; }
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .5s ease, transform .5s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>

<div class="neon-page">

    {{-- ============ PROFILE CARD ============ --}}
    <div class="card-neon fade-up p-6 mb-8">
        <div class="flex flex-col lg:flex-row items-center gap-6">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                @if($user->avatar && \Storage::disk('public')->exists($user->avatar))
                    <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->name }}"
                        class="w-14 h-14 rounded-full object-cover border-2 border-[#7DFF00] flex-shrink-0" />
                @else
                    <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 text-white text-lg font-bold"
                        style="background:linear-gradient(135deg,#7DFF00,#339900)">
                        {{ strtoupper(collect(explode(' ', $user->name))->take(2)->map(fn($w)=>mb_substr($w,0,1))->implode('')) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-[#0F172A] truncate">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                <span class="badge-role {{ $user->role === 'superadmin' ? 'badge-superadmin' : 'badge-admin' }} ml-3 flex-shrink-0 hidden sm:inline-flex">
                    @if($user->role === 'superadmin')
                        <i class="fa-solid fa-crown"></i> SUPER ADMIN
                    @else
                        <i class="fa-solid fa-shield-halved"></i> ADMIN
                    @endif
                </span>
            </div>
            <div class="flex items-center gap-3 text-sm">
                @php
                    $isOnline = optional($user->session)->last_activity && (time() - $user->session->last_activity) < 300;
                @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isOnline ? 'bg-[#e8fbf0] text-[#16A34A]' : 'bg-gray-100 text-gray-500' }}">
                    <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-[#16A34A] animate-pulse' : 'bg-gray-400' }}"></span>
                    {{ $isOnline ? 'Session Aktif' : 'Offline' }}
                </span>
            </div>
            {{-- Shield icon --}}
            <div class="hidden lg:flex items-center justify-center w-16 h-16 rounded-2xl flex-shrink-0" style="background:rgba(125,255,0,.1)">
                <i class="fa-solid fa-shield-halved text-3xl" style="color:rgba(125,255,0,.5)"></i>
            </div>
        </div>
    </div>

    {{-- ============ JUDUL ============ --}}
    <div class="mb-8 fade-up">
        <h1 class="text-3xl font-bold text-[#0F172A]">Update Data Admin</h1>
        <p class="text-gray-500 mt-1">Perbarui informasi akun administrator.</p>
    </div>

    {{-- ============ NOTIFIKASI ============ --}}
    @if(session('success'))
        <div class="card-neon fade-up p-4 mb-6 flex items-center gap-3" style="border-left:4px solid #7DFF00">
            <i class="fa-solid fa-circle-check text-xl" style="color:#7DFF00"></i>
            <span class="text-sm font-medium text-[#0F172A]">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="card-neon fade-up p-4 mb-6 flex items-center gap-3" style="border-left:4px solid #e53e3e">
            <i class="fa-solid fa-circle-exclamation text-xl text-red-500"></i>
            <span class="text-sm font-medium text-[#0F172A]">{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- ============ FORM ============ --}}
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ====== LEFT: FORM FIELDS (2 cols on desktop) ====== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- INFORMASI AKUN --}}
                <div class="card-neon fade-up p-6">
                    <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-user-pen" style="color:#7DFF00"></i> Informasi Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="field-label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <div class="icon-field">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="input-neon @error('name') has-error @enderror" placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Email --}}
                        <div>
                            <label class="field-label">Email <span class="text-red-500">*</span></label>
                            <div class="icon-field">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="input-neon @error('email') has-error @enderror" placeholder="admin@smkamaliah.sch.id">
                            </div>
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Username --}}
                        <div>
                            <label class="field-label">Username</label>
                            <div class="icon-field">
                                <i class="fa-solid fa-at"></i>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                    class="input-neon @error('username') has-error @enderror" placeholder="username_opsional">
                            </div>
                            @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- No HP --}}
                        <div>
                            <label class="field-label">Nomor HP</label>
                            <div class="icon-field">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="input-neon @error('phone') has-error @enderror" placeholder="08xxxxxxxxxx">
                            </div>
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- INFORMASI LOGIN --}}
                <div class="card-neon fade-up p-6">
                    <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-lock" style="color:#7DFF00"></i> Informasi Login
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Password Baru --}}
                        <div>
                            <label class="field-label">Password Baru</label>
                            <div class="icon-field">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password"
                                    class="input-neon @error('password') has-error @enderror"
                                    placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                                <button type="button" onclick="togglePw(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0F172A] z-10">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah.</p>
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="field-label">Konfirmasi Password</label>
                            <div class="icon-field">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="password_confirmation"
                                    class="input-neon" placeholder="Ulangi password baru" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HAK AKSES & STATUS --}}
                <div class="card-neon fade-up p-6">
                    <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-sliders" style="color:#7DFF00"></i> Hak Akses & Status
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Role --}}
                        <div>
                            <label class="field-label">Role <span class="text-red-500">*</span></label>
                            <div class="icon-field">
                                <i class="fa-solid fa-shield-halved"></i>
                                <select name="role" class="input-neon @error('role') has-error @enderror" required style="padding-left:40px">
                                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                                    <option value="superadmin" @selected(old('role', $user->role) === 'superadmin')>Super Admin</option>
                                </select>
                            </div>
                            @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        {{-- Status --}}
                        <div>
                            <label class="field-label">Status Akun</label>
                            <div class="flex items-center gap-3 mt-1">
                                <label class="toggle-wrap">
                                    <input type="hidden" name="status" value="inactive">
                                    <input type="checkbox" name="status" value="active"
                                        {{ old('status', $user->status ?? 'active') === 'active' ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="text-sm font-semibold" id="statusLabel"
                                    style="color:{{ old('status', $user->status ?? 'active') === 'active' ? '#16A34A' : '#9CA3AF' }}">
                                    {{ old('status', $user->status ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- TOMBOL --}}
                <div class="flex flex-wrap gap-3 fade-up">
                    <a href="{{ route('admin.users') }}" class="btn-neon btn-neon-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn-neon btn-neon-primary" id="btnSubmit">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            {{-- ====== RIGHT COLUMN ====== --}}
            <div class="space-y-6">

                {{-- FOTO PROFIL --}}
                <div class="card-neon fade-up p-6 text-center">
                    <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2 justify-center">
                        <i class="fa-solid fa-camera" style="color:#7DFF00"></i> Foto Profil
                    </h3>

                    {{-- Preview --}}
                    <div id="avatarPreviewArea">
                        @if($user->avatar && \Storage::disk('public')->exists($user->avatar))
                            <img src="{{ \Storage::disk('public')->url($user->avatar) }}" alt="avatar" class="avatar-preview" id="avatarPreview">
                        @else
                            <div class="avatar-initials" id="avatarPreview">
                                {{ strtoupper(collect(explode(' ', $user->name))->take(2)->map(fn($w)=>mb_substr($w,0,1))->implode('')) }}
                            </div>
                        @endif
                    </div>

                    {{-- Drop zone --}}
                    <div class="drop-zone" id="dropZone">
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" onchange="previewAvatar(this)">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500 font-medium">Drag & drop atau klik untuk upload</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — Maks 2 MB</p>
                    </div>

                    @error('avatar') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror

                    @if($user->avatar)
                        <button type="button" onclick="document.getElementById('removeAvatarInput').value='1'; this.closest('form').submit();"
                            class="mt-3 text-xs text-red-500 hover:underline">
                            <i class="fa-solid fa-trash"></i> Hapus Foto
                        </button>
                        <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">
                    @endif
                </div>

                {{-- INFORMASI TAMBAHAN (READONLY) --}}
                <div class="card-neon fade-up p-6">
                    <h3 class="text-base font-bold text-[#0F172A] mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info" style="color:#7DFF00"></i> Informasi Tambahan
                    </h3>
                    <div class="space-y-3">
                        <div class="readonly-item">
                            <div class="readonly-label">Tanggal Dibuat</div>
                            <div class="readonly-value">{{ $user->created_at->translatedFormat('d M Y, H:i') }}</div>
                        </div>
                        <div class="readonly-item">
                            <div class="readonly-label">Terakhir Login</div>
                            <div class="readonly-value">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    <span class="text-gray-400 italic font-normal">Belum pernah login</span>
                                @endif
                            </div>
                        </div>
                        <div class="readonly-item">
                            <div class="readonly-label">Status Aktivitas</div>
                            <div class="readonly-value">
                                @if($isOnline)
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Online
                                    </span>
                                @else
                                    <span class="text-gray-400 italic font-normal">Offline</span>
                                @endif
                            </div>
                        </div>
                        <div class="readonly-item">
                            <div class="readonly-label">IP Terakhir Login</div>
                            <div class="readonly-value">{{ $user->last_login_ip ?? '—' }}</div>
                        </div>
                        <div class="readonly-item">
                            <div class="readonly-label">Browser Terakhir</div>
                            <div class="readonly-value">{{ $user->last_login_browser ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ============ SWEETALERT + ANIMATION ============ --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fade-up observer
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.fade-up').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), 80 * i);
        });

        @if(session('success'))
            Swal.fire({ icon:'success', title:'Berhasil', text:'{{ session("success") }}', confirmButtonColor:'#7DFF00', confirmButtonText:'OK', customClass:{popup:'rounded-2xl'} });
        @endif
        @if(session('error'))
            Swal.fire({ icon:'error', title:'Gagal', text:'{{ session("error") }}', confirmButtonColor:'#7DFF00', confirmButtonText:'OK', customClass:{popup:'rounded-2xl'} });
        @endif
        @if($errors->any())
            Swal.fire({ icon:'error', title:'Validasi Gagal', html:'@foreach($errors->all() as $err)<p style="text-align:left">{{ $err }}</p>@endforeach', confirmButtonColor:'#7DFF00', confirmButtonText:'OK', customClass:{popup:'rounded-2xl'} });
        @endif

        // Loading on submit
        document.getElementById('editForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        });
    });

    // Toggle password visibility
    function togglePw(btn) {
        const inp = btn.closest('.icon-field').querySelector('input');
        const icon = btn.querySelector('i');
        if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fa-solid fa-eye-slash'; }
        else { inp.type = 'password'; icon.className = 'fa-solid fa-eye'; }
    }

    // Status toggle label
    document.querySelector('input[name="status"]').addEventListener('change', function() {
        const lbl = document.getElementById('statusLabel');
        if (this.checked) { lbl.textContent = 'Aktif'; lbl.style.color = '#16A34A'; }
        else { lbl.textContent = 'Nonaktif'; lbl.style.color = '#9CA3AF'; }
    });

    // Avatar preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                Swal.fire({ icon:'warning', title:'Terlalu Besar', text:'Ukuran maksimal 2 MB.', confirmButtonColor:'#7DFF00' });
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const area = document.getElementById('avatarPreviewArea');
                area.innerHTML = '<img src="' + e.target.result + '" alt="avatar" class="avatar-preview" id="avatarPreview">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Drag & drop highlight
    const dz = document.getElementById('dropZone');
    ['dragenter','dragover'].forEach(evt => dz.addEventListener(evt, e => { e.preventDefault(); dz.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(evt => dz.addEventListener(evt, e => { e.preventDefault(); dz.classList.remove('dragover'); }));
</script>
@endpush
@endsection