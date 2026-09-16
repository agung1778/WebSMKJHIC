{{-- Styles bersama untuk halaman Fasilitas (index & detail) --}}
<style>
    :root {
        --fc-green: #63cd00;
        --fc-green-soft: #eefde2;
        --fc-ink: #111827;
        --fc-muted: #6b7280;
        --fc-border: #e5e7eb;
        --fc-bg-soft: #f8fafc;
        --fc-dark: #282829;
    }

    /* Chip label */
    .fc-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.7rem; border-radius: 9999px;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 0.03em; line-height: 1.2;
        color: #ffffff; background: rgba(40, 40, 41, 0.85);
        backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    }
    .fc-chip--green { color: #ffffff; background: rgba(99, 205, 0, 0.92); box-shadow: 0 2px 8px -2px rgba(99, 205, 0, 0.5); }

    /* Metadatum */
    .fc-meta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 500; color: var(--fc-muted);
    }
    .fc-meta i { color: var(--fc-green); width: 0.9rem; text-align: center; }

    /* Kartu fasilitas — seragam & responsif */
    .fc-card {
        display: flex; flex-direction: column; height: 100%; min-width: 0;
        background: #ffffff; border: 1px solid var(--fc-border); border-radius: 1rem;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .fc-card:hover, .fc-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px -14px rgba(16, 24, 40, 0.16);
        border-color: var(--fc-green);
    }
    .fc-card:focus-visible { outline: 2px solid var(--fc-green); outline-offset: 2px; }
    .fc-card__thumb {
        position: relative; width: 100%; aspect-ratio: 4 / 3; flex-shrink: 0;
        background: linear-gradient(145deg, #282829, #1f2937); overflow: hidden;
    }
    .fc-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.45s ease; }
    .fc-card:hover .fc-card__thumb img { transform: scale(1.05); }
    .fc-card__hint {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--fc-green); font-weight: 700; font-size: 0.82rem;
        transition: gap 0.2s ease;
    }
    .fc-card:hover .fc-card__hint { gap: 0.65rem; }

    /* Fallback gambar (tanpa foto) */
    .fc-thumb-fallback {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(145deg, #282829 0%, #343435 55%, #1f2937 100%);
        position: relative;
    }
    .fc-thumb-fallback i { font-size: 3rem; color: rgba(255, 255, 255, 0.18); }
    .fc-thumb-fallback--big i { font-size: 4.5rem; }
    .fc-thumb-fallback::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 15%, rgba(99, 205, 0, 0.18) 0%, transparent 55%);
    }

    /* Grid kartu — tinggi baris seragam */
    .fc-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        grid-auto-rows: 1fr;
        align-items: stretch;
        gap: 1.5rem;
    }
    @media (min-width: 640px) { .fc-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .fc-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

    /* Radio filter (tanpa JS) — mirip achievement */
    .fc-filter { position: relative; }
    .fc-filter-input {
        position: absolute; width: 1px; height: 1px; opacity: 0;
        pointer-events: none; margin: 0; padding: 0;
    }
    .fc-tabs {
        display: inline-flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;
        padding: 0.375rem; background: var(--fc-bg-soft);
        border: 1px solid var(--fc-border); border-radius: 9999px; max-width: 100%;
    }
    .fc-tab {
        display: inline-flex; align-items: center; gap: 0.4rem; flex: 0 0 auto;
        padding: 0.5rem 1rem; border-radius: 9999px;
        font-size: 0.82rem; font-weight: 600; line-height: 1.2; white-space: nowrap;
        background: transparent; color: #4b5563; cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }
    .fc-tab .fc-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 1.35rem; height: 1.35rem; padding: 0 0.35rem; border-radius: 9999px;
        font-size: 0.68rem; font-weight: 700; color: #4b5563; background: #e5e7eb;
    }
    .fc-tab:hover { background: #e5e7eb; color: var(--fc-ink); }
    @media (max-width: 599px) {
        .fc-tabs { display: flex; flex-wrap: wrap; border-radius: 1rem; }
        .fc-tab { flex: 1 1 calc(50% - 0.5rem); padding: 0.55rem 0.5rem; justify-content: center; }
    }

    .fc-grid .fc-card { display: none; }
    .fc-empty { display: none; grid-column: 1 / -1; }
    .fc-empty__inner {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center; gap: 0.75rem; padding: 3.5rem 1.5rem;
        border: 1.5px dashed #d1d5db; border-radius: 1rem; background: var(--fc-bg-soft);
    }

    /* Semua */
    #fcr-all:checked ~ .fc-grid .fc-card { display: flex; }
    #fcr-all:checked ~ .fc-tabs label[for="fcr-all"],
    #fcr-all:checked ~ .fc-empty--all { display: flex; }
    #fcr-all:checked ~ .fc-tabs label[for="fcr-all"] {
        background: var(--fc-green); color: #ffffff;
        box-shadow: 0 4px 12px -2px rgba(99, 205, 0, 0.45);
    }
    #fcr-all:checked ~ .fc-tabs label[for="fcr-all"] .fc-count { background: rgba(255,255,255,.25); color: #ffffff; }

    /* Tipografi isi artikel */
    .fc-article { font-size: 1.02rem; line-height: 1.85; color: #374151; word-wrap: break-word; }
    .fc-article > * + * { margin-top: 1.15rem; }
    .fc-article h2 { font-size: 1.5rem; line-height: 1.3; font-weight: 800; color: #111827; margin-top: 2.25rem; }
    .fc-article h3 { font-size: 1.25rem; line-height: 1.35; font-weight: 700; color: #111827; margin-top: 1.9rem; }
    .fc-article a { color: #4b8f00; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .fc-article a:hover { color: var(--fc-green); }
    .fc-article img, .fc-article video { max-width: 100%; height: auto; border-radius: 1rem; box-shadow: 0 10px 30px -12px rgba(16, 24, 40, 0.25); margin: 1.5rem 0; }
    .fc-article blockquote { border-left: 4px solid var(--fc-green); background: #f6faf0; border-radius: 0.6rem; padding: 1rem 1.25rem; font-style: italic; color: #374151; }
    .fc-article ul { list-style: disc; padding-left: 1.4rem; }
    .fc-article ol { list-style: decimal; padding-left: 1.4rem; }
    .fc-article li { margin-top: 0.5rem; }
    .fc-article li::marker { color: var(--fc-green); }
    .fc-article table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    .fc-article th, .fc-article td { border: 1px solid var(--fc-border); padding: 0.6rem 0.75rem; text-align: left; }
    .fc-article th { background: #f8fafc; font-weight: 700; color: #111827; }
    .fc-article hr { border: none; border-top: 1px solid var(--fc-border); margin: 2rem 0; }
    .fc-article iframe { max-width: 100%; border-radius: 0.75rem; }

    /* Sidebar (halaman detail) */
    .fc-side { background: var(--fc-bg-soft); border: 1px solid #eef0f3; border-radius: 1.25rem; overflow: hidden; }
    .fc-side__head {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--fc-border);
        font-weight: 800; font-size: 1rem; color: #111827;
    }
    .fc-side__head i { color: var(--fc-green); }
    .fc-side__body { padding: 1.25rem; }
    .fc-side__row { display: flex; align-items: center; gap: 0.85rem; }
    .fc-side__icon {
        flex-shrink: 0; width: 2.25rem; height: 2.25rem; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        background: #ffffff; color: #3f8600; border: 1px solid var(--fc-border); font-size: 0.8rem;
    }

    .fc-rel {
        display: flex; align-items: flex-start; gap: 0.9rem;
        padding: 0.75rem 0.4rem; border-radius: 0.9rem;
        transition: background 0.2s ease;
    }
    .fc-rel:hover { background: #ffffff; }
    .fc-rel__thumb {
        position: relative; width: 4.5rem; height: 4.5rem; flex-shrink: 0; overflow: hidden;
        border-radius: 0.8rem; background: linear-gradient(145deg, #282829, #1f2937);
    }
    .fc-rel__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .fc-rel__thumb i { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.3); font-size: 1.2rem; }
</style>