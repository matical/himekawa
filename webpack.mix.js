let mix = require('laravel-mix');

mix.copy('resources/images/*.png', 'public/images')
   .js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');


mix.disableNotifications();

if (mix.inProduction()) {
    mix.version();
}

if (! mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'inline-source-map',
    }).sourceMaps();
}

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.IgnorePlugin({
                resourceRegExp: /^\.\/locale$/,
                contextRegExp: /moment$/,
            }),
        ],
    };
});

mix.browserSync({
    proxy: {
        target: 'localhost:8000',
        reqHeaders() {
            return {
                host: 'localhost:3000',
            };
        },
    },
    notify: false,
    open: false,
    online: false,
    ui: false,
});
