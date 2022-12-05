window.$ = window.jQuery = require("jquery");

require("magnific-popup");
window.Noty = require("noty");


$(function () {
  //popup
  $(".js-mfi").each(function () {
    const _el = $(this);
    _el.magnificPopup({
      removalDelay: 500,
      showCloseBtn: false,
      callbacks: {
        beforeOpen: function () {
          const effect = this.st.el.attr("data-effect");
          if (effect) this.st.mainClass = this.st.el.attr("data-effect");
          else this.st.mainClass = "mfp-with-fade";
        },
      },
      items: {
        src: $(this).data("url"),
        type: "inline",
      },
      midClick: true,
    });
  });

  //image
  $(".js-mfi-img").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
      navigateByImgClick: true,
      preload: [0, 1],
      tCounter: "%curr% из %total%",
    },
    image: {
      tError: '<a href="%url%">Image #%curr%</a> cannot be loaded.',
      verticalFit: true,
      titleSrc: function(item) {
        return item.el.attr("title");
      },
    },
    mainClass: "mfp-no-margins mfp-with-zoom",
  });

  //video
  $(".js-mfi-vid").magnificPopup({
    type: "iframe",
    removalDelay: 160,
    preloader: false,
    mainClass: "mfp-fade mfp-no-margins mfp-with-zoom",
  });
});
