/* ============================================================
   SMK Amaliah 1 & 2 — Admin App JS v2
   Vanilla JS. No build step. Uses SweetAlert2 when available.
   ============================================================ */
(function () {
    'use strict';

    var html = document.documentElement;

    /* ---------- Theme ---------- */
    (function initTheme() {
        var saved = null;
        try { saved = localStorage.getItem('smk-admin-theme'); } catch (e) {}
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) html.classList.add('dark');
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

        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 900 && document.body.classList.contains('sidebar-open')) {
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
                var open = menu && menu.classList.contains('dropdown-menu') && menu.style.display !== 'none';
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
                el.style.transform = 'translateY(12px)';
                setTimeout(function () { el.remove(); }, 320);
            }, 4200);
        }
    };
    window.AppToast = function (msg, type) { Toast.push(msg, type); };

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

    /* ---------- Confirm (SweetAlert2 if available) ---------- */
    var Confirm = {
        open: function (opts) {
            opts = opts || {};
            var danger = !!opts.danger;
            if (window.Swal && typeof Swal.fire === 'function') {
                Swal.fire({
                    title: opts.title || 'Konfirmasi',
                    text: opts.message || 'Apakah Anda yakin?',
                    icon: danger ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonText: opts.confirmText || (danger ? 'Ya, hapus' : 'Ya, lanjutkan'),
                    cancelButtonText: 'Batal',
                    confirmButtonColor: danger ? '#E11D48' : '#63CD00',
                    cancelButtonColor: '#D5DBE4',
                    buttonsStyling: true,
                    customClass: {
                        title: 'sw-title',
                        popup: 'sw-pop',
                        confirmButton: 'app-btn sw-btn',
                        cancelButton: 'app-btn sw-btn-cancel'
                    }
                }).then(function (result) {
                    if (result.isConfirmed && opts.onConfirm) opts.onConfirm();
                });
                return;
            }
            var overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            overlay.innerHTML =
                '<div class="modal-card" role="dialog" aria-modal="true">' +
                '  <div class="modal-head">' +
                '    <div class="modal-icon ' + (danger ? 'danger' : 'info') + '"><i class="fas ' + (danger ? 'fa-triangle-exclamation' : 'fa-circle-question') + '"></i></div>' +
                '    <div>' +
                '      <div class="modal-title">' + (opts.title || 'Konfirmasi') + '</div>' +
                '      <div class="modal-desc">' + (opts.message || 'Apakah Anda yakin?') + '</div>' +
                '    </div>' +
                '  </div>' +
                '  <div class="modal-foot">' +
                '    <button class="app-btn" data-cm-cancel>Batal</button>' +
                '    <button class="app-btn ' + (danger ? 'app-btn-danger' : 'app-btn-primary') + '" data-cm-ok>' + (opts.confirmText || (danger ? 'Ya, hapus' : 'Ya, lanjutkan')) + '</button>' +
                '  </div>' +
                '</div>';

            overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
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
            document.body.appendChild(overlay);
            setTimeout(function () { overlay.querySelector('[data-cm-ok]').focus(); }, 30);
        }
    };
    window.AppConfirm = function (opts) { Confirm.open(opts); };

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
            Confirm.open({
                title: title,
                message: msg,
                danger: danger,
                confirmText: danger ? 'Ya, hapus' : 'Ya, lanjutkan',
                onConfirm: function () { form.submit(); }
            });
        });
    })();

    // Buttons with data-confirm
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();
        var danger = btn.hasAttribute('data-confirm-danger');
        Confirm.open({
            title: btn.getAttribute('data-confirm-title') || 'Konfirmasi',
            message: btn.getAttribute('data-confirm-message') || 'Apakah Anda yakin ingin melanjutkan?',
            danger: danger,
            confirmText: btn.getAttribute('data-confirm-text') || (danger ? 'Ya, hapus' : 'Ya, lanjutkan'),
            onConfirm: function () {
                if (btn.tagName === 'A' && btn.href) { window.location.href = btn.href; }
                else if (btn.form) { btn.form.submit(); }
            }
        });
    });

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
                '    <span style="font-size:11px;color:var(--text-3);border:1px solid var(--border);border-radius:7px;padding:2px 8px">ESC</span>' +
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

    /* ---------- Page fade-up ---------- */
    (function initFadeUp() {
        window.addEventListener('load', function () {
            document.querySelectorAll('.fade-up').forEach(function (el, i) {
                setTimeout(function () { el.classList.add('visible'); }, 50 * i);
            });
        });
    })();

    /* ---------- Table realtime search ---------- */
    document.addEventListener('input', function (e) {
        var target = e.target;
        if (!target.hasAttribute('data-table-search')) return;
        var tableId = target.getAttribute('data-table-search');
        var query = target.value.toLowerCase().trim();
        var body = document.querySelector('#' + tableId + ' tbody');
        if (!body) return;
        var noResult = document.getElementById(tableId + '-no-results');
        var rows = body.querySelectorAll('tr[data-searchable]');
        var visible = 0;
        rows.forEach(function (row) {
            var text = (row.getAttribute('data-searchable') || '').toLowerCase();
            var show = !query || text.includes(query);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noResult) noResult.style.display = visible === 0 ? '' : 'none';
    });

    /* ---------- Dropzone (image preview + filename) ---------- */
    document.addEventListener('change', function (e) {
        var input = e.target;
        if (!input.hasAttribute('data-preview')) return;
        var file = input.files && input.files[0];
        if (!file) return;
        var box = document.getElementById(input.getAttribute('data-preview'));
        if (!box) return;
        var img = box.querySelector('[data-role="img"]');
        if (img && file.type.indexOf('image') === 0) {
            var reader = new FileReader();
            reader.onload = function (ev) { img.src = ev.target.result; };
            reader.readAsDataURL(file);
            box.classList.add('visible');
        }
        var label = document.querySelector('[data-file-label="' + input.id + '"]');
        if (label) label.textContent = file.name;
    });

    /* ---------- Drag & drop for dropzones ---------- */
    document.querySelectorAll('.dropzone').forEach(function (dz) {
        var input = dz.querySelector('input[type="file"]');
        dz.addEventListener('dragover', function (e) { e.preventDefault(); dz.classList.add('dragover'); });
        dz.addEventListener('dragleave', function () { dz.classList.remove('dragover'); });
        dz.addEventListener('drop', function (e) {
            e.preventDefault();
            dz.classList.remove('dragover');
            if (input && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });

    /* ============================================================
       Generic table controller (Alpine.data('tableIndex'))
       Dipakai semua halaman index CRUD. Config contoh:
       {
         raw: [...],            // {id, status?, name, sub, by, ts, img?}
         statuses: {...},       // map status -> {label, class}
         statusKey: 'status',
         searchKeys: ['name','sub','by'],
         perPage: 8,
         bulkUrl: '',           // URL POST bulk (kalau ada)
         exportUrl: '',         // URL download export
         useTime: false,
       }
       ============================================================ */
    document.addEventListener('alpine:init', function () {
        (function (X) {
            X.tableIndex = function (config) {
            config = config || {};
            return {
                raw: config.raw || [],
                q: '',
                statusFilter: 'all',
                sortBy: 'newest',
                timeFilter: 'all',
                perPage: config.perPage || 8,
                page: 1,
                loading: false,
                selected: {},
                csrf: config.csrf || (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
                statusKey: config.statusKey || 'status',
                statuses: config.statuses || {},
                searchKeys: config.searchKeys || ['name', 'sub', 'by'],
                bulkUrl: config.bulkUrl || '',
                exportUrl: config.exportUrl || '',
                useTime: !!config.useTime,
                emptyHead: config.emptyHead || 'Belum ada data',
                emptyBody: config.emptyBody || 'Mulai tambahkan data pertama untuk ditampilkan.',

                get filtered() {
                    let list = this.raw.slice();
                    const q = this.q.toLowerCase().trim();
                    if (q) {
                        list = list.filter(function (i) {
                            return this.searchKeys.some(function (k) { return String(i[k] || '').toLowerCase().includes(q); });
                        }.bind(this));
                    }
                    if (this.statusFilter !== 'all') list = list.filter(function (i) { return i[this.statusKey] === this.statusFilter; }.bind(this));
                    if (this.useTime && this.timeFilter !== 'all') {
                        const now = Date.now() / 1000;
                        const cut = this.timeFilter === '7d' ? 7 : this.timeFilter === '30d' ? 30 : 365;
                        list = list.filter(function (i) { return (now - i.ts) <= cut * 86400; }.bind(this));
                    }
                    list.sort(function (a, b) {
                        const av = a.ts || 0, bv = b.ts || 0;
                        return bv - av;
                    });
                    if (this.sortBy === 'oldest') list.reverse();
                    if (this.sortBy === 'az' || this.sortBy === 'za') {
                        list.sort(function (a, b) {
                            const av = String(a.name || '').toLowerCase(), bv = String(b.name || '').toLowerCase();
                            return this.sortBy === 'az' ? av.localeCompare(bv) : bv.localeCompare(av);
                        }.bind(this));
                    }
                    return list;
                },
                get totalPages() { return Math.max(1, Math.ceil(this.filtered.length / this.perPage)); },
                get paged() {
                    const start = (this.page - 1) * this.perPage;
                    return this.filtered.slice(start, start + this.perPage);
                },
                get empty() { return this.filtered.length === 0; },
                get emptyTitle() { return this.q || this.statusFilter !== 'all' ? 'Tidak ada hasil' : this.emptyHead; },
                get emptyText() {
                    return this.q || this.statusFilter !== 'all' ? 'Coba ubah kata kunci atau filter pencarian Anda.' : this.emptyBody;
                },
                get rangeStart() { return this.filtered.length === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
                get rangeEnd() { return Math.min(this.page * this.perPage, this.filtered.length); },
                get pageList() {
                    const t = this.totalPages, c = this.page, set = new Set([1, t, c, c - 1, c + 1]);
                    return Array.from(set).filter(function (n) { return n >= 1 && n <= t; }).sort(function (a, b) { return a - b; });
                },
                get hasStatusFilter() { return Object.keys(this.statuses).length > 0; },

                applyFilter() { this.page = 1; this.render(); },
                goPage(n) { this.page = Math.min(Math.max(1, n), this.totalPages); this.render(); this.scrollTop(); },
                scrollTop() { const el = document.querySelector('.app-content'); if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' }); },
                render() { this.$nextTick(function () { document.querySelectorAll('.dropdown-menu').forEach(function (m) { m.style.display = 'none'; }); }); },
                refresh() {
                    this.loading = true;
                    const self = this;
                    setTimeout(function () { self.loading = false; self.page = 1; self.applyFilter(); }, 450);
                },

                badgeClass(s) {
                    const map = { published: 'badge-published', draft: 'badge-draft', archived: 'badge-archived', active: 'badge-published', inactive: 'badge-archived', Buka: 'badge-published', Tutup: 'badge-archived', Wajib: 'badge-info', Pilihan: 'badge-warning', Individual: 'badge-info', Institutional: 'badge-published' };
                    const c = this.statuses[s] || {};
                    return c.class || map[s] || 'badge-archived';
                },
                statusLabel(s) {
                    const c = this.statuses[s] || {};
                    return c.label || s;
                },

                toggleSelect(id, checked) { this.selected[id] = !!checked; },
                selCount() { return Object.keys(this.selected).filter(function (k) { return this.selected[k]; }.bind(this)).length; },
                selectedIds() { return Object.keys(this.selected).filter(function (k) { return this.selected[k]; }.bind(this)); },
                toggleAll(checked) {
                    const cur = {};
                    if (checked) this.paged.forEach(function (p) { cur[p.id] = true; });
                    this.selected = cur;
                },
                isAllSelected() { return this.paged.length > 0 && this.paged.every(function (p) { return this.selected[p.id] === true; }.bind(this)); },
                clearSelection() { this.selected = {}; },

                submitForm(method, url, params) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    let html = '<input type="hidden" name="_token" value="' + this.csrf + '">';
                    if (method !== 'POST') html += '<input type="hidden" name="_method" value="' + method + '">';
                    Object.keys(params || {}).forEach(function (k) {
                        const v = params[k];
                        if (Array.isArray(v)) v.forEach(function (x) { html += '<input type="hidden" name="' + k + '[]" value="' + x + '">'; });
                        else html += '<input type="hidden" name="' + k + '" value="' + v + '">';
                    });
                    form.innerHTML = html;
                    document.body.appendChild(form);
                    form.submit();
                },
                askStatus(item, url, status, label, extra) {
                    const self = this;
                    const msg = extra || 'Tindakan ini akan memperbarui status item.';
                    AppConfirm({
                        title: 'Konfirmasi',
                        message: 'Yakin ingin ' + label + ' <b>' + (item.name || item.title || '') + '</b>? ' + msg,
                        confirmText: 'Ya, ' + label,
                        onConfirm: function () { self.submitForm('POST', url, status ? { status: status } : {}); }
                    });
                },
                askDelete(item, url) {
                    const self = this;
                    AppConfirm({
                        title: 'Hapus Data',
                        message: 'Hapus <b>' + (item.name || item.title || '') + '</b>? Tindakan ini tidak dapat dibatalkan.',
                        danger: true,
                        confirmText: 'Ya, hapus',
                        onConfirm: function () { self.submitForm('DELETE', url); }
                    });
                },
                bulk(action) {
                    const ids = this.selectedIds().map(Number);
                    if (ids.length === 0 || !this.bulkUrl) return;
                    const label = action === 'publish' ? 'menerbitkan' : action === 'archive' ? 'mengarsipkan' : 'menghapus';
                    const danger = action === 'delete';
                    AppConfirm({
                        title: danger ? 'Hapus Massal' : 'Konfirmasi Aksi Massal',
                        message: '<b>' + ids.length + '</b> data akan di-' + label + '. ' + (danger ? 'Tindakan ini tidak dapat dibatalkan.' : ''),
                        danger: danger,
                        confirmText: danger ? 'Ya, hapus' : 'Ya, lanjutkan',
                        onConfirm: function () { self.submitForm('POST', self.bulkUrl, { action: action, ids: ids }); }
                    });
                },
                doExport() {
                    if (!this.exportUrl) return;
                    const ids = this.selectedIds();
                    const url = ids.length ? this.exportUrl + (this.exportUrl.indexOf('?') > -1 ? '&' : '?') + 'ids=' + ids.join(',') : this.exportUrl;
                    window.location.href = url;
                    if (ids.length) AppToast('Export ' + ids.length + ' data berhasil', 'info');
                }
            };
            };
            X.instaIndex = function (config) {
                return Object.assign(X.tableIndex(config || {}), {
                    editOpen: false,
                    editItem: { id: null, img: '', caption: '', url: '', status: 'inactive' },
                    editActive: 0,
                    openEdit(p) {
                        this.editItem = {
                            id: p.id,
                            img: p.img,
                            caption: p.caption || '',
                            url: p.url || '',
                            status: p.status
                        };
                        this.editActive = p.status === 'active' ? 1 : 0;
                        this.editOpen = true;
                    },
                    toggleActive(p) {
                        const self = this;
                        const hide = p.status === 'active';
                        AppConfirm({
                            title: 'Konfirmasi',
                            message: 'Yakin ingin ' + (hide ? 'menyembunyikan' : 'menampilkan') + ' postingan <b>' + (p.caption || 'ini') + '</b>?',
                            confirmText: 'Ya, lanjutkan',
                            onConfirm: function () { self.submitForm('POST', '/admin/insta-posts/' + p.id + '/toggle', {}); }
                        });
                    }
                });
            };
        })(window.__tableIndex = window.__tableIndex || {});
        window.__tableIndexBase = window.__tableIndex.tableIndex;
        Alpine.data('tableIndex', window.__tableIndex.tableIndex);
        Alpine.data('instaIndex', window.__tableIndex.instaIndex);
    });
})();