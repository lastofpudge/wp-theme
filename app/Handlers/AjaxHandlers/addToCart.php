<?php

use App\Controllers\CartController;

verify_ajax_nonce();

$product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
$variation_id = !empty($_POST['variation']) ? absint(wp_unslash($_POST['variation'])) : 0;
$quantity = isset($_POST['quantity']) ? max(1, absint(wp_unslash($_POST['quantity']))) : 1;

if ($product_id <= 0) {
    wp_send_json(['type' => 'error', 'message' => __('Invalid product.', 'woocommerce')]);
}

(new CartController())->addToCart($product_id, $variation_id, $quantity);
