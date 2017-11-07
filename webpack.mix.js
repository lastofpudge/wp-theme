const mix = require('laravel-mix');

mix.js('assets/src/scripts/bundle.js', 'assets/dist/js')
   .sass('resources/sass/app.scss', 'public/css');
