<?php

use App\Controllers\CartController;

if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';

if ($key === '') {
    wp_send_json(['type' => 'error', 'message' => __('Cart item not found.', 'woocommerce')]);
}

(new CartController())->removeFromCart($key);
