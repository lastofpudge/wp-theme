let mix = require('laravel-mix');

mix.sass('assets/src/scss/app.scss', 'assets/css').options({
    processCssUrls: false
}).sourceMaps();
