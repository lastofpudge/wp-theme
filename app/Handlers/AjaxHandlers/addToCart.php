<?php

use App\Controllers\CartController;

if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
$variation_id = !empty($_POST['variation']) ? absint(wp_unslash($_POST['variation'])) : 0;

if ($product_id <= 0) {
    wp_send_json(['type' => 'error', 'message' => __('Invalid product.', 'woocommerce')]);
}

(new CartController())->addToCart($product_id, $variation_id);
