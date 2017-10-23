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
        'resources/assets/css/bootstrap/bootstrap.css',
        'resources/assets/css/select2.min.css',
        'resources/assets/css/select2-bootstrap.min.css',
        // 'resources/assets/css/dataTables.bootstrap4.css',
        'resources/assets/css/lightbox.css',
        'resources/assets/css/font-awesome.min.css',
        'resources/assets/css/sb-admin.min.css',
        'public/css/style.css',
        'node_modules/croppie/croppie.css',
    ], 'public/css/all.css')
    .js([
        'resources/assets/js/app.js',
        'resources/assets/js/jquery.cropit.js',
        'resources/assets/js/jquery.easing.js',
        'resources/assets/js/select2.min.js',
        'resources/assets/js/input-mask/jquery.inputmask.js',
        'resources/assets/js/input-mask/jquery.inputmask.extensions.js',
        'resources/assets/js/lightbox.js',
        'resources/assets/js/bootstrap-filestyle.min.js',
        'node_modules/croppie/croppie.js',
        // 'resources/assets/js/tether.min.js',
        // 'resources/assets/js/bootstrap.min.js',
        // 'resources/assets/js/popper/popper.min.js',
        // 'resources/assets/js/jquery.dataTables.js',
        // 'resources/assets/js/dataTables.bootstrap4.js',
        // 'resources/assets/js/sb-admin.js',
    ], 'public/js/all.js');