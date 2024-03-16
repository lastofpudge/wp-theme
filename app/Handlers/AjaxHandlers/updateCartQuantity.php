<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
$oldQuantity = isset($_POST['oldQuantity']) ? absint(wp_unslash($_POST['oldQuantity'])) : 0;

if (empty($key) || $oldQuantity <= 0) {
    wp_send_json(['type' => 'error', 'message' => 'invalid_data']);
}

try {
    $cart = WC()->cart;
    $cart_item_key = $cart->find_product_in_cart($key);
    if ($cart_item_key) {
        if ($_POST['type'] === 'decrement' && $oldQuantity > 1) {
            $newQuantity = $oldQuantity - 1;
        } else {
            $newQuantity = $oldQuantity + 1;
        }
        $response = $cart->set_quantity($cart_item_key, $newQuantity);

        if ($response) {
            wp_send_json([
                'type' => 'success',
                'newQuantity' => $newQuantity,
                'cart' => get_cart_data(),
                'total' => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
                'subTotal' => WC()->cart->get_subtotal(),
                'count' => WC()->cart->get_cart_contents_count(),
                'message' => 'Quantity updated'
            ]);
        }
    } else {
        wp_send_json(['type' => 'error', 'message' => 'Item no found']);
    }
} catch (Exception $e) {
    wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
}
