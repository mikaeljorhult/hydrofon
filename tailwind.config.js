module.exports = {
    mode: 'jit',
    content: [
        './resources/**/*.{php,js}',
        './node_modules/flatpickr/dist/flatpickr.js'
    ],
    theme: {
        extend: {
            fontFamily: {
                'base': ['Roboto', 'sans-serif']
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
    corePlugins: {
        container: false
    }
};
