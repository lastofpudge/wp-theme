<?php

namespace App\Controllers;

use App\Traits\HandlesWcNotices;
use Exception;

class CartController
{
    use HandlesWcNotices;

    private function payload(): array
    {
        return get_cart_summary_payload();
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
        ] + $this->payload());
    }

    public function removeFromCart(string $key): void
    {
        try {
            if (WC()->cart->remove_cart_item($key)) {
                wp_send_json([
                    'type'     => 'success',
                    'message'  => $this->getNotice('success', __('Product removed from the cart.', 'woocommerce')),
                ] + $this->payload());
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
                    'message'     => __('Quantity updated.', 'woocommerce'),
                ] + $this->payload());
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
            wp_send_json(['type' => 'error', 'message' => __('Coupon is already applied.', 'woocommerce')]);
        }

        if (!WC()->cart->apply_coupon($couponCode)) {
            wp_send_json(['type' => 'error', 'message' => $this->getNotice('error', __('Invalid coupon.', 'woocommerce'))]);
        }

        wp_send_json([
            'type'     => 'success',
            'message'  => $this->getNotice('success', __('Coupon applied successfully.', 'woocommerce')),
        ] + $this->payload());
    }

    public function removeCoupon(string $couponCode): void
    {
        if (!WC()->cart->has_discount($couponCode)) {
            wp_send_json(['type' => 'error', 'message' => __('Coupon not applied.', 'woocommerce')]);
        }

        if (!WC()->cart->remove_coupon($couponCode)) {
            wp_send_json(['type' => 'error', 'message' => __('Could not remove coupon.', 'woocommerce')]);
        }

        wp_send_json([
            'type'     => 'success',
            'message'  => __('Coupon removed.', 'woocommerce'),
        ] + $this->payload());
    }
}
