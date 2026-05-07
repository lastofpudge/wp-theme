<?php

namespace App\Controllers;

use Exception;

class CartController extends Controller
{
    private static function notice(string $type, string $fallback): string
    {
        $notices = wc_get_notices($type);
        $message = !empty($notices)
            ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
            : $fallback;
        wc_clear_notices();

        return $message;
    }

    private static function total(): string
    {
        return number_format(WC()->cart->get_cart_contents_total(), 2, '.', '');
    }

    private static function subtotal(): string
    {
        $cart = WC()->cart;
        $amount = method_exists($cart, 'get_subtotal')
            ? (float) $cart->get_subtotal()
            : (float) $cart->get_cart_contents_total();

        return number_format($amount, 2, '.', '');
    }

    public static function addToCart($product_id, $variation_id = 0): void
    {
        $quantity = isset($_POST['quantity']) ? max(1, absint(wp_unslash($_POST['quantity']))) : 1;

        try {
            $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            if (!$result) {
                wp_send_json(['type' => 'error', 'message' => self::notice('error', __('Could not add product to cart.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }

        wp_send_json([
            'type'     => 'success',
            'message'  => wp_strip_all_tags(wc_add_to_cart_message([$product_id => $quantity], false, true)),
            'cart'     => get_cart_data(),
            'total'    => self::total(),
            'subTotal' => self::subtotal(),
            'count'    => WC()->cart->get_cart_contents_count(),
        ]);
    }

    public static function removeFromCart($key): void
    {
        try {
            if (WC()->cart->remove_cart_item($key)) {
                wp_send_json([
                    'type'     => 'success',
                    'message'  => self::notice('success', __('Product removed from the cart.', 'woocommerce')),
                    'total'    => self::total(),
                    'subTotal' => self::subtotal(),
                    'count'    => WC()->cart->get_cart_contents_count(),
                ]);
            } else {
                wp_send_json(['type' => 'error', 'message' => self::notice('error', __('Could not remove product from cart.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public static function updateCartQuantity($key, $oldQuantity, string $type = 'increment'): void
    {
        try {
            $cart = WC()->cart;
            $cart_item_key = $cart->find_product_in_cart($key);

            if (!$cart_item_key) {
                wp_send_json(['type' => 'error', 'message' => __('Cart item not found.', 'woocommerce')]);
            }

            $newQuantity = $type === 'decrement' ? max(1, $oldQuantity - 1) : $oldQuantity + 1;

            if ($cart->set_quantity($cart_item_key, $newQuantity)) {
                wp_send_json([
                    'type'        => 'success',
                    'newQuantity' => $newQuantity,
                    'cart'        => get_cart_data(),
                    'total'       => self::total(),
                    'subTotal'    => self::subtotal(),
                    'count'       => WC()->cart->get_cart_contents_count(),
                    'message'     => __('Quantity updated.', 'woocommerce'),
                ]);
            } else {
                wp_send_json(['type' => 'error', 'message' => self::notice('error', __('Could not update quantity.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public static function applyCoupon($couponCode): void
    {
        if (WC()->cart->has_discount($couponCode)) {
            wp_send_json(['response' => false, 'message' => __('Coupon is already applied.', 'woocommerce')]);
        }

        if (!WC()->cart->apply_coupon($couponCode)) {
            wp_send_json(['response' => false, 'message' => self::notice('error', __('Invalid coupon.', 'woocommerce'))]);
        }

        WC()->cart->calculate_totals();

        wp_send_json([
            'response' => true,
            'message'  => self::notice('success', __('Coupon applied successfully.', 'woocommerce')),
            'total'    => self::total(),
            'subTotal' => self::subtotal(),
        ]);
    }

    public static function removeCoupon($couponCode): void
    {
        if (!WC()->cart->has_discount($couponCode)) {
            wp_send_json(['response' => false, 'message' => __('Coupon not applied.', 'woocommerce')]);
        }

        $response = WC()->cart->remove_coupon($couponCode);
        WC()->cart->calculate_totals();

        wp_send_json([
            'response' => $response,
            'message'  => $response ? __('Coupon removed.', 'woocommerce') : __('Could not remove coupon.', 'woocommerce'),
            'total'    => self::total(),
            'subTotal' => self::subtotal(),
        ]);
    }
}
