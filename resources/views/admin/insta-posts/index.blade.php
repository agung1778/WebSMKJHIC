@extends('layouts.admin-app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ __('Feed Instagram') }}</h1>
                <p class="text-xs text-slate-500 mt-1">{{ __('Semi-otomatis: unggah foto postingan Instagram yang ingin ditampilkan di website. Maksimal 16 postingan — data paling lama otomatis terhapus saat ada data baru.') }}</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $activeCount = $posts->where('is_active', true)->count();
        @endphp

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-square-plus text-[#6CF600]"></i> {{ __('Tambah Postingan Baru') }}
            </h2>
            <form action="{{ route('admin.insta-posts.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Foto Postingan') }}</label>
                        <div id="drop-zone"
                            class="flex flex-col justify-center items-center w-full py-8 px-4 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-[#6CF600] transition-all group">
                            <div class="bg-white p-3 rounded-full shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                <i class="fa-brands fa-instagram text-2xl text-slate-400 group-hover:text-[#6CF600]"></i>
                            </div>
                            <p class="text-sm text-slate-600 mb-1"><span class="font-bold text-[#6CF600]">{{ __('Klik untuk memilih') }}</span> {{ __('atau seret file ke sini') }}</p>
                            <p class="text-xs text-slate-400">{{ __('Mendukung: PNG, JPG, JPEG, WEBP, AVIF (Maks. 5MB)') }}</p>
                            <p id="file-name-display"
                                class="text-sm font-bold text-slate-800 mt-3 px-3 py-1 bg-slate-200 rounded-lg empty:hidden">
                            </p>
                        </div>
                        <input type="file" name="image_file" id="image_file" class="hidden" accept="image/*">
                        @error('image_file')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="caption" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Caption') }}
                                ({{ __('Opsional') }})</label>
                            <textarea name="caption" id="caption" rows="3"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white text-sm placeholder-slate-400"
                                placeholder="{{ __('Tulis caption postingan...') }}">{{ old('caption') }}</textarea>
                            @error('caption')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="post_url" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Link Postingan') }}
                                ({{ __('Opsional') }})</label>
                            <input type="url" name="post_url" id="post_url" value="{{ old('post_url') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white text-sm placeholder-slate-400"
                                placeholder="https://www.instagram.com/p/...">
                            @error('post_url')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="w-4 h-4 rounded border-slate-300 text-[#6CF600] focus:ring-[#6CF600]">
                            <span class="text-sm text-slate-600">{{ __('Tampilkan langsung di website') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> {{ __('Tambah Postingan') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">{{ __('Daftar Postingan') }}
                    <span
                        class="ml-2 px-2 py-0.5 bg-[#6CF600]/10 text-[#6CF600] rounded-full text-[10px] font-bold">{{ $activeCount }}
                        {{ __('aktif') }}</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-4 px-6 w-24">{{ __('Pratinjau') }}</th>
                            <th class="py-4 px-6">{{ __('Caption') }}</th>
                            <th class="py-4 px-6">{{ __('Link Postingan') }}</th>
                            <th class="py-4 px-6">{{ __('Status') }}</th>
                            <th class="py-4 px-6">{{ __('Ditambahkan') }}</th>
                            <th class="py-4 px-6 text-center">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        @forelse($posts as $post)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6">
                                    <img src="{{ asset('storage/' . $post->path) }}" alt="{{ $post->caption }}"
                                        class="w-14 h-14 object-cover rounded-lg shadow-sm cursor-pointer border border-slate-200"
                                        onclick="window.open(this.src)">
                                </td>
                                <td class="py-4 px-6 max-w-[280px]">
                                    <span class="text-slate-800 line-clamp-2">{{ $post->caption ?: '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($post->post_url)
                                        <a href="{{ $post->post_url }}" target="_blank"
                                            class="text-blue-600 hover:underline font-medium text-xs">{{ __('Buka') }}</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $post->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $post->is_active ? __('Aktif') : __('Tersembunyi') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs">{{ $post->created_at->format('d M Y') }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.insta-posts.toggle', $post->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg {{ $post->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition-colors"
                                                title="{{ $post->is_active ? __('Sembunyikan') : __('Tampilkan') }}">
                                                <i class="fa-solid {{ $post->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                                            </button>
                                        </form>
                                        <button type="button" data-edit-target="{{ $post->id }}"
                                            class="js-edit-btn w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                            title="{{ __('Edit') }}">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.insta-posts.destroy', $post->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('Hapus postingan ini secara permanen?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                title="{{ __('Hapus') }}">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500 text-sm">{{ __('Belum ada postingan Instagram yang ditambahkan.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-edit-close></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">{{ __('Edit Postingan') }}</h3>
                <button type="button" data-edit-close
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="edit-form" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-4">
                    <img id="edit-preview" src="" alt="{{ __('Pratinjau') }}"
                        class="w-20 h-20 object-cover rounded-xl border border-slate-200">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-600 mb-1">{{ __('Ganti Foto') }} ({{ __('Opsional') }})</label>
                        <input type="file" name="image_file" accept="image/*"
                            class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-xs file:font-bold file:text-slate-600 hover:file:bg-slate-200">
                    </div>
                </div>
                <div>
                    <label for="edit_caption" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Caption') }}</label>
                    <textarea name="caption" id="edit_caption" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white text-sm placeholder-slate-400"></textarea>
                </div>
                <div>
                    <label for="edit_post_url" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Link Postingan') }}</label>
                    <input type="url" name="post_url" id="edit_post_url"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#6CF600] focus:bg-white text-sm placeholder-slate-400"
                        placeholder="https://www.instagram.com/p/...">
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active"
                        class="w-4 h-4 rounded border-slate-300 text-[#6CF600] focus:ring-[#6CF600]">
                    <span class="text-sm text-slate-600">{{ __('Tampilkan di website') }}</span>
                </label>
                <div class="flex justify-end pt-2 border-t border-slate-100 gap-2">
                    <button type="button" data-edit-close
                        class="px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100">{{ __('Batal') }}</button>
                    <button type="submit"
                        class="bg-[#6CF600] text-black px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#5bd300] transition-colors">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('drop-zone');
            const imageFileInput = document.getElementById('image_file');
            const fileNameDisplay = document.getElementById('file-name-display');

            dropZone.addEventListener('click', () => imageFileInput.click());

            imageFileInput.addEventListener('change', () => {
                fileNameDisplay.textContent = imageFileInput.files.length > 0 ? imageFileInput.files[0].name :
                    '';
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-[#6CF600]', 'bg-[#6CF600]/10');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-[#6CF600]', 'bg-[#6CF600]/10');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-[#6CF600]', 'bg-[#6CF600]/10');
                if (e.dataTransfer.files.length > 0) {
                    imageFileInput.files = e.dataTransfer.files;
                    imageFileInput.dispatchEvent(new Event('change'));
                }
            });

            const modal = document.getElementById('edit-modal');
            const editForm = document.getElementById('edit-form');
            const editPreview = document.getElementById('edit-preview');
            const editCaption = document.getElementById('edit_caption');
            const editPostUrl = document.getElementById('edit_post_url');
            const editActive = document.getElementById('edit_is_active');

            const posts = @json($posts);

            document.querySelectorAll('.js-edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const post = posts.find(p => p.id == btn.dataset.editTarget);
                    if (!post) return;
                    const base = window.location.origin;
                    editForm.action = `{{ url('admin/insta-posts') }}/${post.id}`;
                    editPreview.src = base + '/storage/' + post.path;
                    editCaption.value = post.caption || '';
                    editPostUrl.value = post.post_url || '';
                    editActive.checked = !!post.is_active;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.querySelectorAll('[data-edit-close]').forEach(el => {
                el.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        });
    </script>
@endsection
