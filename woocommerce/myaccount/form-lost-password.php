<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');

$message    = apply_filters('woocommerce_lost_password_message', esc_html__('Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce'));
$form_extra = capture_action('woocommerce_lostpassword_form');
$nonce      = wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce', true, false);

\Timber\Timber::render('views/woocommerce/myaccount/form-lost-password.twig', [
    'message'    => $message,
    'form_extra' => $form_extra,
    'nonce'      => $nonce,
]);

do_action('woocommerce_after_lost_password_form');
