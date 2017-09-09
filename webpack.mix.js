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
        // 'node_modules/materialize-css/dist/css/materialize.min.css',
        'resources/assets/css/bootstrap/bootstrap.css',
        'resources/assets/css/select2.min.css',
        'resources/assets/css/font-awesome.min.css',
        'resources/assets/css/sb-admin.min.css',
        'public/css/style.css',
        // 'resources/assets/css/select2-materialize.css',
        // 'resources/assets/css/datatables.min.css',
        // 'resources/assets/css/dataTables.material.min.css',
    ], 'public/css/all.css')
    .js([
        'resources/assets/js/app.js',
        'resources/assets/js/jquery.easing.js',
        'resources/assets/js/select2.min.js',
        'resources/assets/js/popper/popper.min.js',
        'resources/assets/js/input-mask/jquery.inputmask.js',
        'resources/assets/js/input-mask/jquery.inputmask.extensions.js',
        'resources/assets/js/bootstrap/bootstrap.min.js',
        'resources/assets/js/sb-admin.min.js',
        // 'node_modules/materialize-css/dist/js/materialize.min.js',
        // 'resources/assets/js/datatables.min.js',
    ], 'public/js/all.js');