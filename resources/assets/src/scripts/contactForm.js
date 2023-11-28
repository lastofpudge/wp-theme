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

export function initContactForm() {
    const preloader = $(".js-preloader-main");

    $(document).on("submit", ".js-contact-form", function (event) {
        event.preventDefault();

        const form = $(this);

        preloader.addClass("js-preloading");

        const formData = buildFormData(form);

        $.ajax({
            type: "POST",
            url: data.ajax_url,
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                preloader.removeClass("js-preloading");

                handleSuccess(response, Toast);
            },
            error: function (errors) {
                preloader.removeClass("js-preloading");

                handleError(errors, Toast);
            },
        });

        form.trigger("reset");
    });
}

function buildFormData(form) {
    const formData = new FormData();

    formData.append("name", form.find('input[name="name"]').val());
    formData.append("mail", form.find('input[name="mail"]').val());
    formData.append("action", "sendMail");
    formData.append("nonce", data.nonce);

    return formData;
}

function handleSuccess(response, Toast) {
    if (response.type === "success") {
        Toast.fire({
            icon: "success",
            iconColor: "#007cba",
            title: response.message,
        });
    } else {
        const errorMessage =
            response.sended?.errors?.wp_mail_failed[0] || response.message;

        Toast.fire({
            icon: "error",
            iconColor: "red",
            title: errorMessage,
        });
    }
}

function handleError(errors, Toast) {
    Toast.fire({
        icon: "error",
        iconColor: "red",
        title: "Error sending email",
    });
    console.log(errors);
}