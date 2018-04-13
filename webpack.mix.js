let mix = require('laravel-mix');
let glob = require('glob');

let dirs = [
    {
        source: 'resources/assets/images',
        dest: 'public/images',
    },
];
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

mix.copyDirectory('resources/assets/images', 'public/images')
   .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');


mix.disableNotifications();

if (mix.inProduction()) {
    dirs.forEach((dir) => {
        mix.copy(dir.source, dir.dest, false);
    });

    let files = [];
    dirs.forEach((dir) => {
        glob.sync('**/*', {cwd: dir.source}).forEach((file) => {
            files.push(dir.dest + '/' + file);
        });
    });

    mix.version(files);
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
