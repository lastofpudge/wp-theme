<?php

defined('ABSPATH') || exit;

$store_name = get_bloginfo('name', 'display');

if ($order->needs_payment()) {
    $intro = $order->has_status('failed')
        ? sprintf(
            __('Sorry, your order on %1$s was unsuccessful. Your order details are below, with a link to try your payment again: <a href="%2$s">Pay for this order</a>', 'woocommerce'),
            esc_html($store_name),
            esc_url($order->get_checkout_payment_url())
        )
        : sprintf(
            __('An order has been created for you on %1$s. Your order details are below, with a link to make payment when you\'re ready: <a href="%2$s">Pay for this order</a>', 'woocommerce'),
            esc_html($store_name),
            esc_url($order->get_checkout_payment_url())
        );
} else {
    $intro = sprintf(
        esc_html__('Here are the details of your order placed on %s:', 'woocommerce'),
        esc_html(wc_format_datetime($order->get_date_created()))
    );
}

echo \Timber\Timber::compile(
    'views/woocommerce/emails/customer-invoice.twig',
    wc_email_frame_context($email_heading ?? '', $email ?? null, $additional_content ?? '') + [
        'first_name'       => $order->get_billing_first_name(),
        'intro'            => wp_kses_post($intro),
        'order_details'    => capture_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email),
        'order_meta'       => capture_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email),
        'customer_details' => capture_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email),
    ]
);
