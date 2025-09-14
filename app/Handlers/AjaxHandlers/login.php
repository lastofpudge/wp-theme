<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$email = sanitize_email($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    wp_send_json(['type' => 'error', 'message' => 'Please fill in all required fields']);
}

if (!is_email($email)) {
    wp_send_json(['type' => 'error', 'message' => 'Please enter a valid email address']);
}

$credentials = [
    'user_login' => $email,
    'user_password' => $password,
    'remember' => true,
];

$user = wp_signon($credentials, false);

if (is_wp_error($user)) {
    wp_send_json([
        'type' => 'error',
        'message' => json_decode($user->get_error_message()),
    ]);
}

wp_send_json([
    'type' => 'success',
    'message' => 'Your successfully enter',
]);
