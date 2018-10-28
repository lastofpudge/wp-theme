<?php

if (isset($_FILES['user_file']) && !empty($_FILES['user_file'])) {
    // allowed mimes
    $mimes = [
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
    ];

    // userfile
    $file = $_FILES['user_file'];

    // validate file image
    $filetype = wp_check_filetype($file, $mimes);

    if (!$filetype['ext']) {
        wp_send_json([
            'type'    => 'error',
            'message' => 'Invalid file format (Only images allowed)',
        ]);
    }

    // add file to email
    $php_mailer->AddAttachment($_FILES['user_file']['tmp_name'], $_FILES['user_file']['name']);
}
