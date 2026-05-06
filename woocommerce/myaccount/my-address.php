<?php
defined('ABSPATH') || exit;

$customer_id   = get_current_user_id();
$multi_address = !wc_ship_to_billing_address_only() && wc_shipping_enabled();

if ($multi_address) {
    $get_addresses = apply_filters('woocommerce_my_account_get_addresses', [
        'billing'  => __('Billing address', 'woocommerce'),
        'shipping' => __('Shipping address', 'woocommerce'),
    ], $customer_id);
} else {
    $get_addresses = apply_filters('woocommerce_my_account_get_addresses', [
        'billing' => __('Billing address', 'woocommerce'),
    ], $customer_id);
}

$addresses = [];
foreach ($get_addresses as $name => $title) {
    $formatted = wc_get_account_formatted_address($name);

    $after_html = capture_action('woocommerce_my_account_after_my_address', $name);

    $addresses[] = [
        'name'       => $name,
        'title'      => esc_html($title),
        'address'    => $formatted ? wp_kses_post($formatted) : '',
        'edit_url'   => esc_url(wc_get_endpoint_url('edit-address', $name)),
        'after_html' => $after_html,
    ];
}

\Timber\Timber::render('views/woocommerce/myaccount/my-address.twig', [
    'multi_address' => $multi_address,
    'description'   => apply_filters('woocommerce_my_account_my_address_description', esc_html__('The following addresses will be used on the checkout page by default.', 'woocommerce')),
    'addresses'     => $addresses,
]);
