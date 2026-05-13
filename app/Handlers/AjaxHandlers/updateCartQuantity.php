<?php

use App\Controllers\CartController;

if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}

$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
$oldQuantity = isset($_POST['oldQuantity']) ? absint(wp_unslash($_POST['oldQuantity'])) : 0;
$type = isset($_POST['type']) && sanitize_text_field(wp_unslash($_POST['type'])) === 'decrement' ? 'decrement' : 'increment';

if (empty($key) || $oldQuantity <= 0) {
    wp_send_json(['type' => 'error', 'message' => 'invalid_data']);
}

(new CartController())->updateCartQuantity($key, $oldQuantity, $type);
