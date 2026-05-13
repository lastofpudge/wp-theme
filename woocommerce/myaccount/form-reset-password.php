<?php

defined('ABSPATH') || exit;

do_action('woocommerce_before_reset_password_form');

$message    = apply_filters('woocommerce_reset_password_message', esc_html__('Enter a new password below.', 'woocommerce'));
$form_extra = capture_action('woocommerce_resetpassword_form');
$nonce      = wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce', true, false);

\Timber\Timber::render('views/woocommerce/myaccount/form-reset-password.twig', [
    'message'     => $message,
    'form_extra'  => $form_extra,
    'nonce'       => $nonce,
    'reset_key'   => esc_attr($args['key'] ?? ''),
    'reset_login' => esc_attr($args['login'] ?? ''),
]);

do_action('woocommerce_after_reset_password_form');
