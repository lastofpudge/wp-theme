<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

return dd('test1');

$name = sanitize_text_field($_POST['name'] ?? '');
$mail = sanitize_text_field($_POST['mail'] ?? '');

if (empty($name) || empty($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Вы не заполнили все обязательные поля']);
}

wp_send_json([
    'type' => send_mail_cst('testMail', [
        'subject'    => 'subject',
        'site_name'  =>  get_bloginfo('name'),
        'name'       => $name,
        'mail'       => $mail
    ])
 ]);
