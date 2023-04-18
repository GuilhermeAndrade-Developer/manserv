const mix = require('laravel-mix');

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

mix
    .copyDirectory('resources/views/assets/vendor', 'public/assets/vendor')
    .copyDirectory('resources/views/assets/fonts', 'public/assets/fonts')
    .copyDirectory('resources/views/assets/icons', 'public/assets/icons')
    .copyDirectory('resources/views/assets/images', 'public/assets/images')

    .copyDirectory('resources/views/assets/css', 'public/assets/css')
    .copyDirectory('resources/views/assets/js', 'public/assets/js')
