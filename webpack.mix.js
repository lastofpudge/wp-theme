let mix = require("laravel-mix");

mix.setPublicPath("resources/assets/dist");
mix.disableNotifications();

mix
  .sass("resources/assets/src/styles/bundle.scss", "resources/assets/dist/css/bundle.min.css")
  .options({
    processCssUrls: false,
    cssNano: { minifyFontValues: false },
  })
  .js("resources/assets/src/scripts/app.js", "resources/assets/dist/js/app.min.js");
