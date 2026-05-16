<?php

verify_ajax_nonce();

$name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
$mail = sanitize_email(wp_unslash($_POST['mail'] ?? ''));

if (empty($name) || empty($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Please fill in all required fields']);
}

if (strlen($name) < 2 || strlen($name) > 100) {
    wp_send_json(['type' => 'error', 'message' => 'Name must be between 2 and 100 characters']);
}

if (!is_email($mail)) {
    wp_send_json(['type' => 'error', 'message' => 'Please enter a valid email address']);
}

$isMailSent = send_email('contact', [
    'subject'   => __('New contact form submission', 'woocommerce'),
    'site_name' => get_bloginfo('name'),
    'name'      => $name,
    'mail'      => $mail,
    'reply_to'  => $mail,
]);

if ($isMailSent) {
    wp_send_json([
        'type'    => 'success',
        'message' => 'Your request has been successfully sent, thank you!',
    ]);
} else {
    wp_send_json(['type' => 'error', 'message' => 'Error sending email']);
}
