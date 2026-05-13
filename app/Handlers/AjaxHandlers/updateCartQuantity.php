<?php

use App\Controllers\CartController;

verify_ajax_nonce();

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
$oldQuantity = isset($_POST['oldQuantity']) ? absint(wp_unslash($_POST['oldQuantity'])) : 0;
$type = isset($_POST['type']) && sanitize_text_field(wp_unslash($_POST['type'])) === 'decrement' ? 'decrement' : 'increment';

if (empty($key) || $oldQuantity <= 0) {
    wp_send_json(['type' => 'error', 'message' => 'invalid_data']);
}

(new CartController())->updateCartQuantity($key, $oldQuantity, $type);
