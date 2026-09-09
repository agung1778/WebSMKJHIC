{{-- ============================================================== --}}
{{-- TRAFFIC WEBSITE TRACKING                                  --}}
{{-- Pelacakan pengunjung & klik (link/button) secara ringan.  --}}
{{-- Menggunakan sendBeacon agar non-blocking & tetap berjalan --}}
{{-- saat user berpindah halaman. Tidak butuh library tambahan --}}
{{-- ============================================================== --}}
<script>
    (function () {
        if (window.__smkTrafficTrackingLoaded) return;
        window.__smkTrafficTrackingLoaded = true;

        var ENDPOINTS = {
            page: @json(route('traffic.track.page')),
            click: @json(route('traffic.track.click'))
        };

        var TOKEN_KEY = 'smk_traffic_visitor_token';

        function getVisitorToken() {
            try {
                var token = window.localStorage.getItem(TOKEN_KEY);
                if (!token) {
                    token = 'v-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 12);
                    window.localStorage.setItem(TOKEN_KEY, token);
                }
                return token;
            } catch (e) {
                return 'v-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 12);
            }
        }

        function isTrackable() {
            var path = window.location.pathname || '';
            var blocked = ['/admin', '/developer', '/track/traffic'];
            return !blocked.some(function (prefix) {
                return path.indexOf(prefix) === 0;
            });
        }

        function send(endpoint, payload) {
            try {
                if (navigator.sendBeacon) {
                    var form = new FormData();
                    Object.keys(payload).forEach(function (key) {
                        form.append(key, payload[key]);
                    });
                    navigator.sendBeacon(endpoint, form);
                    return;
                }
            } catch (e) { /* fallback ke fetch */ }

            try {
                fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    keepalive: true,
                    body: JSON.stringify(payload)
                }).catch(function () { /* diam, jangan ganggu UX */ });
            } catch (e) { /* diam */ }
        }

        function elementName(el) {
            var name = el.getAttribute('data-traffic-name')
                || el.getAttribute('aria-label')
                || el.getAttribute('title');

            if (!name || !name.trim()) {
                name = el.textContent || '';
            }

            return name.trim().slice(0, 500) || (el.tagName === 'A' ? '(Link)' : '(Button)');
        }

        // --- Klik pada link & button -------------------------------------
        document.addEventListener('click', function (e) {
            if (!isTrackable()) return;

            var el = e.target && e.target.closest ? e.target.closest('a[href], button') : null;
            if (!el) return;

            var isLink = el.tagName.toLowerCase() === 'a';

            send(ENDPOINTS.click, {
                visitor_token: getVisitorToken(),
                click_type: isLink ? 'link' : 'button',
                element_name: elementName(el),
                element_url: isLink ? (el.href || '') : '',
                page_url: window.location.href,
                referrer: document.referrer
            });
        }, true);

        // --- Kunjungan halaman (sekali per load) --------------------------
        function trackPageView() {
            if (!isTrackable()) return;
            send(ENDPOINTS.page, {
                visitor_token: getVisitorToken(),
                page_url: window.location.href,
                referrer: document.referrer
            });
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(trackPageView, 0);
        } else {
            window.addEventListener('load', trackPageView);
        }
    })();
</script>