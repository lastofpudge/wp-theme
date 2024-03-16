<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';

try {
    $response = WC()->cart->remove_cart_item($key);

    if ($response) {
        $total = WC()->cart->get_cart_contents_total();
        $subTotal = WC()->cart->get_subtotal();
        $cartItemCount = WC()->cart->get_cart_contents_count();

        wp_send_json([
            'type' => 'success',
            'message' => 'Product removed from the cart.',
            'total' => number_format($total, 2, '.', ''), // Format total amount
            'subTotal' => $subTotal,
            'count' => $cartItemCount
        ]);
    } else {
        wp_send_json(['type' => 'error', 'message' => 'removal_failed']);
    }
} catch (Exception $e) {
    wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
}
