<?php

defined('ABSPATH') || exit;

$saved_methods = wc_get_customer_saved_methods_list(get_current_user_id());
$has_methods = (bool) $saved_methods;
$columns = wc_get_account_payment_methods_columns();

do_action('woocommerce_before_account_payment_methods', $has_methods);

$method_rows = [];
foreach ($saved_methods as $type => $methods) {
    foreach ($methods as $method) {
        $cells = [];
        foreach ($columns as $column_id => $column_name) {
            if (has_action('woocommerce_account_payment_methods_column_'.$column_id)) {
                $content = capture_action('woocommerce_account_payment_methods_column_'.$column_id, $method);
            } elseif ('method' === $column_id) {
                if (!empty($method['method']['last4'])) {
                    $content = sprintf(
                        esc_html__('%1$s ending in %2$s', 'woocommerce'),
                        esc_html(wc_get_credit_card_type_label($method['method']['brand'])),
                        esc_html($method['method']['last4'])
                    );
                } else {
                    $content = esc_html(wc_get_credit_card_type_label($method['method']['brand']));
                }
            } elseif ('expires' === $column_id) {
                $content = esc_html($method['expires']);
            } elseif ('actions' === $column_id) {
                $content = '';
                foreach ($method['actions'] as $key => $action) {
                    $content .= sprintf(
                        '<a href="%s" class="button %s">%s</a>&nbsp;',
                        esc_url($action['url']),
                        sanitize_html_class($key),
                        esc_html($action['name'])
                    );
                }
            } else {
                $content = '';
            }

            $cells[$column_id] = ['name' => $column_name, 'content' => $content];
        }

        $method_rows[] = [
            'is_default' => !empty($method['is_default']),
            'cells'      => $cells,
        ];
    }
}

\Timber\Timber::render('views/woocommerce/myaccount/payment-methods.twig', [
    'has_methods'    => $has_methods,
    'columns'        => $columns,
    'method_rows'    => $method_rows,
    'can_add_method' => (bool) WC()->payment_gateways->get_available_payment_gateways(),
    'add_method_url' => esc_url(wc_get_endpoint_url('add-payment-method')),
]);

do_action('woocommerce_after_account_payment_methods', $has_methods);
