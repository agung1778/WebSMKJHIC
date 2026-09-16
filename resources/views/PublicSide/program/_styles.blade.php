{{-- Styles bersama untuk halaman Program (index & detail) --}}
<style>
    :root {
        --pg-green: #63cd00;
        --pg-green-soft: #eefde2;
        --pg-ink: #111827;
        --pg-muted: #6b7280;
        --pg-border: #e5e7eb;
        --pg-bg-soft: #f8fafc;
        --pg-dark: #282829;
    }

    /* Chip label hijau */
    .pg-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 0.7rem; border-radius: 9999px;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 0.03em; line-height: 1.2;
        color: #ffffff; background: rgba(40, 40, 41, 0.85);
        backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    }
    .pg-chip--green { color: #ffffff; background: rgba(99, 205, 0, 0.92); box-shadow: 0 2px 8px -2px rgba(99, 205, 0, 0.5); }

    /* Metadatum */
    .pg-meta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 500; color: var(--pg-muted);
    }
    .pg-meta i { color: var(--pg-green); width: 0.9rem; text-align: center; }

    /* Kartu program — seragam & responsif */
    .pg-card {
        display: flex; flex-direction: column; height: 100%; min-width: 0;
        background: #ffffff; border: 1px solid var(--pg-border); border-radius: 1rem;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .pg-card:hover, .pg-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px -14px rgba(16, 24, 40, 0.16);
        border-color: var(--pg-green);
    }
    .pg-card:focus-visible { outline: 2px solid var(--pg-green); outline-offset: 2px; }
    .pg-card__thumb {
        position: relative; width: 100%; aspect-ratio: 4 / 3; flex-shrink: 0;
        background: linear-gradient(145deg, #282829, #1f2937); overflow: hidden;
    }
    .pg-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.45s ease; }
    .pg-card:hover .pg-card__thumb img { transform: scale(1.05); }
    .pg-card__hint {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--pg-green); font-weight: 700; font-size: 0.82rem;
        transition: gap 0.2s ease;
    }
    .pg-card:hover .pg-card__hint { gap: 0.65rem; }

    /* Fallback gambar (program tanpa foto) */
    .pg-thumb-fallback {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(145deg, #282829 0%, #343435 55%, #1f2937 100%);
        position: relative;
    }
    .pg-thumb-fallback i { font-size: 3rem; color: rgba(255, 255, 255, 0.18); }
    .pg-thumb-fallback--big i { font-size: 4.5rem; }
    .pg-thumb-fallback::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 80% 15%, rgba(99, 205, 0, 0.18) 0%, transparent 55%);
    }

    /* Grid kartu — tinggi baris seragam */
    .pg-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        grid-auto-rows: 1fr;
        align-items: stretch;
        gap: 1.5rem;
    }
    @media (min-width: 640px) { .pg-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1024px) { .pg-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

    /* Tipografi isi artikel */
    .pg-article { font-size: 1.02rem; line-height: 1.85; color: #374151; word-wrap: break-word; }
    .pg-article > * + * { margin-top: 1.15rem; }
    .pg-article h2 { font-size: 1.5rem; line-height: 1.3; font-weight: 800; color: #111827; margin-top: 2.25rem; }
    .pg-article h3 { font-size: 1.25rem; line-height: 1.35; font-weight: 700; color: #111827; margin-top: 1.9rem; }
    .pg-article a { color: #4b8f00; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .pg-article a:hover { color: var(--pg-green); }
    .pg-article img, .pg-article video { max-width: 100%; height: auto; border-radius: 1rem; box-shadow: 0 10px 30px -12px rgba(16, 24, 40, 0.25); margin: 1.5rem 0; }
    .pg-article blockquote { border-left: 4px solid var(--pg-green); background: #f6faf0; border-radius: 0.6rem; padding: 1rem 1.25rem; font-style: italic; color: #374151; }
    .pg-article ul { list-style: disc; padding-left: 1.4rem; }
    .pg-article ol { list-style: decimal; padding-left: 1.4rem; }
    .pg-article li { margin-top: 0.5rem; }
    .pg-article li::marker { color: var(--pg-green); }
    .pg-article table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    .pg-article th, .pg-article td { border: 1px solid var(--pg-border); padding: 0.6rem 0.75rem; text-align: left; }
    .pg-article th { background: #f8fafc; font-weight: 700; color: #111827; }
    .pg-article hr { border: none; border-top: 1px solid var(--pg-border); margin: 2rem 0; }
    .pg-article iframe { max-width: 100%; border-radius: 0.75rem; }

    /* Sidebar (halaman detail) */
    .pg-side { background: var(--pg-bg-soft); border: 1px solid #eef0f3; border-radius: 1.25rem; overflow: hidden; }
    .pg-side__head {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--pg-border);
        font-weight: 800; font-size: 1rem; color: #111827;
    }
    .pg-side__head i { color: var(--pg-green); }
    .pg-side__body { padding: 1.25rem; }
    .pg-side__row { display: flex; align-items: center; gap: 0.85rem; }
    .pg-side__icon {
        flex-shrink: 0; width: 2.25rem; height: 2.25rem; border-radius: 0.7rem;
        display: flex; align-items: center; justify-content: center;
        background: #ffffff; color: #3f8600; border: 1px solid var(--pg-border); font-size: 0.8rem;
    }

    .pg-rel {
        display: flex; align-items: flex-start; gap: 0.9rem;
        padding: 0.75rem 0.4rem; border-radius: 0.9rem;
        transition: background 0.2s ease;
    }
    .pg-rel:hover { background: #ffffff; }
    .pg-rel__thumb {
        position: relative; width: 4.5rem; height: 4.5rem; flex-shrink: 0; overflow: hidden;
        border-radius: 0.8rem; background: linear-gradient(145deg, #282829, #1f2937);
    }
    .pg-rel__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .pg-rel__thumb i { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.3); font-size: 1.2rem; }

    /* Keunggulan list */
    .pg-advantages { list-style: none; padding: 0; display: flex; flex-direction: column; gap: 0.75rem; }
    .pg-advantages li {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 1rem 1.25rem; background: #ffffff; border: 1px solid var(--pg-border); border-radius: 0.9rem;
    }
    .pg-advantages .pg-adv-icon {
        flex-shrink: 0; width: 2rem; height: 2rem; border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        background: var(--pg-green-soft); color: #3f8600; font-size: 0.85rem;
    }
</style>