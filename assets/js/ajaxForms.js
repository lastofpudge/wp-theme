    jQuery(document).ready(function($) {

        $('.contactForm').on('submit', function(event) {
            event.preventDefault();
            var user_name = $(this).find('input[name="name"]').val();
            var user_mail = $(this).find('input[name="mail"]').val();

            $.ajax({
                type: "POST",
                data: {
                    action: "contactMail",
                    nonce: vars.nonce,
                    user_name: user_name,
                    user_mail: user_mail,
                },
                url: vars.ajax_url,
                success: function(data, text) {
                    if (data.type == 'success') {
                        new Noty({
                            text: data.message,
                            timeout: 5000,
                            progressBar: false,
                            closeWith: ['click', 'button'],
                        }).show();
                    }
                },
                fail: function(errors) {
                    console.log('fail');
                }
            });

            document.getElementById("contactForm").reset();
        });

    });
