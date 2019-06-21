let mix = require('laravel-mix');

require('laravel-mix-purgecss');
require('laravel-mix-tailwind');
require('laravel-mix-vue-svgicon');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
        resolve: {
            alias: {
                vue: path.resolve(__dirname, 'node_modules/vue')
            }
        }
    })
    .js('resources/js/app.js', 'public/js')
    .extract()
    .copyDirectory('resources/images', 'public/images')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css')
    .svgicon('./resources/images/svg')
    .tailwind('./tailwind.config.js')
    .purgeCss({
        globs: [
            path.join(__dirname, 'node_modules/flatpickr/dist/*.js'),
            path.join(__dirname, 'node_modules/segel/dist/*.js'),
            path.join(__dirname, 'node_modules/vue-select/dist/*.js'),
        ],
    })
    .version();