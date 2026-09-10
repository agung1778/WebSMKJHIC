@extends('layouts.admin-app')

@section('title', 'Feed Instagram Curator')

@section('content')
    <div class="fade-up space-y-6">
        <x-admin-components::page-header
            icon="fa-brands fa-instagram"
            kicker="Website"
            title="Feed Instagram Curator"
            subtitle="Pratinjau feed Instagram otomatis yang diambil dari Curator.io untuk website." />

        <div class="grid gap-6 lg:grid-cols-3 items-start">
            <div class="lg:col-span-2 space-y-6">
                <div class="app-card overflow-hidden">
                    <div class="p-5 border-b" style="border-color:var(--border)">
                        <h3 class="card-title"><i class="fa-solid fa-rss" style="color:var(--brand)"></i> Pratinjau Feed</h3>
                        <p class="text-sm" style="color:var(--text-3)">Tampilan langsung feed yang disediakan oleh Curator.io. Pastikan koneksi internet aktif.</p>
                    </div>
                    <div class="p-5" id="curator-feed-grid-layout">
                        <div id="curator-feed-default-feed-layout">
                            <a href="https://curator.io" target="_blank" rel="noopener" class="crt-logo crt-tag">Powered by Curator.io</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="form-section p-6">
                    <h4 class="form-section-title mb-4"><i class="fa-solid fa-circle-info" style="color:var(--brand)"></i> Cara Kerja</h4>
                    <ul class="space-y-3" style="color:var(--text-2)">
                        <li class="flex gap-3"><span class="badge badge-info" style="flex-shrink:0">1</span><span class="text-sm">Feed dikelola otomatis dari akun Instagram sekolah via Curator.io.</span></li>
                        <li class="flex gap-3"><span class="badge badge-info" style="flex-shrink:0">2</span><span class="text-sm">Postingan terbaru muncul otomatis di bagian "Feed Instagram" website tanpa perlu unggah ulang.</span></li>
                        <li class="flex gap-3"><span class="badge badge-info" style="flex-shrink:0">3</span><span class="text-sm">Maksimal 16 postingan tampil di feed website.</span></li>
                    </ul>
                </div>
                <div class="form-section p-6">
                    <h4 class="form-section-title mb-4"><i class="fa-solid fa-wrench" style="color:var(--brand)"></i> Kelola Feed</h4>
                    <p class="text-sm mb-4" style="color:var(--text-2)">Kelola, moderasi, dan atur tampilan feed di panel Curator.io.</p>
                    <a class="app-btn app-btn-primary app-btn-lg w-full justify-center" href="https://curator.io" target="_blank" rel="noopener">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Curator.io
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @php
        $feedId = '9b122a7e-d39e-40c4-abc3-8ab6bc446899';
    @endphp
    <script type="text/javascript">
        (function () {
            var i, e, d = document, s = "script"; i = d.createElement(s); i.async = 1; i.charset = "UTF-8";
            i.src = "https://cdn.curator.io/published/{{ $feedId }}.js";
            e = d.getElementsByTagName(s)[0]; e.parentNode.insertBefore(i, e);
        })();
    </script>
@endpush