<?php

use App\Controllers\CartController;

if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$couponCode = isset($_POST['couponCode']) ? sanitize_text_field(wp_unslash($_POST['couponCode'])) : '';

(new CartController())->applyCoupon($couponCode);
