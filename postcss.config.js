export default {
    plugins: {
        '@tailwindcss/postcss': {},
        autoprefixer: {
            grid: 'autoplace',
            flexbox: true,
        },
        cssnano: {
            preset: 'default',
            discardComments: { removeAll: true },
            normalizeWhitespace: true,
            minifyFontValues: { removeQuotes: false },
        },
    },
};