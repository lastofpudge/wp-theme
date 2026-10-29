<?php

verify_ajax_nonce();

$email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
$password = wp_unslash($_POST['password'] ?? '');
$firstName = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
$lastName = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));

if (empty($email) || empty($password)) {
    wp_send_json(['type' => 'error', 'message' => __('Please fill in all required fields.', 'woocommerce')]);
}

if (!is_email($email)) {
    wp_send_json(['type' => 'error', 'message' => __('Please enter a valid email address.', 'woocommerce')]);
}

if (strlen($password) < 8) {
    wp_send_json(['type' => 'error', 'message' => __('Password must be at least 8 characters.', 'woocommerce')]);
}

if (email_exists($email)) {
    wp_send_json(['type' => 'error', 'message' => __('An account is already registered with your email address.', 'woocommerce')]);
}

$username = wc_create_new_customer_username($email);
$userId = wc_create_new_customer($email, $username, $password, [
    'first_name' => $firstName,
    'last_name'  => $lastName,
]);

if (is_wp_error($userId)) {
    wp_send_json(['type' => 'error', 'message' => wp_strip_all_tags($userId->get_error_message())]);
}

wp_set_current_user($userId);
wp_set_auth_cookie($userId);
do_action('woocommerce_created_customer', $userId, [], false);

wp_send_json([
    'type'    => 'success',
    'message' => __('Your account has been created successfully.', 'woocommerce'),
]);
