$(function() {
    $(document).on('submit', '#contactForm', function(event) {
        event.preventDefault();
        var form = $(this);
        var form_data = new FormData();
        // form_data.append( "file", $('#file')[0].files[0]);

        form_data.append( "user_name", $(this).find('input[name="name"]').val());
        form_data.append( "user_mail", $(this).find('input[name="mail"]').val());
        form_data.append( "nonce", vars.nonce);

        $.ajax({
            type: "POST",
            url: vars.ajax_main,
            processData: false,
            contentType: false,
            data: form_data,
            success: function(data, text) {
                if (data) {
                    // if is modal form - close modal before show message
                    // $.magnificPopup.close();
                    new Noty({
                        theme: 'mint',
                        text: data.message,
                        timeout: 5000,
                        progressBar: false,
                        closeWith: ['click', 'button'],
                    }).show();
                }
            },
            fail: function(errors) {
                new Noty({
                    theme: 'mint',
                    text: 'Ошибка отправки',
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
