<?php

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');

$registration_enabled = 'yes' === get_option('woocommerce_enable_myaccount_registration');

$login_form_start = capture_action('woocommerce_login_form_start');
$login_form_middle = capture_action('woocommerce_login_form');
$login_form_end = capture_action('woocommerce_login_form_end');

$register_data = [];
if ($registration_enabled) {
    $register_data['form_start'] = capture_action('woocommerce_register_form_start');
    $register_data['form_tag_attrs'] = capture_action('woocommerce_register_form_tag');
    $register_data['form_middle'] = capture_action('woocommerce_register_form');
    $register_data['form_end'] = capture_action('woocommerce_register_form_end');

    $register_data['generate_username'] = 'yes' === get_option('woocommerce_registration_generate_username');
    $register_data['generate_password'] = 'yes' === get_option('woocommerce_registration_generate_password');
    $register_data['nonce'] = wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce', true, false);
    $register_data['username_value'] = !empty($_POST['username']) ? esc_attr(wp_unslash($_POST['username'])) : '';
    $register_data['email_value'] = !empty($_POST['email']) ? esc_attr(wp_unslash($_POST['email'])) : '';
}

\Timber\Timber::render('views/woocommerce/myaccount/form-login.twig', [
    'registration_enabled' => $registration_enabled,
    'login_form_start'     => $login_form_start,
    'login_form_middle'    => $login_form_middle,
    'login_form_end'       => $login_form_end,
    'lost_password_url'    => esc_url(wp_lostpassword_url()),
    'register'             => $register_data,
]);

do_action('woocommerce_after_customer_login_form');
