<?php

namespace App\Controllers;

use Exception;

class CartController extends Controller
{
    public static function addToCart($product_id, $variation_id = 0): void
    {
        try {
            $result = WC()->cart->add_to_cart($product_id, absint($_POST['quantity']), $variation_id);
            if (!$result) {
                wp_send_json(['type' => 'error', 'message' => 'Error adding to cart']);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }

        wp_send_json([
            'type' => 'success',
            'message' => 'Product added to the cart.',
            'cart' => get_cart_data(),
            'total' => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
            'subTotal' => WC()->cart->get_subtotal(),
            'count' => WC()->cart->get_cart_contents_count(),
        ]);
    }

    public static function removeFromCart($key): void
    {
        try {
            $response = WC()->cart->remove_cart_item($key);

            if ($response) {
                $total = WC()->cart->get_cart_contents_total();
                $subTotal = WC()->cart->get_subtotal();
                $cartItemCount = WC()->cart->get_cart_contents_count();

                wp_send_json([
                    'type' => 'success',
                    'message' => 'Product removed from the cart.',
                    'total' => number_format($total, 2, '.', ''), // Format total amount
                    'subTotal' => $subTotal,
                    'count' => $cartItemCount
                ]);
            } else {
                wp_send_json(['type' => 'error', 'message' => 'removal_failed']);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public static function updateCartQuantity($key, $oldQuantity): void
    {
        try {
            $cart = WC()->cart;
            $cart_item_key = $cart->find_product_in_cart($key);
            if ($cart_item_key) {
                if ($_POST['type'] === 'decrement' && $oldQuantity > 1) {
                    $newQuantity = $oldQuantity - 1;
                } else {
                    $newQuantity = $oldQuantity + 1;
                }
                $response = $cart->set_quantity($cart_item_key, $newQuantity);

                if ($response) {
                    wp_send_json([
                        'type' => 'success',
                        'newQuantity' => $newQuantity,
                        'cart' => get_cart_data(),
                        'total' => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
                        'subTotal' => WC()->cart->get_subtotal(),
                        'count' => WC()->cart->get_cart_contents_count(),
                        'message' => 'Quantity updated'
                    ]);
                }
            } else {
                wp_send_json(['type' => 'error', 'message' => 'Item no found']);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public static function applyCoupon($couponCode): void
    {
        if (!WC()->cart->has_discount($couponCode)) {
            $response = WC()->cart->apply_coupon($couponCode);
            wp_send_json([
                'response' => $response,
            ]);
        }
    }

    public static function removeCoupon($couponCode): void
    {
        if (WC()->cart->has_discount($couponCode)) {
            $response = WC()->cart->remove_coupon($couponCode);
            WC()->cart->calculate_totals();

            wp_send_json([
                'response' => $response,
            ]);
        } else {
            wp_send_json([
                'response' => false,
            ]);
        }
    }
}
