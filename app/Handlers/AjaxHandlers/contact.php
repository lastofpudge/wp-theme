<?php

verify_ajax_nonce();

// Use the direct peer address: forwarded headers can be forged by visitors.
$address = $_SERVER['REMOTE_ADDR'] ?? '';
$rateKey = 'theme_contact_'.hash_hmac('sha256', $address, wp_salt('nonce'));
$attempts = get_transient($rateKey);
if (!is_array($attempts) || ($attempts['expires'] ?? 0) <= time()) {
    $attempts = ['count' => 0, 'expires' => time() + 10 * MINUTE_IN_SECONDS];
}
if ($attempts['count'] >= 5) {
    header('Retry-After: '.max(1, $attempts['expires'] - time()));
    wp_send_json(['type' => 'error', 'message' => 'Too many requests. Please try again later.'], 429);
}
$attempts['count']++;
set_transient($rateKey, $attempts, max(1, $attempts['expires'] - time()));

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
