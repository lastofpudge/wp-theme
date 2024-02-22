<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$key = sanitize_text_field($_POST['key']);

$response = WC()->cart->remove_cart_item($key);

$total = WC()->cart->get_cart_contents_total();
$subTotal = WC()->cart->get_subtotal();
$cartItemCount = WC()->cart->get_cart_contents_count();

if ($response) {
    wp_send_json([
        'type' => 'success',
        'message' => 'Product removed from the cart.',
        'total' => number_format($total, 2, '.', ''),
        'subTotal' => $subTotal,
        'count' => $cartItemCount
    ]);
} else {
    wp_send_json(['type' => 'error', 'response' => $response]);
}
