import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                // Consistent chunk naming for caching
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(png|jpe?g|gif|svg|webp|avif|ico)$/.test(assetInfo.name)) {
                        return `images/[name]-[hash].${ext}`;
                    }
                    if (/\.(woff2?|ttf|eot)$/.test(assetInfo.name)) {
                        return `fonts/[name]-[hash].${ext}`;
                    }
                    if (/\.css$/.test(assetInfo.name)) {
                        return `css/[name]-[hash].${ext}`;
                    }
                    return `assets/[name]-[hash].${ext}`;
                },
            },
        },
        // Minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info', 'console.debug'],
            },
            format: {
                comments: false,
            },
        },
        // CSS code splitting
        cssCodeSplit: true,
        // Asset inlining threshold (inline small assets as base64)
        assetsInlineLimit: 4096, // 4kb
        // Sourcemaps for production debugging (disable for smaller build)
        sourcemap: false,
        // Target modern browsers for smaller output
        target: 'es2020',
        // Module preload polyfill
        modulePreload: {
            polyfill: true,
        },
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['alpinejs', '@splidejs/splide', 'axios'],
        exclude: [],
    },
    // CSS configuration
    css: {
        postcss: './postcss.config.js',
    },
});