import Toast from "./libs/Toast";

export function initContactForm() {
    const preloader = $(".js-preloader-main");
    $(document).on("submit", ".js-contact-form", async function (event) {
        event.preventDefault();

        const form = $(this);
        const formData = new FormData();

        formData.append("name", form.find('input[name="name"]').val());
        formData.append("mail", form.find('input[name="mail"]').val());
        formData.append("action", "sendMail");
        formData.append("nonce", data.nonce);
        preloader.addClass("js-preloading");

        try {
            const {data: response} = await axios.post(data.ajax_url, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                },
                params: {
                    action: "sendMail",
                    nonce: data.nonce
                },
            });

            if (response.type === 'success') {
                Toast.fire({icon: 'success', iconColor: '#007cba', title: response.message})
            } else {
                Toast.fire({icon: "error", iconColor: "red", title: "Error sending email"});
            }
        } catch (error) {
            console.log(error);
        }

        preloader.removeClass("js-preloading");
        form.trigger("reset");
    });
}