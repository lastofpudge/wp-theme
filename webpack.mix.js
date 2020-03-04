let mix = require('laravel-mix');

mix.setPublicPath('assets');

// mix.webpackConfig({
//     devtool: "inline-source-map"
// });

// mix.browserSync('http://local.dev/');

mix.sass(
    'assets/src/styles/bundle.scss',
    'assets/dist/css/').options({
        processCssUrls: false,
        cssNano: { minifyFontValues: false }
}).js('assets/src/scripts/bundle.js', 'assets/dist/js/bundle.js');

