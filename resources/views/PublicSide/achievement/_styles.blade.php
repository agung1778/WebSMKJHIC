{{-- Styles bersama untuk halaman Achievement (index & detail) — tema website SMK Amaliah --}}
<style>
    :root {
        --ac-green: #63cd00;
        --ac-green-soft: #eefde2;
        --ac-ink: #111827;
        --ac-muted: #6b7280;
        --ac-border: #e5e7eb;
        --ac-bg-soft: #f8fafc;
        --ac-dark: #282829;
    }

    /* Chip label hijau */
    .ac-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.7rem; border-radius: 9999px;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 0.03em; line-height: 1.2;
        color: #ffffff; background: rgba(40, 40, 41, 0.85);
        backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    }
    .ac-chip--green { color: #ffffff; background: rgba(99, 205, 0, 0.92); box-shadow: 0 2px 8px -2px rgba(99, 205, 0, 0.5); }

    /* Metadatum */
    .ac-meta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 500; color: var(--ac-muted);
    }
    .ac-meta i { color: var(--ac-green); width: 0.9rem; text-align: center; }

    /* Kartu prestasi — seragam & responsif */
    .ac-card {
        display: flex; flex-direction: column; height: 100%; min-width: 0;
        background: #ffffff; border: 1px solid var(--ac-border); border-radius: 1rem;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .ac-card:hover, .ac-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px -14px rgba(16, 24, 40, 0.16);
        border-color: var(--ac-green);
    }
    .ac-card:focus-visible { outline: 2px solid var(--ac-green); outline-offset: 2px; }
    .ac-card__thumb {
        position: relative; width: 100%; aspect-ratio: 4 / 3; flex-shrink: 0;
        background: linear-gradient(145deg, #282829, #1f2937); overflow: hidden;
    }
    .ac-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.45s ease; }
    .ac-card:hover .ac-card__thumb img { transform: scale(1.05); }
    .ac-card__hint {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--ac-green); font-weight: 700; font-size: 0.82rem;
        transition: gap 0.2s ease;
    }
    .ac-card:hover .ac-card__hint { gap: 0.65rem; }

    /* Fallback gambar (prestasi tanpa foto) */
    .ac-thumb-fallback {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(145deg, #282829 0%, #343435 55%, #1f2937 100%);
        position: relative;
    }
    .ac-thumb-fallback i { font-size: 3rem; color: rgba(255, 255, 255, 0.18); }
    .ac-thumb-fallback--big i { font-size: 4.5rem; }
    .ac-thumb-fallback::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 15%, rgba(99, 205, 0, 0.18) 0%, transparent 55%);
    }

    /* Grid kartu — radio filter (tanpa JavaScript) */
    .ac-filter { position: relative; }
    .ac-filter-input {
        position: absolute; width: 1px; height: 1px; opacity: 0;
        pointer-events: none; margin: 0; padding: 0;
    }
    .ac-tabs {
        display: inline-flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;
        padding: 0.375rem; background: var(--ac-bg-soft);
        border: 1px solid var(--ac-border); border-radius: 9999px; max-width: 100%;
    }
    .ac-tab {
        display: inline-flex; align-items: center; gap: 0.4rem; flex: 0 0 auto;
        padding: 0.5rem 1rem; border-radius: 9999px;
        font-size: 0.82rem; font-weight: 600; line-height: 1.2; white-space: nowrap;
        background: transparent; color: #4b5563; cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }
    .ac-tab .ac-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 1.35rem; height: 1.35rem; padding: 0 0.35rem; border-radius: 9999px;
        font-size: 0.68rem; font-weight: 700; color: #4b5563; background: #e5e7eb;
    }
    .ac-tab:hover { background: #e5e7eb; color: var(--ac-ink); }
    @media (max-width: 599px) {
        .ac-tabs { display: flex; flex-wrap: wrap; border-radius: 1rem; }
        .ac-tab { flex: 1 1 calc(50% - 0.5rem); padding: 0.55rem 0.5rem; justify-content: center; }
    }

    /* Kartu disembunyikan default (bukan dipilih), grid menatanya */
    .ac-grid { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); grid-auto-rows: 1fr; align-items: stretch; gap: 1.5rem; }
    @media (min-width: 640px) { .ac-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .ac-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    .ac-grid .ac-card { display: none; }
    .ac-empty { display: none; grid-column: 1 / -1; }
    .ac-empty__inner {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center; gap: 0.75rem; padding: 3.5rem 1.5rem;
        border: 1.5px dashed #d1d5db; border-radius: 1rem; background: var(--ac-bg-soft);
    }

    /* Semua kartu & tombol "Semua" tampil */
    #acr-all:checked ~ .ac-grid .ac-card { display: flex; }
    #acr-all:checked ~ .ac-tabs label[for="acr-all"],
    #acr-all:checked ~ .ac-empty--all { display: flex; }
    #acr-all:checked ~ .ac-tabs label[for="acr-all"] {
        background: var(--ac-green); color: #ffffff;
        box-shadow: 0 4px 12px -2px rgba(99, 205, 0, 0.45);
    }
    #acr-all:checked ~ .ac-tabs label[for="acr-all"] .ac-count { background: rgba(255, 255, 255, 0.25); color: #ffffff; }

    /* Tipografi isi artikel */
    .ac-article { font-size: 1.02rem; line-height: 1.85; color: #374151; word-wrap: break-word; }
    .ac-article > * + * { margin-top: 1.15rem; }
    .ac-article h2 { font-size: 1.5rem; line-height: 1.3; font-weight: 800; color: #111827; margin-top: 2.25rem; }
    .ac-article h3 { font-size: 1.25rem; line-height: 1.35; font-weight: 700; color: #111827; margin-top: 1.9rem; }
    .ac-article a { color: #4b8f00; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .ac-article a:hover { color: var(--ac-green); }
    .ac-article img, .ac-article video { max-width: 100%; height: auto; border-radius: 1rem; box-shadow: 0 10px 30px -12px rgba(16, 24, 40, 0.25); margin: 1.5rem 0; }
    .ac-article blockquote { border-left: 4px solid var(--ac-green); background: #f6faf0; border-radius: 0.6rem; padding: 1rem 1.25rem; font-style: italic; color: #374151; }
    .ac-article ul { list-style: disc; padding-left: 1.4rem; }
    .ac-article ol { list-style: decimal; padding-left: 1.4rem; }
    .ac-article li { margin-top: 0.5rem; }
    .ac-article li::marker { color: var(--ac-green); }
    .ac-article table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    .ac-article th, .ac-article td { border: 1px solid var(--ac-border); padding: 0.6rem 0.75rem; text-align: left; }
    .ac-article th { background: #f8fafc; font-weight: 700; color: #111827; }
    .ac-article hr { border: none; border-top: 1px solid var(--ac-border); margin: 2rem 0; }
    .ac-article iframe { max-width: 100%; border-radius: 0.75rem; }

    /* Sidebar (halaman detail) */
    .ac-side { background: var(--ac-bg-soft); border: 1px solid #eef0f3; border-radius: 1.25rem; overflow: hidden; }
    .ac-side__head {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--ac-border);
        font-weight: 800; font-size: 1rem; color: #111827;
    }
    .ac-side__head i { color: var(--ac-green); }
    .ac-side__body { padding: 1.25rem; }
    .ac-side__row { display: flex; align-items: center; gap: 0.85rem; }
    .ac-side__icon {
        flex-shrink: 0; width: 2.25rem; height: 2.25rem; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        background: #ffffff; color: #3f8600; border: 1px solid var(--ac-border); font-size: 0.8rem;
    }

    .ac-rel {
        display: flex; align-items: flex-start; gap: 0.9rem;
        padding: 0.75rem 0.4rem; border-radius: 0.9rem;
        transition: background 0.2s ease;
    }
    .ac-rel:hover { background: #ffffff; }
    .ac-rel__thumb {
        position: relative; width: 4.5rem; height: 4.5rem; flex-shrink: 0; overflow: hidden;
        border-radius: 0.8rem; background: linear-gradient(145deg, #282829, #1f2937);
    }
    .ac-rel__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ac-rel__thumb i { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.3); font-size: 1.2rem; }
</style>