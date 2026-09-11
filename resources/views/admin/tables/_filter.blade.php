{{-- Filter baris tabel tanpa Alpine (render server-side) --}}
@push('scripts')
    <script>
        (function () {
            function initFilter(root) {
                var input = root.querySelector('input[data-filter-input]');
                var cat = root.querySelector('select[data-filter-cat]');
                var rows = Array.prototype.slice.call(root.querySelectorAll('tbody tr[data-row]'));
                if (!input || !rows.length) return;

                function filter() {
                    var q = (input.value || '').toLowerCase().trim();
                    var c = cat ? cat.value || '' : '';
                    var visible = 0;
                    rows.forEach(function (row) {
                        if (row.hasAttribute('data-empty')) {
                            row.style.display = visible ? 'none' : '';
                            return;
                        }
                        var okQ = q === '' || (row.textContent || '').toLowerCase().indexOf(q) !== -1;
                        var okC = !c || row.getAttribute('data-cat') === c;
                        var show = okQ && okC;
                        row.style.display = show ? '' : 'none';
                        if (show) visible++;
                    });
                    rows.forEach(function (row) {
                        if (row.hasAttribute('data-empty')) row.style.display = visible ? 'none' : '';
                    });
                }

                input.addEventListener('input', filter);
                if (cat) cat.addEventListener('change', filter);
            }

            document.querySelectorAll('[data-filter-root]').forEach(initFilter);
        })();
    </script>
@endpush