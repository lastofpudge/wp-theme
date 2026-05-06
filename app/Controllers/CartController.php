<?php

namespace App\Controllers;

use Exception;

class CartController extends Controller
{
    private static function getSubtotalAmount(): float
    {
        $cart = WC()->cart;

        if (method_exists($cart, 'get_subtotal')) {
            return (float) $cart->get_subtotal();
        }

        return (float) $cart->get_cart_contents_total();
    }

    public static function addToCart($product_id, $variation_id = 0): void
    {
        $quantity = isset($_POST['quantity']) ? max(1, absint(wp_unslash($_POST['quantity']))) : 1;

        try {
            $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            if (!$result) {
                $notices = wc_get_notices('error');
                $message = !empty($notices)
                    ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
                    : __('Could not add product to cart.', 'woocommerce');
                wc_clear_notices();
                wp_send_json(['type' => 'error', 'message' => $message]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }

        $message = wp_strip_all_tags(wc_add_to_cart_message(
            [$product_id => $quantity],
            false,
            true
        ));

        wp_send_json([
            'type'     => 'success',
            'message'  => $message,
            'cart'     => get_cart_data(),
            'total'    => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
            'subTotal' => number_format(self::getSubtotalAmount(), 2, '.', ''),
            'count'    => WC()->cart->get_cart_contents_count(),
        ]);
    }

    public static function removeFromCart($key): void
    {
        try {
            $response = WC()->cart->remove_cart_item($key);

            if ($response) {
                $removeNotices = wc_get_notices('success');
                $removeMessage = !empty($removeNotices)
                    ? wp_strip_all_tags(implode(' ', array_column($removeNotices, 'notice')))
                    : __('Product removed from the cart.', 'woocommerce');
                wc_clear_notices();

                wp_send_json([
                    'type'     => 'success',
                    'message'  => $removeMessage,
                    'total'    => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
                    'subTotal' => number_format(self::getSubtotalAmount(), 2, '.', ''),
                    'count'    => WC()->cart->get_cart_contents_count(),
                ]);
            } else {
                $notices = wc_get_notices('error');
                $message = !empty($notices)
                    ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
                    : __('Could not remove product from cart.', 'woocommerce');
                wc_clear_notices();
                wp_send_json(['type' => 'error', 'message' => $message]);
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
            if ($cart_item_key) {
                if ($type === 'decrement') {
                    $newQuantity = max(1, $oldQuantity - 1);
                } else {
                    $newQuantity = $oldQuantity + 1;
                }
                $response = $cart->set_quantity($cart_item_key, $newQuantity);

                if ($response) {
                    wp_send_json([
                        'type'        => 'success',
                        'newQuantity' => $newQuantity,
                        'cart'        => get_cart_data(),
                        'total'       => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
                        'subTotal'    => number_format(self::getSubtotalAmount(), 2, '.', ''),
                        'count'       => WC()->cart->get_cart_contents_count(),
                        'message'     => __('Quantity updated.', 'woocommerce'),
                    ]);
                } else {
                    $notices = wc_get_notices('error');
                    $message = !empty($notices)
                        ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
                        : __('Could not update quantity.', 'woocommerce');
                    wc_clear_notices();
                    wp_send_json(['type' => 'error', 'message' => $message]);
                }
            } else {
                wp_send_json(['type' => 'error', 'message' => __('Cart item not found.', 'woocommerce')]);
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

        $response = WC()->cart->apply_coupon($couponCode);

        if (!$response) {
            $notices = wc_get_notices('error');
            $message = !empty($notices)
                ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
                : __('Invalid coupon.', 'woocommerce');
            wc_clear_notices();
            wp_send_json(['response' => false, 'message' => $message]);
        }

        WC()->cart->calculate_totals();
        $notices = wc_get_notices('success');
        $message = !empty($notices)
            ? wp_strip_all_tags(implode(' ', array_column($notices, 'notice')))
            : __('Coupon applied successfully.', 'woocommerce');
        wc_clear_notices();

        wp_send_json([
            'response' => true,
            'message'  => $message,
            'total'    => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
            'subTotal' => number_format(self::getSubtotalAmount(), 2, '.', ''),
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
            'total'    => number_format(WC()->cart->get_cart_contents_total(), 2, '.', ''),
            'subTotal' => number_format(self::getSubtotalAmount(), 2, '.', ''),
        ]);
    }
}
