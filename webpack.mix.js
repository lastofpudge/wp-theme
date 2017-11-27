const mix = require('laravel-mix');

mix.setPublicPath('assets');

mix.js('assets/src/scripts/bundle.js', 'assets/dist/js/bundle.js')
    .sass(
        'assets/src/styles/bundle.scss',
        'assets/dist/css/').options({
          processCssUrls: false
    });
