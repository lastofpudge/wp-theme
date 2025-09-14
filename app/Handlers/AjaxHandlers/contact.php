<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$name = sanitize_text_field($_POST['name'] ?? '');
$mail = sanitize_email($_POST['mail'] ?? '');

if (empty($name) || empty($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Please fill in all required fields']);
}

if (strlen($name) < 2 || strlen($name) > 100) {
    wp_send_json(['type' => 'error', 'message' => 'Name must be between 2 and 100 characters']);
}

if (!is_email($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Please enter a valid email address']);
}

$isMailSent = send_email('test', [
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
