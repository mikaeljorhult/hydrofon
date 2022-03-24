module.exports = {
    mode: 'jit',
    content: [
        './resources/**/*.{php,js}',
        './node_modules/flatpickr/dist/flatpickr.js'
    ],
    theme: {},
    plugins: [
        require('@tailwindcss/forms'),
    ],
    corePlugins: {
        container: false
    }
};
