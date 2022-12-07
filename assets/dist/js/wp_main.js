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
      success: function (data, text) {
        preloader.removeClass("js-preloading");

        // $.magnificPopup.close();
        if (data.type === "success") {
          new Noty({
            theme: "mint",
            text: data.message,
            timeout: 5000,
            progressBar: false,
            closeWith: ["click", "button"],
          }).show();
        } else {
          new Noty({
            theme: "mint",
            type: "error",
            text: data.sended?.errors?.wp_mail_failed[0] || data.message,
            timeout: 5000,
            progressBar: false,
            closeWith: ["click", "button"],
          }).show();
        }
      },
      fail: function (errors) {
        preloader.removeClass("js-preloading");
        new Noty({
          theme: "mint",
          text: "Error send email",
          timeout: 5000,
          progressBar: false,
          closeWith: ["click", "button"],
        }).show();
        console.log(errors);
      },
    });

    form.trigger("reset");
  });
});
