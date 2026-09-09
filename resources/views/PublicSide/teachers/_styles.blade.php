{{-- Styles bersama untuk halaman Teachers (index & detail) --}}
<style>
    /* Warna tema */
    :root {
        --ts-green: #63cd00;
        --ts-green-soft: #eefde2;
        --ts-ink: #111827;
        --ts-muted: #6b7280;
        --ts-border: #e5e7eb;
        --ts-bg-soft: #f8fafc;
    }

    /* Mensinkronkan tab yang disembunyikan (attribute hidden kalah oleh display grid) */
    .tab-content[hidden] { display: none !important; }

    /* Wrapping halaman */
    .page-section { background: #ffffff; padding-top: 0; padding-bottom: 3rem; overflow-x: hidden; }
    @media (min-width: 640px) { .page-section { padding-bottom: 4rem; } }

    .page-shell { width: 100%; max-width: 1280px; margin-left: auto; margin-right: auto; padding-left: 1rem; padding-right: 1rem; }
    @media (min-width: 640px) { .page-shell { padding-left: 1.5rem; padding-right: 1.5rem; } }
    @media (min-width: 1024px) { .page-shell { padding-left: 2rem; padding-right: 2rem; } }

    /* Hero slider */
    .ts-hero { position: relative; width: 100%; height: 240px; background: #111827; overflow: hidden; }
    @media (min-width: 768px) { .ts-hero { height: 300px; } }
    @media (max-width: 480px) { .ts-hero { height: 180px; } }

    /* Breadcrumb */
    .breadcrumb-bar { background: #111827; min-height: 64px; display: flex; align-items: center; }
    .ts-crumb a { color: #9ca3af; }
    .ts-crumb a:hover { color: #ffffff; }
    .ts-crumb .crumb-current { color: var(--ts-green); font-weight: 600; }
    @media (max-width: 767px) { .ts-crumb { font-size: 0.8rem; } }

    /* Judul bagian */
    .section-eyebrow {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
        color: var(--ts-green); margin-bottom: 0.75rem;
    }
    .section-eyebrow::before { content: ''; width: 24px; height: 2px; background: var(--ts-green); border-radius: 9999px; }

    .section-title { font-size: 1.5rem; font-weight: 800; line-height: 1.15; color: var(--ts-ink); letter-spacing: -0.01em; }
    @media (min-width: 640px) { .section-title { font-size: 1.875rem; } }
    @media (min-width: 1024px) { .section-title { font-size: 2.25rem; } }

    .section-lead { margin-top: 0.75rem; font-size: 0.95rem; line-height: 1.6; color: var(--ts-muted); max-width: 60ch; }
    @media (min-width: 640px) { .section-lead { font-size: 1.05rem; } }

    /* Tombol filter (tab) — pill modern */
    .ts-tabs { display: inline-flex; flex-wrap: wrap; align-items: center; justify-content: flex-start; gap: 0.5rem; padding: 0.375rem; background: var(--ts-bg-soft); border: 1px solid var(--ts-border); border-radius: 9999px; max-width: 100%; }
    .ts-btn {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto;
        padding: 0.5rem 1rem; border-radius: 9999px;
        font-size: 0.82rem; font-weight: 600; line-height: 1.2; white-space: nowrap;
        background: transparent; color: #4b5563; border: none; cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    }
    .ts-btn:hover { color: var(--ts-ink); }
    .ts-btn.is-active {
        background: var(--ts-green); color: #ffffff;
        box-shadow: 0 4px 12px -2px rgba(99, 205, 0, 0.45);
    }
    @media (max-width: 599px) {
        .ts-tabs { display: flex; flex-wrap: wrap; border-radius: 1rem; }
        .ts-btn { flex: 1 1 calc(50% - 0.5rem); padding: 0.55rem 0.5rem; }
    }

    /* Grid kartu guru */
    .teachers-grid { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.25rem; }
    @media (min-width: 480px) { .teachers-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 900px) { .teachers-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (min-width: 1180px) { .teachers-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }

    /* Kartu guru — clean modern */
    .teacher-card {
        display: flex; flex-direction: column; height: 100%; min-width: 0;
        background: #ffffff; border: 1px solid var(--ts-border); border-radius: 1rem;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .teacher-card:hover, .teacher-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px -14px rgba(16, 24, 40, 0.16);
        border-color: #d6d9de;
    }
    .teacher-card:focus-visible { outline: 2px solid var(--ts-green); outline-offset: 2px; }

    /* Foto guru: selalu 1:1 (square) */
    .teacher-card__photo { position: relative; width: 100%; aspect-ratio: 1 / 1; background: linear-gradient(145deg, #f3f4f6, #e9edf1); overflow: hidden; }
    .teacher-card__photo img {
        width: 100%; height: 100%; object-fit: cover; object-position: top center;
        transition: transform 0.45s ease;
    }
    .teacher-card:hover .teacher-card__photo img,
    .teacher-card:focus-visible .teacher-card__photo img { transform: scale(1.05); }

    .teacher-card__hint {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--ts-green); font-weight: 700; font-size: 0.78rem;
        transition: gap 0.2s ease; white-space: nowrap;
    }
    .teacher-card:hover .teacher-card__hint { gap: 0.65rem; }

    .text-theme-green { color: var(--ts-green); }

    /* Empty state */
    .empty-state {
        grid-column: 1 / -1;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center; gap: 0.75rem; padding: 3.5rem 1.5rem;
        border: 1.5px dashed #d1d5db; border-radius: 1rem; background: var(--ts-bg-soft);
    }

    /* Detail guru */
    .teacher-detail { display: flex; flex-direction: column; gap: 2rem; }
    @media (min-width: 1024px) { .teacher-detail { flex-direction: row; gap: 3rem; align-items: flex-start; } }

    .teacher-detail__aside { width: 100%; max-width: 26rem; margin-left: auto; margin-right: auto; flex-shrink: 0; }
    @media (min-width: 1024px) { .teacher-detail__aside { width: 20rem; max-width: 20rem; margin: 0; position: sticky; top: 2rem; } }

    .teacher-detail__main { flex: 1 1 auto; min-width: 0; }

    .teacher-photo-frame {
        position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden;
        background: linear-gradient(145deg, #f3f4f6, #e9edf1);
        border: 1px solid var(--ts-border); border-radius: 1.25rem;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }
    .teacher-photo-frame img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }

    .ts-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 0.85rem; border-radius: 9999px;
        font-size: 0.78rem; font-weight: 600; line-height: 1.3;
        background: #ffffff; color: #475569; border: 1px solid var(--ts-border);
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
    }
    .ts-badge--green { background: var(--ts-green-soft); color: #3f8600; border-color: rgba(99, 205, 0, 0.3); }

    .ts-info-row { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 0.75rem; }
    @media (min-width: 640px) { .ts-info-row { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .ts-info-row > div {
        padding: 1rem 1.1rem; background: var(--ts-bg-soft); border: 1px solid #eef0f3; border-radius: 0.9rem;
    }
</style>
