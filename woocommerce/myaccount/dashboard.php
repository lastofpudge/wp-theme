<?php

defined('ABSPATH') || exit;

do_action('woocommerce_account_dashboard');
do_action('woocommerce_before_my_account');

$shipping_enabled = wc_shipping_enabled() && !wc_ship_to_billing_address_only();

if ($shipping_enabled) {
    $desc = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
} else {
    $desc = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
}

$description_html = wp_kses(sprintf(
    $desc,
    esc_url(wc_get_endpoint_url('orders')),
    esc_url(wc_get_endpoint_url('edit-address')),
    esc_url(wc_get_endpoint_url('edit-account'))
), ['a' => ['href' => []]]);

\Timber\Timber::render('views/woocommerce/myaccount/dashboard.twig', [
    'user_name'       => esc_html($current_user->display_name),
    'logout_url'      => esc_url(wc_logout_url()),
    'description_html' => $description_html,
]);

do_action('woocommerce_after_my_account');
