<?php

    add_action('phpmailer_init', function ($phpmailer) {
        if (!is_object($phpmailer)) {
            $phpmailer = (object) $phpmailer;
        }

        $phpmailer->Mailer = 'smtp';
        // $phpmailer->Host       = 'smtp.gmail.com';
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->Port = 587;
        $phpmailer->IsHTML = true;
        $phpmailer->Username = '147f56d4f04ad3';
        $phpmailer->Password = 'd00a67c20c46bd';
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->From = get_bloginfo('admin_email');
        $phpmailer->FromName = get_bloginfo('name');
    });
