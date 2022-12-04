$(function() {
    const preloader = $('.js__preloader__main');

    $(document).on('submit', '#contactForm', function(event) {
        event.preventDefault();

        const form = $(this);
        const form_data = new FormData();
        // form_data.append( "file", $('#file')[0].files[0]);

        form_data.append( "name", $(this).find('input[name="name"]').val());
        form_data.append( "mail", $(this).find('input[name="mail"]').val());
        form_data.append( "action", "test_action");
        form_data.append( "nonce", data.nonce);


        preloader.addClass('js__preloading');
        $.ajax({
            type: "POST",
            url: data.ajax_url,
            processData: false,
            contentType: false,
            data: form_data,
            success: function(data, text) {
                preloader.removeClass('js__preloading');

                // $.magnificPopup.close();
                if (data.type === 'success') {
                    new Noty({
                        theme: 'mint',
                        text: data.message,
                        timeout: 5000,
                        progressBar: false,
                        closeWith: ['click', 'button'],
                    }).show();
                }else{
                    console.warn(data);
                    new Noty({
                        theme: 'mint',
                        type: 'error',
                        text: data.sended.errors.wp_mail_failed[0],
                        timeout: 5000,
                        progressBar: false,
                        closeWith: ['click', 'button'],
                    }).show();         
                }
            },
            fail: function(errors) {
                preloader.removeClass('js__preloading');
                new Noty({
                    theme: 'mint',
                    text: 'Error send email',
                    timeout: 5000,
                    progressBar: false,
                    closeWith: ['click', 'button'],
                }).show();
                console.log(errors);
            }
        });

        form.trigger('reset');
    });

});
