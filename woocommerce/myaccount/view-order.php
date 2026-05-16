<?php

defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();

$status_text = wp_kses_post(apply_filters(
    'woocommerce_order_details_status',
    sprintf(
        esc_html__('Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce'),
        '<mark class="order-number">'.$order->get_order_number().'</mark>',
        '<mark class="order-date">'.wc_format_datetime($order->get_date_created()).'</mark>',
        '<mark class="order-status">'.wc_get_order_status_name($order->get_status()).'</mark>'
    ),
    $order
));

$note_rows = [];
foreach ($notes as $note) {
    $note_rows[] = [
        'date'    => date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)),
        'content' => wp_kses_post(wpautop(wptexturize($note->comment_content))),
    ];
}

$order_details = capture_action('woocommerce_view_order', $order_id);

\Timber\Timber::render('views/woocommerce/myaccount/view-order.twig', [
    'status_text'   => $status_text,
    'note_rows'     => $note_rows,
    'order_details' => $order_details,
]);
