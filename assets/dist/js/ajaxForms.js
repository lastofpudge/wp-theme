    jQuery(document).ready(function($) {

        /**
         * send contact form
         */
            // event.preventDefault();
            // var response = grecaptcha.getResponse();
            // if(response.length == 0){
            //     new Noty({
            //         theme: 'mint',
            //         text: 'Вы не ввели капчу',
            //         timeout: 5000,
            //         progressBar: false,
            //         closeWith: ['click', 'button'],
            //     }).show();
            //     return;
            // }

            // var user_name = $(this).find('input[name="name"]').val();
            // var user_tel = $(this).find('input[name="tel"]').val();
            // var user_comment = $(this).find('[name="comment"]').val();
            // var checker = $(this).find('[name="agr"]').val();

            // $.ajax({
            //     type: "POST",
            //     data: {
            //         action: "contactMail",
            //         nonce: vars.nonce,
            //         user_name: user_name,
            //         user_tel: user_tel,
            //         user_comment: user_comment,
            //         checker: checker,
            //     },
            //     url: vars.ajax_url,
            //     success: function(data, text) {
            //         if (data) {
            //             $.magnificPopup.close();
            //             new Noty({
            //                 theme: 'mint',
            //                 text: data.message,
            //                 timeout: 5000,
            //                 progressBar: false,
            //                 closeWith: ['click', 'button'],
            //             }).show();
            //         }
            //     },
            //     fail: function(errors) {
            //             new Noty({
            //                 theme: 'mint',
            //                 text: 'Ошибка отправки',
            //                 timeout: 5000,
            //                 progressBar: false,
            //                 closeWith: ['click', 'button'],
            //             }).show();
            //             console.log(errors);
            //     }
            // });

            // document.getElementById("contactFormP").reset();

    });
