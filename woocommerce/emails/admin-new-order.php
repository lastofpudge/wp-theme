<?php

defined('ABSPATH') || exit;

echo \Timber\Timber::compile(
    'views/woocommerce/emails/admin-new-order.twig',
    wc_email_frame_context($email_heading ?? '', $email ?? null, $additional_content ?? '') + [
        'customer_name'    => $order->get_formatted_billing_full_name(),
        'order_details'    => capture_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email),
        'order_meta'       => capture_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email),
        'customer_details' => capture_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email),
    ]
);
