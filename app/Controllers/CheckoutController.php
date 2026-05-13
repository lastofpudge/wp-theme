<?php

namespace App\Controllers;

use Timber\Timber;

class CheckoutController extends Controller
{
    public function cart(): array
    {
        $this->data['coupons_enabled'] = wc_coupons_enabled();

        return $this->data;
    }

    public function checkout(): array
    {
        $this->data['post']              = Timber::get_post();
        $this->data['is_order_received'] = is_wc_endpoint_url('order-received');
        $this->data['order_id']          = null;
        $this->data['order']             = null;

        if ($this->data['is_order_received']) {
            $orderId = absint(get_query_var('order-received'));

            if ($orderId) {
                $order = wc_get_order($orderId);

                if ($order) {
                    $this->data['order_id'] = $order->get_id();
                    $this->data['order']    = $order;
                }
            }

            return $this->data;
        }

        $checkout = WC()->checkout();

        if (WC()->cart) {
            WC()->cart->calculate_shipping();
            WC()->cart->calculate_totals();
        }

        $this->data['checkout']       = $checkout;
        $this->data['fields']         = $checkout->get_checkout_fields();
        $this->data['checkout_url']   = wc_get_checkout_url();
        $this->data['checkout_nonce'] = wp_create_nonce('woocommerce-process_checkout');
        $this->data['http_referer']   = esc_attr(wp_unslash($_SERVER['REQUEST_URI'] ?? '/'));

        ob_start();
        if (WC()->cart && WC()->cart->needs_shipping()) {
            wc_cart_totals_shipping_html();
        }
        $this->data['shipping_methods_html'] = ob_get_clean();

        $gateways = [];
        foreach (WC()->payment_gateways()->get_available_payment_gateways() as $id => $gateway) {
            ob_start();
            $gateway->payment_fields();
            $gateways[$id] = [
                'id'          => $id,
                'title'       => $gateway->get_title(),
                'description' => $gateway->get_description(),
                'fields_html' => ob_get_clean(),
                'icon'        => $gateway->get_icon(),
            ];
        }
        $this->data['payment_gateways'] = $gateways;

        return $this->data;
    }
}
