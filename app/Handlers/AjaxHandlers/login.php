<?php

verify_ajax_nonce();

$email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
$password = wp_unslash($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    wp_send_json(['type' => 'error', 'message' => 'Please fill in all required fields']);
}

if (!is_email($email)) {
    wp_send_json(['type' => 'error', 'message' => 'Please enter a valid email address']);
}

$credentials = [
    'user_login'    => $email,
    'user_password' => $password,
    'remember'      => true,
];

$user = wp_signon($credentials, false);

if (is_wp_error($user)) {
    wp_send_json([
        'type'    => 'error',
        'message' => wp_strip_all_tags($user->get_error_message()),
    ]);
}

wp_send_json([
    'type'    => 'success',
    'message' => __('You have successfully logged in.', 'woocommerce'),
]);
