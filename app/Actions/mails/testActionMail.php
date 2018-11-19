<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'Ошибка nonce']);
}

$mail_subject = 'New order';

$name = sanitize_text_field($_POST['user_name'] ?? '');
$mail = sanitize_text_field($_POST['user_mail'] ?? '');
        //validate data
if (empty($name) || empty($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Вы не заполнили все обязательные поля']);
}

// mail title
$site_name = get_bloginfo('name');
$php_mailer->Subject = $mail_subject.' - '.$site_name;

loadMail('testMail', [
    'php_mailer' => $php_mailer,
    'site_name'  => $site_name,
    'name'       => $name,
    'mail'       => $mail,
    'phone'      => '09912313',
    'message'    => 'message test',
]);

if (!$php_mailer->send()) {
    wp_send_json(['type' => 'fail', 'message' => 'Fail send email']);
}
wp_send_json(['type' => 'success', 'message' => 'TEST']);
