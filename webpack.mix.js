const { mix } = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.styles([
        'node_modules/materialize-css/dist/css/materialize.css',
        'public/css/style.css',
        // 'resources/assets/css/select2.min.css',
        'resources/assets/css/select2-materialize.css',
    ], 'public/css/all.css')
    .js([
        'node_modules/materialize-css/dist/js/materialize.js',
        'resources/assets/js/app.js',
        'resources/assets/js/select2.min.js',
    ], 'public/js/all.js');