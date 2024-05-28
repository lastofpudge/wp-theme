<?php

use App\Controllers\CartController;

if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
}


$key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';

CartController::removeFromCart($key);
