module.exports = {
    purge: [
        './resources/**/*.js',
        './resources/**/*.php',
        './node_modules/flatpickr/dist/flatpickr.js'
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    100: '#FFEFED',
                    200: '#FFD8D1',
                    300: '#FFC1B5',
                    400: '#FF927E',
                    500: '#FF6347',
                    600: '#E65940',
                    700: '#993B2B',
                    800: '#732D20',
                    900: '#4D1E15',
                    default: '#FF6347'
                },
                complementary: {
                    100: '#F1F2F5',
                    200: '#DDDFE5',
                    300: '#C8CBD5',
                    400: '#9EA5B6',
                    500: '#757E96',
                    600: '#697187',
                    700: '#464C5A',
                    800: '#353944',
                    900: '#23262D',
                    default: '#757E96'
                },
            },
            fontFamily: {
                'base': ['Roboto', 'sans-serif']
            }
        }
    },
    variants: {},
    plugins: [],
    corePlugins: {
        container: false
    }
};
