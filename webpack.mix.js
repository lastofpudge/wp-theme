let mix = require("laravel-mix");

mix.setPublicPath("resources");
mix.disableNotifications();

mix
  .sass("resources/assets/src/styles/bundle.scss", "resources/assets/dist/css/bundle.min.css")
  .options({
    processCssUrls: false,
    cssNano: { minifyFontValues: false },
  })
  .js("resources/assets/src/scripts/wp_main.js", "resources/assets/dist/js/wp_main.min.js");
