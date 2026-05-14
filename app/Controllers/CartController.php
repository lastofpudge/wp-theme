<?php

namespace App\Controllers;

use App\Traits\HandlesWcNotices;
use Exception;

class CartController
{
    use HandlesWcNotices;

    private function total(): string
    {
        return number_format(WC()->cart->get_cart_contents_total(), 2, '.', '');
    }

    private function subtotal(): string
    {
        $cart = WC()->cart;
        $amount = method_exists($cart, 'get_subtotal')
            ? (float) $cart->get_subtotal()
            : (float) $cart->get_cart_contents_total();

        return number_format($amount, 2, '.', '');
    }

    public function addToCart(int $product_id, int $variation_id = 0, int $quantity = 1): void
    {
        try {
            $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            if (!$result) {
                wp_send_json(['type' => 'error', 'message' => $this->getNotice('error', __('Could not add product to cart.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }

        wp_send_json([
            'type'     => 'success',
            'message'  => wp_strip_all_tags(wc_add_to_cart_message([$product_id => $quantity], false, true)),
            'cart'     => get_cart_data(),
            'total'    => $this->total(),
            'subTotal' => $this->subtotal(),
            'count'    => WC()->cart->get_cart_contents_count(),
        ]);
    }

    public function removeFromCart(string $key): void
    {
        try {
            if (WC()->cart->remove_cart_item($key)) {
                wp_send_json([
                    'type'     => 'success',
                    'message'  => $this->getNotice('success', __('Product removed from the cart.', 'woocommerce')),
                    'total'    => $this->total(),
                    'subTotal' => $this->subtotal(),
                    'count'    => WC()->cart->get_cart_contents_count(),
                ]);
            } else {
                wp_send_json(['type' => 'error', 'message' => $this->getNotice('error', __('Could not remove product from cart.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateCartQuantity(string $key, int $oldQuantity, string $type = 'increment'): void
    {
        try {
            $cart = WC()->cart;

            if (!array_key_exists($key, $cart->get_cart())) {
                wp_send_json(['type' => 'error', 'message' => __('Cart item not found.', 'woocommerce')]);
            }

            $newQuantity = $type === 'decrement' ? max(1, $oldQuantity - 1) : $oldQuantity + 1;

            if ($cart->set_quantity($key, $newQuantity)) {
                wp_send_json([
                    'type'        => 'success',
                    'newQuantity' => $newQuantity,
                    'cart'        => get_cart_data(),
                    'total'       => $this->total(),
                    'subTotal'    => $this->subtotal(),
                    'count'       => WC()->cart->get_cart_contents_count(),
                    'message'     => __('Quantity updated.', 'woocommerce'),
                ]);
            } else {
                wp_send_json(['type' => 'error', 'message' => $this->getNotice('error', __('Could not update quantity.', 'woocommerce'))]);
            }
        } catch (Exception $e) {
            wp_send_json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function applyCoupon(string $couponCode): void
    {
        if (WC()->cart->has_discount($couponCode)) {
            wp_send_json(['response' => false, 'message' => __('Coupon is already applied.', 'woocommerce')]);
        }

        if (!WC()->cart->apply_coupon($couponCode)) {
            wp_send_json(['response' => false, 'message' => $this->getNotice('error', __('Invalid coupon.', 'woocommerce'))]);
        }

        wp_send_json([
            'response' => true,
            'message'  => $this->getNotice('success', __('Coupon applied successfully.', 'woocommerce')),
            'total'    => $this->total(),
            'subTotal' => $this->subtotal(),
        ]);
    }

    public function removeCoupon(string $couponCode): void
    {
        if (!WC()->cart->has_discount($couponCode)) {
            wp_send_json(['response' => false, 'message' => __('Coupon not applied.', 'woocommerce')]);
        }

        $response = WC()->cart->remove_coupon($couponCode);

        wp_send_json([
            'response' => $response,
            'message'  => $response ? __('Coupon removed.', 'woocommerce') : __('Could not remove coupon.', 'woocommerce'),
            'total'    => $this->total(),
            'subTotal' => $this->subtotal(),
        ]);
    }
}
