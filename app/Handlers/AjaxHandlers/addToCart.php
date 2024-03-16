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

$cart = WC()->cart->get_cart();
$cart_data = [];

foreach ($cart as $cart_item_key => $cart_item) {
    $_product = $cart_item['data'];
    $sale_price = null;

    if (!empty($_product->get_sale_price())) {
        $sale_price = wc_price($_product->get_sale_price());
    }

    $item_data = [
        'id' => $cart_item['product_id'],
        'name' => $_product->get_name(),
        'link' => get_permalink($cart_item['product_id']),
        'thumbnail' => $_product->get_image(),
        'quantity' => $cart_item['quantity'],
        'cart_item_key' => $result,
        'regular_price' => wc_price($_product->get_regular_price()),
        'sale_price' => $sale_price,
    ];

    $cart_data[] = $item_data;
}

wp_send_json([
    'type' => 'success',
    'message' => 'Product added to the cart.',
    'cart' => $cart_data,
    'total' => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
    'subTotal' => WC()->cart->get_subtotal(),
    'count' => WC()->cart->get_cart_contents_count(),
]);
