<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = sanitize_text_field($_POST['product_id']);
$quantity = intval($_POST['quantity']);

$result = WC()->cart->add_to_cart($product_id, $quantity);

if ($result) {
    $total = WC()->cart->get_cart_contents_total();
    $subTotal = WC()->cart->get_subtotal();

    wp_send_json(['type' => 'success', 'message' => 'Product added to the cart.', 'total' => $total, 'subTotal' => $subTotal, 'result' => $result]);
} else {
    wp_send_json(['type' => 'error', 'message' => 'Error adding product to the cart.']);
}
