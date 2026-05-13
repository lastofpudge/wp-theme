<?php

use App\Controllers\CartController;

verify_ajax_nonce();

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';

if ($key === '') {
    wp_send_json(['type' => 'error', 'message' => __('Cart item not found.', 'woocommerce')]);
}

(new CartController())->removeFromCart($key);
