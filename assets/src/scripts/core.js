window.$ = window.jQuery = require('jquery');

require('magnific-popup');
// require('parsleyjs');
// require('slick-carousel');
require('./mmenu.js');
window.Noty = require('noty');

// window.chosen = require('chosen-js');
// window.AOS = require('aos');


$(function() {
    //popup
  $('.js-mfi').each(function(index, el) {
    var _el = $(this);
    _el.magnificPopup({
      removalDelay: 500,
      showCloseBtn: false,
      callbacks: {
        beforeOpen: function() {
          var effect = this.st.el.attr('data-effect');
          if (effect) this.st.mainClass = this.st.el.attr('data-effect');
          else this.st.mainClass = 'mfp-with-fade';
        }
      },
      items: {
        src: $(this).data('url'),
        type: 'inline'
      },
      midClick: true
    });
  });

  //image
  $('.js-mfi-img').magnificPopup({
    type: 'image',
    gallery: {
      enabled: true,
      navigateByImgClick: true,
      preload: [0, 1],
      tCounter: '%curr% из %total%',
    },
    image: {
      tError: '<a href="%url%">Картинка #%curr%</a> не может быть загружена.',
      verticalFit: true,
      titleSrc: function(item) {
        return item.el.attr('title');
      }
    },
    mainClass: 'mfp-no-margins mfp-with-zoom'
  });

  //image
  $('.js-mfi-vid').magnificPopup({
    type: 'iframe',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    mainClass: 'mfp-no-margins mfp-with-zoom'
  });

});
