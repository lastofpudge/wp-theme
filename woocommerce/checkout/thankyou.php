<?php
defined('ABSPATH') || exit;

if ($order) {
    do_action('woocommerce_before_thankyou', $order->get_id());

    if ($order->has_status('failed')) {
        \Timber\Timber::render('views/woocommerce/checkout/thankyou.twig', [
            'failed'            => true,
            'payment_url'       => esc_url($order->get_checkout_payment_url()),
            'myaccount_url'     => esc_url(wc_get_page_permalink('myaccount')),
            'is_user_logged_in' => is_user_logged_in(),
        ]);
    } else {
        $order_received    = capture_action('woocommerce_thankyou_order_received_text');
        $payment_content   = capture_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
        $thankyou_content  = capture_action('woocommerce_thankyou', $order->get_id());

        \Timber\Timber::render('views/woocommerce/checkout/thankyou.twig', [
            'failed'           => false,
            'order_received'   => $order_received,
            'order_number'     => $order->get_order_number(),
            'order_date'       => wc_format_datetime($order->get_date_created()),
            'order_email'      => (is_user_logged_in() && $order->get_user_id() === get_current_user_id())
                                      ? $order->get_billing_email()
                                      : '',
            'order_total'      => $order->get_formatted_order_total(),
            'payment_method'   => wp_kses_post($order->get_payment_method_title()),
            'payment_content'  => $payment_content,
            'thankyou_content' => $thankyou_content,
        ]);
    }
} else {
    ob_start();
    wc_get_template('checkout/order-received.php', ['order' => false]);
    $order_received = ob_get_clean();

    \Timber\Timber::render('views/woocommerce/checkout/thankyou.twig', [
        'failed'           => false,
        'order_received'   => $order_received,
        'order_number'     => '',
        'order_date'       => '',
        'order_email'      => '',
        'order_total'      => '',
        'payment_method'   => '',
        'payment_content'  => '',
        'thankyou_content' => '',
    ]);
}
