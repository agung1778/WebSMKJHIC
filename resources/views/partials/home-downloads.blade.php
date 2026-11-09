<style>
    .home-downloads { padding: clamp(32px, 5vw, 64px) clamp(16px, 4vw, 32px); }
    .home-downloads__card { max-width: 1216px; margin-inline: auto; display: grid; grid-template-columns: 1.15fr 1fr; gap: clamp(24px, 4vw, 56px); align-items: center; padding: clamp(24px, 4vw, 48px); border: 1px solid #d6e7ca; border-radius: 24px; background: #eff7e9; color: #20321c; overflow-wrap: anywhere; }
    .home-downloads__card > * { min-width: 0; }
    .home-downloads__eyebrow { display: flex; align-items: center; gap: 8px; color: #46712d; font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .home-downloads__card h2 { margin: 12px 0; font-size: clamp(26px, 3vw, 36px); font-weight: 700; line-height: 1.25; }
    .home-downloads__intro { max-width: 450px; margin: 0; font-size: 14px; line-height: 1.8; color: #586850; }
    .home-downloads__cta { display: inline-flex; align-items: center; justify-content: center; gap: 14px; min-height: 46px; margin-top: 24px; padding: 12px 20px; border-radius: 10px; background: #365f21; color: #fff; font-size: 13px; font-weight: 600; text-decoration: none; }
    .home-downloads__cta:hover { background: #294b18; }
    .home-downloads__links { display: grid; gap: 12px; }
    .home-downloads__link { display: flex; align-items: center; gap: 14px; min-width: 0; padding: 20px; border: 1px solid #dce8d4; border-radius: 14px; background: #fff; color: #20321c; text-decoration: none; }
    .home-downloads__link:hover { border-color: #7ca65f; background: #fbfdf9; }
    .home-downloads__icon { display: flex; align-items: center; justify-content: center; flex: 0 0 44px; height: 44px; border-radius: 12px; background: #edf5e7; color: #46712d; font-size: 19px; }
    .home-downloads__label { flex: 1; min-width: 0; }
    .home-downloads__label strong { display: block; font-size: 14px; font-weight: 600; }
    .home-downloads__label span { display: block; margin-top: 5px; color: #687460; font-size: 12px; line-height: 1.6; }
    .home-downloads__arrow { flex-shrink: 0; color: #46712d; font-size: 13px; }
    .home-downloads a:focus-visible { outline: 3px solid #46712d; outline-offset: 4px; }
    @media (max-width: 767px) {
        .home-downloads__card { grid-template-columns: minmax(0, 1fr); border-radius: 18px; }
        .home-downloads__link { padding: 16px; gap: 12px; }
    }
    @media (max-width: 359px) {
        .home-downloads__link { flex-wrap: wrap; }
        .home-downloads__label { flex-basis: calc(100% - 56px); }
        .home-downloads__arrow { display: none; }
        .home-downloads__cta { width: 100%; }
    }
</style>

<section class="home-downloads" aria-labelledby="home-downloads-title">
    <div class="home-downloads__card">
        <div>
            <span class="home-downloads__eyebrow"><i class="fa-solid fa-folder-open" aria-hidden="true"></i> Berkas Sekolah</span>
            <h2 id="home-downloads-title">Pusat Unduhan</h2>
            <p class="home-downloads__intro">Butuh logo sekolah, logo perusahaan, atau referensi laporan PKL? Temukan berkas yang kamu perlukan dalam satu tempat.</p>
            <a class="home-downloads__cta" href="{{ route('public.downloads.index') }}">Lihat Unduhan <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="home-downloads__links">
            <a class="home-downloads__link" href="{{ route('public.downloads.index', ['kategori' => 'logo']) }}">
                <span class="home-downloads__icon"><i class="fa-regular fa-image" aria-hidden="true"></i></span>
                <span class="home-downloads__label"><strong>Logo Sekolah</strong><span>Unduh logo dalam kualitas asli.</span></span>
                <i class="fa-solid fa-arrow-right home-downloads__arrow" aria-hidden="true"></i>
            </a>
            <a class="home-downloads__link" href="{{ route('public.downloads.index', ['kategori' => 'company_logo']) }}">
                <span class="home-downloads__icon"><i class="fa-regular fa-building" aria-hidden="true"></i></span>
                <span class="home-downloads__label"><strong>Logo Perusahaan</strong><span>Unduh logo perusahaan mitra.</span></span>
                <i class="fa-solid fa-arrow-right home-downloads__arrow" aria-hidden="true"></i>
            </a>
            <a class="home-downloads__link" href="{{ route('public.downloads.index', ['kategori' => 'pkl']) }}">
                <span class="home-downloads__icon"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>
                <span class="home-downloads__label"><strong>Contoh Laporan PKL</strong><span>Temukan referensi sesuai jurusanmu.</span></span>
                <i class="fa-solid fa-arrow-right home-downloads__arrow" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>
