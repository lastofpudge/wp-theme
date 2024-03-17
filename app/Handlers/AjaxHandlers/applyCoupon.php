<?php

use App\Controllers\CartController;

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$couponCode = isset($_POST['couponCode']) ? sanitize_text_field($_POST['couponCode']) : '';

CartController::applyCoupon($couponCode);
