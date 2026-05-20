<?php

defined('ABSPATH') || exit;

echo \Timber\Timber::compile(
    'views/woocommerce/emails/customer-refunded-order.twig',
    wc_email_frame_context($email_heading ?? '', $email ?? null, $additional_content ?? '') + [
        'first_name'       => $order->get_billing_first_name(),
        'order_number'     => $order->get_order_number(),
        'order_details'    => capture_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email),
        'order_meta'       => capture_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email),
        'customer_details' => capture_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email),
    ]
);
