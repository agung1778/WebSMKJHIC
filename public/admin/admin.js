/* ============================================================
   SMK Amaliah 1 & 2 — Admin App JS
   Vanilla JS. No build step.
   ============================================================ */
(function () {
    'use strict';

    var html = document.documentElement;

    /* ---------- Theme ---------- */
    (function initTheme() {
        var saved = null;
        try { saved = localStorage.getItem('smk-admin-theme'); } catch (e) {}
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) { html.classList.add('dark'); }
        var toggles = document.querySelectorAll('[data-theme-toggle]');
        toggles.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var isDark = html.classList.toggle('dark');
                try { localStorage.setItem('smk-admin-theme', isDark ? 'dark' : 'light'); } catch (e) {}
                var iconSun = btn.querySelector('[data-theme-icon-sun]');
                var iconMoon = btn.querySelector('[data-theme-icon-moon]');
                if (iconSun) iconSun.style.display = isDark ? '' : 'none';
                if (iconMoon) iconMoon.style.display = isDark ? 'none' : '';
            });
        });
    })();

    /* ---------- Sidebar collapse (desktop) + drawer (mobile) ---------- */
    (function initSidebar() {
        try {
            var collapsed = localStorage.getItem('smk-admin-collapsed') === '1';
            if (collapsed) document.body.classList.add('sidebar-collapsed');
        } catch (e) {}

        document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                try { localStorage.setItem('smk-admin-collapsed', isCollapsed ? '1' : '0'); } catch (e) {}
            });
        });

        document.querySelectorAll('[data-sidebar-mobile-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.body.classList.toggle('sidebar-open');
            });
        });

        // Tutup drawer saat klik di luar sidebar (mobile)
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && document.body.classList.contains('sidebar-open')) {
                var sb = document.querySelector('.sidebar');
                if (sb && !sb.contains(e.target) && !e.target.closest('[data-sidebar-mobile-toggle]')) {
                    document.body.classList.remove('sidebar-open');
                }
            }
        });
    })();

    /* ---------- Sidebar submenu ---------- */
    (function initSubmenus() {
        var toggles = document.querySelectorAll('.nav-group-toggle');
        toggles.forEach(function (btn) {
            var group = btn.closest('.nav-group');
            // Buka otomatis jika ada link active di dalamnya
            if (group && group.querySelector('.nav-link.active')) group.classList.add('open');
            btn.addEventListener('click', function () {
                if (group) group.classList.toggle('open');
            });
        });
    })();

    /* ---------- Dropdown menus ---------- */
    (function initDropdowns() {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-dropdown]');
            var menu = null;
            if (btn) {
                menu = btn.nextElementSibling;
                var open = menu && menu.classList.contains('dropdown-menu');
                closeAllDropdowns();
                if (menu && !open) menu.style.display = 'block';
                e.preventDefault();
            } else {
                if (!e.target.closest('.dropdown-menu')) closeAllDropdowns();
            }
        });

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(function (m) { m.style.display = 'none'; });
        }
    })();

    /* ---------- Toasts ---------- */
    var Toast = {
        stack: null,
        push: function (message, type) {
            if (!this.stack) {
                this.stack = document.createElement('div');
                this.stack.className = 'toast-stack';
                document.body.appendChild(this.stack);
            }
            var el = document.createElement('div');
            el.className = 'toast ' + (type || 'success');
            var icon = type === 'error' ? 'fa-circle-exclamation'
                : type === 'info' ? 'fa-circle-info' : 'fa-circle-check';
            el.innerHTML = '<i class="fas ' + icon + '"></i><div>' + message + '</div>';
            this.stack.appendChild(el);
            setTimeout(function () {
                el.style.transition = 'opacity .3s ease, transform .3s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(10px)';
                setTimeout(function () { el.remove(); }, 320);
            }, 4200);
        }
    };
    window.AppToast = function (msg, type) { Toast.push(msg, type); };

    // Toast dari flash session
    (function flashToasts() {
        var flash = document.querySelector('[data-toast-flash]');
        if (flash) {
            var type = flash.getAttribute('data-toast-flash') || 'success';
            Toast.push(flash.textContent.trim(), type);
            flash.remove();
        }
        var errors = document.querySelector('[data-toast-errors]');
        if (errors) {
            try {
                var list = JSON.parse(errors.textContent);
                (list || []).forEach(function (msg) { Toast.push(msg, 'error'); });
            } catch (e) {}
            errors.remove();
        }
    })();

    /* ---------- Confirm modal ---------- */
    var Confirm = {
        open: function (opts) {
            opts = opts || {};
            var overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            overlay.innerHTML =
                '<div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="cm-title">' +
                '  <div class="modal-head">' +
                '    <div class="modal-icon ' + (opts.danger ? 'danger' : 'info') + '"><i class="fas ' + (opts.danger ? 'fa-triangle-exclamation' : 'fa-circle-question') + '"></i></div>' +
                '    <div>' +
                '      <div class="modal-title" id="cm-title">' + (opts.title || 'Konfirmasi') + '</div>' +
                '      <div class="modal-desc">' + (opts.message || 'Apakah Anda yakin?') + '</div>' +
                '    </div>' +
                '  </div>' +
                '  <div class="modal-foot">' +
                '    <button class="app-btn" data-cm-cancel>Batal</button>' +
                '    <button class="app-btn ' + (opts.danger ? 'app-btn-danger' : 'app-btn-primary') + '" data-cm-ok>' + (opts.confirmText || 'Ya, lanjutkan') + '</button>' +
                '  </div>' +
                '</div>';

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) close();
            });
            overlay.querySelector('[data-cm-cancel]').addEventListener('click', close);
            overlay.querySelector('[data-cm-ok]').addEventListener('click', function () {
                close();
                if (opts.onConfirm) opts.onConfirm();
            });
            function close() {
                overlay.remove();
                document.removeEventListener('keydown', escHandler);
            }
            function escHandler(e) { if (e.key === 'Escape') close(); }
            document.addEventListener('keydown', escHandler);
            var firstFocus = overlay.querySelector('[data-cm-ok]');
            setTimeout(function () { firstFocus.focus(); }, 30);
            document.body.appendChild(overlay);
        }
    };
    window.AppConfirm = function (opts) { Confirm.open(opts); };

    // Konfirmasi untuk form: tombol data-confirm-form="#selector",
    // atau form dengan data-confirm-form (tanpa nilai / pada form itu sendiri)
    (function confirmForms() {
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('[data-confirm-form]');
            if (!trigger) return;
            var form = null;
            var sel = trigger.getAttribute('data-confirm-form');
            if (sel && sel !== '#') {
                form = document.querySelector(sel);
            } else {
                form = trigger.closest('form') || trigger;
            }
            if (!form || !form.submit) return;
            e.preventDefault();
            var title = trigger.getAttribute('data-confirm-title') || 'Konfirmasi';
            var msg = trigger.getAttribute('data-confirm-message') || trigger.getAttribute('data-message') || 'Apakah Anda yakin ingin melanjutkan?';
            var danger = trigger.hasAttribute('data-confirm-danger') || trigger.hasAttribute('data-danger');
            AppConfirm({
                title: title,
                message: msg,
                danger: danger,
                confirmText: danger ? 'Ya, hapus' : 'Ya, lanjutkan',
                onConfirm: function () { form.submit(); }
            });
        });
    })();

    /* ---------- Command palette ---------- */
    (function initPalette() {
        var menuItems = [];
        try { menuItems = window.__palette || []; } catch (e) {}

        function build(match) {
            var q = (match || '').toLowerCase().trim();
            var flat = [];
            menuItems.forEach(function (group) {
                group.items.forEach(function (item) {
                    if (!q || item.title.toLowerCase().indexOf(q) !== -1 || (item.kw || '').toLowerCase().indexOf(q) !== -1) {
                        flat.push(Object.assign({ g: group.label }, item));
                    }
                });
            });
            return flat.slice(0, 18);
        }

        var wrap = null, current = 0, results = [];

        function closePalette() {
            if (wrap) { wrap.remove(); wrap = null; }
            document.removeEventListener('keydown', onOpenKey, true);
        }

        function escHandler(e) { if (e.key === 'Escape') closePalette(); }

        function onOpenKey(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openPalette();
            }
        }
        document.addEventListener('keydown', onOpenKey, true);

        function openPalette() {
            closePalette();
            wrap = document.createElement('div');
            wrap.className = 'palette-wrap';
            wrap.innerHTML =
                '<div class="palette" role="dialog" aria-modal="true">' +
                '  <div class="palette-input-wrap">' +
                '    <i class="fas fa-magnifying-glass" style="color:var(--text-3)"></i>' +
                '    <input class="palette-input" data-pal-input type="text" placeholder="Cari menu, halaman, atau aksi..." autocomplete="off">' +
                '    <span style="font-size:11px;color:var(--text-3);border:1px solid var(--border);border-radius:6px;padding:2px 7px">ESC</span>' +
                '  </div>' +
                '  <div class="palette-results" data-pal-results></div>' +
                '</div>';

            var input = wrap.querySelector('[data-pal-input]');
            var resultsBox = wrap.querySelector('[data-pal-results]');

            function render(q) {
                results = build(q);
                current = 0;
                if (!results.length) {
                    resultsBox.innerHTML = '<div class="palette-empty">Tidak ada hasil untuk <b>' + (q || '') + '</b></div>';
                    return;
                }
                var lastGroup = null, htmlStr = '';
                results.forEach(function (item, i) {
                    if (item.g !== lastGroup) {
                        htmlStr += '<div class="palette-group-label">' + item.g + '</div>';
                        lastGroup = item.g;
                    }
                    htmlStr += '<button class="palette-item" data-idx="' + i + '">' +
                        '  <i class="' + item.icon + '"></i><span>' + item.title + '</span>' +
                        '  <span class="kbd">&#8629;</span>' +
                        '</button>';
                });
                resultsBox.innerHTML = htmlStr;
                resultsBox.querySelectorAll('.palette-item').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var item = results[parseInt(btn.getAttribute('data-idx'), 10)];
                        closePalette();
                        go(item);
                    });
                });
                highlight();
            }

            function highlight() {
                resultsBox.querySelectorAll('.palette-item').forEach(function (b, i) {
                    b.classList.toggle('highlight', i === current);
                    if (i === current) b.scrollIntoView({ block: 'nearest' });
                });
            }

            function go(item) {
                if (!item) return;
                if (item.url) { window.location.href = item.url; }
                else if (item.action) { item.action(); }
            }

            input.addEventListener('input', function () { render(input.value); });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowDown') { e.preventDefault(); current = (current + 1) % results.length; highlight(); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); current = (current - 1 + results.length) % results.length; highlight(); }
                else if (e.key === 'Enter') { e.preventDefault(); go(results[current]); }
                else if (e.key === 'Escape') { closePalette(); }
            });

            wrap.addEventListener('click', function (e) { if (e.target === wrap) closePalette(); });
            document.addEventListener('keydown', escHandler);

            document.body.appendChild(wrap);
            setTimeout(function () { input.focus(); render(''); }, 30);
        }

        document.querySelectorAll('[data-open-palette]').forEach(function (b) {
            b.addEventListener('click', openPalette);
        });
    })();
})();