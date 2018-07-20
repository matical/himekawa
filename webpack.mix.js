let mix = require('laravel-mix');

mix.copy('resources/assets/images/*.png', 'public/images')
   .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .options({
       postCss: [
           require('postcss-custom-properties')
       ]
   });


mix.disableNotifications();

if (mix.inProduction()) {
    mix.version();
}

if (! mix.inProduction()) {
    mix.sourceMaps();
}

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ]
    }
});

mix.browserSync({
    proxy: {
        target: 'localhost:8000',
        reqHeaders() {
            return {
                host: 'localhost:3000'
            };
        }
    },
    notify: false,
    open: false,
    online: false,
    ui: false
});
