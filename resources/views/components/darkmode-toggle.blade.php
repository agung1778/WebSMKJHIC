<button id="theme-toggle" class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 
           bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-300">
    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block dark:hidden" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12.515h1M16.95 7.05l.707-.707M6.343 17.657l.707-.707M16.95 16.95l.707.707M6.343 6.343l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
    </svg>
    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
    </svg>
    <span class="text-sm font-medium">Mode</span>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const html = document.documentElement;
        const btn = document.getElementById('theme-toggle');

        btn.addEventListener('click', () => {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
</script>