<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$name = sanitize_text_field($_POST['name'] ?? '');
$mail = sanitize_text_field($_POST['mail'] ?? '');

if (empty($name) || empty($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Please fill in all required fields']);
}

$isMailSent = send_custom_mail('test', [
    'subject' => 'test form',
    'site_name' => get_bloginfo('name'),
    'name' => $name,
    'mail' => $mail,
]);

if ($isMailSent) {
    wp_send_json([
        'type' => 'success',
        'message' => 'Your request has been successfully sent, thank you!',
    ]);
} else {
    wp_send_json(['type' => 'error', 'message' => 'Error sending email']);
}
