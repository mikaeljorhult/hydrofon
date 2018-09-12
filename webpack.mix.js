let mix = require('laravel-mix');

let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

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

mix.js('resources/js/app.js', 'public/js')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.js')],
    })
    .combine([
        'node_modules/vue/dist/vue.min.js',
        'node_modules/interactjs/dist/interact.min.js',
        'node_modules/segel/dist/index.js'
    ], 'public/js/vendor.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css')
    .purgeCss()
    .version();