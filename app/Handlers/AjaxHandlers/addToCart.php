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

$total = WC()->cart->get_cart_contents_total();
$subTotal = WC()->cart->get_subtotal();
$cartItemCount = WC()->cart->get_cart_contents_count();

$originProduct = wc_get_product($product_id);
$productData = $originProduct->get_data();
$product['sale_price'] = '';

if ($variation_id) {
    $product['name'] = wc_get_product($variation_id)->get_name();
    $product['regular_price'] = number_format(get_post_meta($variation_id, '_regular_price', true), 2, ',', '');
    ;
    if (!empty(get_post_meta($variation_id, '_sale_price', true))) {
        $product['sale_price'] = number_format(get_post_meta($variation_id, '_sale_price', true), 2, ',', '');
        ;
    }
} else {
    $product['name'] = $productData['name'];
    $product['regular_price'] = number_format($productData['price'], 2, ',', '');
    $product['sale_price'] = $productData['sale_price'];
}

$product = array_merge($product, [
    'link' => get_permalink($product_id),
    'quantity' => absint($_POST['quantity']),
    'currency_symbol' => get_woocommerce_currency_symbol(),
    'cart_item_key' => $result,
    'thumbnail' => $originProduct->get_image(),
]);

wp_send_json([
    'type' => 'success',
    'message' => 'Product added to the cart.',
    'total' => number_format($total, 2, '.', ''),
    'subTotal' => $subTotal,
    'count' => $cartItemCount,
    'product' => $product
]);
