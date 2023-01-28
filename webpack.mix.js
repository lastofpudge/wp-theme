let mix = require("laravel-mix");

mix.setPublicPath("assets");
mix.disableNotifications();

mix
  .sass("assets/src/styles/bundle.scss", "assets/dist/css/bundle.min.css")
  .options({
    processCssUrls: false,
    cssNano: { minifyFontValues: false },
  })
  .js("assets/src/scripts/wp_main.js", "assets/dist/js/wp_main.min.js");
