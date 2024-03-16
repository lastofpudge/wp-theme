<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = absint($_POST['product_id']);
$variation_id = $_POST['variation'] ? absint($_POST['variation']) : 0;

try {
    $result = WC()->cart->add_to_cart($product_id, absint($_POST['quantity']), $variation_id);
    if (!$result) {
        wp_send_json(['type' => 'error', 'message' => 'Error adding to cart']);
    }
} catch (Exception $e) {
    wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
}

wp_send_json([
    'type' => 'success',
    'message' => 'Product added to the cart.',
    'cart' => get_cart_data(),
    'total' => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
    'subTotal' => WC()->cart->get_subtotal(),
    'count' => WC()->cart->get_cart_contents_count(),
]);
