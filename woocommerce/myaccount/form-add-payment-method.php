<?php

defined('ABSPATH') || exit;

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

if ($available_gateways) {
    if (count($available_gateways)) {
        current($available_gateways)->set_current();
    }

    $gateways = [];
    foreach ($available_gateways as $gateway) {
        $has_fields = $gateway->has_fields() || $gateway->get_description();
        $gateways[] = [
            'id'          => $gateway->id,
            'title'       => $gateway->get_title(),
            'icon'        => $gateway->get_icon(),
            'chosen'      => $gateway->chosen,
            'has_fields'  => $has_fields,
            'fields_html' => $has_fields ? capture_output([$gateway, 'payment_fields']) : '',
        ];
    }

    \Timber\Timber::render('views/woocommerce/myaccount/form-add-payment-method.twig', [
        'has_gateways' => true,
        'gateways'     => $gateways,
        'nonce_field'  => wp_nonce_field('woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce', true, false),
        'form_bottom'  => capture_action('woocommerce_add_payment_method_form_bottom'),
    ]);
} else {
    \Timber\Timber::render('views/woocommerce/myaccount/form-add-payment-method.twig', [
        'has_gateways' => false,
        'notice'       => esc_html__('New payment methods can only be added during checkout. Please contact us if you require assistance.', 'woocommerce'),
    ]);
}
