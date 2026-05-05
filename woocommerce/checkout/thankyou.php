<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;

$failed = $order && $order->has_status('failed');

if ($order) {
    do_action('woocommerce_before_thankyou', $order->get_id());
}

\Timber\Timber::render('woocommerce/components/order-details.twig', [
    'order'  => $order ?: false,
    'failed' => $failed,
]);

if ($order && !$failed) {
    do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
    do_action('woocommerce_thankyou', $order->get_id());
}
