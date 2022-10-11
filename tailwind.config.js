module.exports = {
    mode: 'jit',
    content: [
        './app/States/*.{php,js}',
        './resources/**/*.{php,js}',
        './node_modules/flatpickr/dist/flatpickr.js',
    ],
    safelist: [
        {
            pattern: /text-(gray|orange|red|yellow)-600/,
        },
    ],
    theme: {},
    plugins: [
        require('@tailwindcss/forms'),
    ],
    corePlugins: {
        container: false,
    }
};
