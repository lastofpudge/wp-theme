<?php

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = sanitize_text_field($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$variation_id = null;

try {
    if (isset($_POST['variation'])) {
        $variation_id = intval($_POST['variation']);
        $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    } else {
        $result = WC()->cart->add_to_cart($product_id, $quantity);
    }
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

    $product['sale_price'] = null;

    if ($variation_id) {
        $product['name'] = wc_get_product($variation_id)->get_name();
        $product['regular_price'] = get_post_meta($variation_id, '_regular_price', true);
        $product['sale_price'] = get_post_meta($variation_id, '_sale_price', true);
    } else {
        $product['name'] = $productData['name'];
        $product['regular_price'] = number_format($productData['price'], 2, ',', '');
        $product['sale_price'] = $productData['sale_price'];
    }

    $product = array_merge($product, [
        'id' => $product_id,
        'link' => get_permalink($product_id),
        'quantity' => $quantity,
        'currency_symbol' => $currencySymbol,
        'cart_item_key' => $result,
        'sku' => $productData['sku'],
        'thumbnail' => $originProduct->get_image(),
    ]);

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
