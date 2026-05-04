<?php

use App\Controllers\CartController;

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$product_id = absint($_POST['product_id']);
$variation_id = !empty($_POST['variation']) ? absint($_POST['variation']) : 0;

CartController::addToCart($product_id, $variation_id);
