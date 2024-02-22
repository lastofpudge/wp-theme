<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = sanitize_text_field($_POST['product_id']);
$quantity = intval($_POST['quantity']);

try {
    $result = WC()->cart->add_to_cart($product_id, $quantity);
} catch (Exception $e) {
    wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
}


if ($result) {
    $total = WC()->cart->get_cart_contents_total();
    $subTotal = WC()->cart->get_subtotal();
    $cartItemCount = WC()->cart->get_cart_contents_count();

    $originProduct = wc_get_product($product_id);
    $productData = $originProduct->get_data();
    $currencySymbol = get_woocommerce_currency_symbol();

    if (!empty($productData['sale_price'])) {
        $productData['sale_price'] = number_format($productData['sale_price'], 2, ',', '');
    } else {
        $productData['sale_price'] = null;
    }

    $product = [
        'id' => $product_id,
        'name' => $productData['name'],
        'link' => get_permalink($product_id),
        'regular_price' => number_format($productData['price'], 2, ',', ''),
        'quantity' => $quantity,
        'sale_price' => $productData['sale_price'],
        'currency_symbol' => $currencySymbol,
        'cart_item_key' => $result,
        'sku' => $productData['sku'],
        'thumbnail' => $originProduct->get_image(),
    ];

    wp_send_json([
        'type' => 'success',
        'message' => 'Product added to the cart.',
        'total' => $total,
        'subTotal' => $subTotal,
        'count' => $cartItemCount,
        'product' => $product
    ]);
} else {
    wp_send_json(['type' => 'error', 'message' => 'Error adding to cart']);
}
