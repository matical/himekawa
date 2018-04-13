let mix = require('laravel-mix');

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

mix.copy('resources/assets/images/*.png', 'public/images')
   .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');


mix.disableNotifications();

if (mix.inProduction()) {
    mix.version();
}

if (! mix.inProduction()) {
    mix.sourceMaps();
}

mix.browserSync({
    proxy: {
        target: 'localhost:8000',
        reqHeaders() {
            return {
                host: 'localhost:3000'
            };
        }
    },
    notify: false
});
