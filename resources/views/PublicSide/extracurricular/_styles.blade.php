{{-- Styles bersama untuk halaman Ekstrakurikuler (index & detail) --}}
<style>
    :root {
        --ec-green: #00875a;
        --ec-green-hover: #00704a;
        --ec-green-soft: #e6f4ea;
        --ec-ink: #111827;
        --ec-muted: #6b7280;
        --ec-border: #e5e7eb;
        --ec-bg-soft: #f8fafc;
        --ec-dark: #1f1f20;
    }

    /* Chip label hijau & status */
    .ec-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.75rem; border-radius: 9999px;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.03em; line-height: 1.2;
        color: #ffffff; background: rgba(20, 20, 22, 0.88);
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }
    .ec-chip--green { color: #ffffff; background: #00875a; box-shadow: 0 2px 8px -2px rgba(0, 135, 90, 0.5); }
    .ec-chip--type-wajib { background: #d97706; color: #ffffff; box-shadow: 0 2px 8px -2px rgba(217, 119, 6, 0.5); }
    .ec-chip--type-pilihan { background: #0284c7; color: #ffffff; box-shadow: 0 2px 8px -2px rgba(2, 132, 199, 0.5); }

    /* Metadatum */
    .ec-meta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 500; color: var(--ec-muted);
    }
    .ec-meta i { color: var(--ec-green); width: 0.9rem; text-align: center; }

    /* Kartu ekstrakurikuler — seragam & responsif */
    .ec-card {
        display: flex; flex-direction: column; height: 100%; min-width: 0;
        background: #ffffff; border: 1px solid var(--ec-border); border-radius: 1rem;
        overflow: hidden; box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .ec-card:hover, .ec-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px -14px rgba(16, 24, 40, 0.14);
        border-color: var(--ec-green);
    }
    .ec-card:focus-visible { outline: 2px solid var(--ec-green); outline-offset: 2px; }
    .ec-card__thumb {
        position: relative; width: 100%; aspect-ratio: 16 / 10; flex-shrink: 0;
        background: linear-gradient(145deg, #1f1f20, #2d2d2e); overflow: hidden;
    }
    .ec-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.45s ease; }
    .ec-card:hover .ec-card__thumb img { transform: scale(1.05); }
    .ec-card__hint {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--ec-green); font-weight: 700; font-size: 0.82rem;
        transition: gap 0.2s ease;
    }
    .ec-card:hover .ec-card__hint { gap: 0.65rem; }

    /* Fallback gambar (tanpa foto) */
    .ec-thumb-fallback {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(145deg, #1f1f20 0%, #2d2d2e 55%, #343435 100%);
        position: relative;
    }
    .ec-thumb-fallback i { font-size: 3rem; color: rgba(255, 255, 255, 0.2); }
    .ec-thumb-fallback--big i { font-size: 4.5rem; }
    .ec-thumb-fallback::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 15%, rgba(0, 135, 90, 0.2) 0%, transparent 55%);
    }

    /* Grid kartu — tinggi baris seragam */
    .ec-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        grid-auto-rows: 1fr;
        align-items: stretch;
        gap: 1.5rem;
    }
    @media (min-width: 640px) { .ec-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .ec-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

    /* Tipografi isi artikel */
    .ec-article { font-size: 1.02rem; line-height: 1.85; color: #374151; word-wrap: break-word; }
    .ec-article > * + * { margin-top: 1.15rem; }
    .ec-article h2 { font-size: 1.5rem; line-height: 1.3; font-weight: 800; color: #111827; margin-top: 2.25rem; }
    .ec-article h3 { font-size: 1.25rem; line-height: 1.35; font-weight: 700; color: #111827; margin-top: 1.9rem; }
    .ec-article a { color: var(--ec-green); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .ec-article a:hover { color: var(--ec-green-hover); }
    .ec-article img, .ec-article video { max-width: 100%; height: auto; border-radius: 1rem; box-shadow: 0 10px 30px -12px rgba(16, 24, 40, 0.2); margin: 1.5rem 0; }
    .ec-article blockquote { border-left: 4px solid var(--ec-green); background: #f0fdf4; border-radius: 0.6rem; padding: 1rem 1.25rem; font-style: italic; color: #374151; }
    .ec-article ul { list-style: disc; padding-left: 1.4rem; }
    .ec-article ol { list-style: decimal; padding-left: 1.4rem; }
    .ec-article li { margin-top: 0.5rem; }
    .ec-article li::marker { color: var(--ec-green); }
    .ec-article table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    .ec-article th, .ec-article td { border: 1px solid var(--pg-border); padding: 0.6rem 0.75rem; text-align: left; }
    .ec-article th { background: #f8fafc; font-weight: 700; color: #111827; }
    .ec-article hr { border: none; border-top: 1px solid var(--ec-border); margin: 2rem 0; }
    .ec-article iframe { max-width: 100%; border-radius: 0.75rem; }

    /* Sidebar (halaman detail) */
    .ec-side { background: #ffffff; border: 1px solid #eef0f3; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
    .ec-side__head {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--ec-border);
        font-weight: 800; font-size: 1rem; color: #111827;
    }
    .ec-side__head i { color: var(--ec-green); }
    .ec-side__body { padding: 1.25rem; }
    .ec-side__row { display: flex; align-items: center; gap: 0.85rem; }
    .ec-side__icon {
        flex-shrink: 0; width: 2.25rem; height: 2.25rem; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        background: var(--ec-green-soft); color: var(--ec-green); border: 1px solid rgba(0,135,90,0.15); font-size: 0.85rem;
    }

    .ec-rel {
        display: flex; align-items: flex-start; gap: 0.9rem;
        padding: 0.75rem 0.5rem; border-radius: 0.9rem;
        transition: background 0.2s ease;
    }
    .ec-rel:hover { background: #f8fafc; }
    .ec-rel__thumb {
        position: relative; width: 4.5rem; height: 4.5rem; flex-shrink: 0; overflow: hidden;
        border-radius: 0.8rem; background: linear-gradient(145deg, #1f1f20, #2d2d2e);
    }
    .ec-rel__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ec-rel__thumb i { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.3); font-size: 1.2rem; }
</style>