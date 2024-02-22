const mix = require('laravel-mix')
const path = require('path')

mix.setPublicPath('resources/assets/dist')
mix.disableNotifications()

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/assets/src/scripts')
    }
  }
})

mix
  .sass('resources/assets/src/styles/bundle.scss', 'resources/assets/dist/css/bundle.min.css')
  .options({
    processCssUrls: false,
    cssNano: { minifyFontValues: false }
  })
  .js('resources/assets/src/scripts/app.js', 'resources/assets/dist/js/app.min.js')
