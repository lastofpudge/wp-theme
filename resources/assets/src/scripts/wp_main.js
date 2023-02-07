window.$ = window.jQuery = require("jquery");
import Swal from "sweetalert2/dist/sweetalert2.js";

const Toast = Swal.mixin({
  toast: true,
  position: "top-right",
  showClass: {
    backdrop: "swal2-noanimation",
    popup: "",
    icon: "",
  },
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
});

$(function () {
  const preloader = $(".js-preloader-main");

  $(document).on("submit", ".js-contact-form", function (event) {
    event.preventDefault();

    const form = $(this);
    const form_data = new FormData();
    // form_data.append( "file", $('#file')[0].files[0]);

    form_data.append("name", $(this).find('input[name="name"]').val());
    form_data.append("mail", $(this).find('input[name="mail"]').val());
    form_data.append("action", "sendMailAction");
    form_data.append("nonce", data.nonce);

    preloader.addClass("js-preloading");
    $.ajax({
      type: "POST",
      url: data.ajax_url,
      processData: false,
      contentType: false,
      data: form_data,
      success: function (response) {
        preloader.removeClass("js-preloading");

        if (response.type === "success") {
          Toast.fire({
            icon: "success",
            iconColor: "#007cba",
            title: response.message,
          });
        } else {
          Toast.fire({
            icon: "error",
            iconColor: "red",
            title: response.sended?.errors?.wp_mail_failed[0] || response.message,
          });
        }
      },
      fail: function (errors) {
        preloader.removeClass("js-preloading");

        Toast.fire({
          icon: "error",
          iconColor: "red",
          title: "Error send email",
        });
        console.log(errors);
      },
    });

    form.trigger("reset");
  });
});
