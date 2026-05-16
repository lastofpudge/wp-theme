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
            $order   = $orderId ? wc_get_order($orderId) : null;

            $this->data['order_id'] = $order ? $order->get_id() : null;
            $this->data['order']    = $order;

            if ($order) {
                do_action('woocommerce_before_thankyou', $order->get_id());
            }

            if ($order && $order->has_status('failed')) {
                $this->data['thankyou'] = [
                    'failed'            => true,
                    'payment_url'       => esc_url($order->get_checkout_payment_url()),
                    'myaccount_url'     => esc_url(wc_get_page_permalink('myaccount')),
                    'is_user_logged_in' => is_user_logged_in(),
                ];
            } else {
                $this->data['thankyou'] = [
                    'failed'           => false,
                    'order_received'   => capture_action('woocommerce_thankyou_order_received_text'),
                    'order_number'     => $order ? $order->get_order_number() : '',
                    'order_date'       => $order ? wc_format_datetime($order->get_date_created()) : '',
                    'order_email'      => ($order && is_user_logged_in() && $order->get_user_id() === get_current_user_id())
                                            ? $order->get_billing_email() : '',
                    'order_total'      => $order ? $order->get_formatted_order_total() : '',
                    'payment_method'   => $order ? wp_kses_post($order->get_payment_method_title()) : '',
                    'payment_content'  => $order ? capture_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()) : '',
                    'thankyou_content' => $order ? capture_action('woocommerce_thankyou', $order->get_id()) : '',
                ];
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
        $this->data['terms_html']       = capture_output('wc_get_template', 'checkout/terms.php');

        return $this->data;
    }
}
