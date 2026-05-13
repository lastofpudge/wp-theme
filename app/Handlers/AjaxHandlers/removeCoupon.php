<?php

use App\Controllers\CartController;

verify_ajax_nonce();

$couponCode = isset($_POST['couponCode']) ? sanitize_text_field(wp_unslash($_POST['couponCode'])) : '';

if ($couponCode === '') {
    wp_send_json(['type' => 'error', 'message' => __('Please enter a coupon code.', 'woocommerce')]);
}

(new CartController())->removeCoupon($couponCode);
