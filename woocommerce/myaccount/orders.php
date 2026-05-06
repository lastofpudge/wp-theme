<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders);

$columns     = wc_get_account_orders_columns();
$order_rows  = [];

if ($has_orders) {
    foreach ($customer_orders->orders as $customer_order) {
        $order      = wc_get_order($customer_order);
        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
        $cells      = [];

        foreach ($columns as $column_id => $column_name) {
            if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) {
                $content = capture_action('woocommerce_my_account_my_orders_column_' . $column_id, $order);
            } elseif ('order-number' === $column_id) {
                $content = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url($order->get_view_order_url()),
                    esc_attr(sprintf(__('View order number %s', 'woocommerce'), $order->get_order_number())),
                    esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number())
                );
            } elseif ('order-date' === $column_id) {
                $content = sprintf(
                    '<time datetime="%s">%s</time>',
                    esc_attr($order->get_date_created()->date('c')),
                    esc_html(wc_format_datetime($order->get_date_created()))
                );
            } elseif ('order-status' === $column_id) {
                $content = esc_html(wc_get_order_status_name($order->get_status()));
            } elseif ('order-total' === $column_id) {
                $content = wp_kses_post(sprintf(
                    _n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'),
                    $order->get_formatted_order_total(),
                    $item_count
                ));
            } elseif ('order-actions' === $column_id) {
                $actions = wc_get_account_orders_actions($order);
                $content = '';
                foreach ($actions as $key => $action) {
                    $aria = empty($action['aria-label'])
                        ? sprintf(__('%1$s order number %2$s', 'woocommerce'), $action['name'], $order->get_order_number())
                        : $action['aria-label'];
                    $content .= sprintf(
                        '<a href="%s" class="woocommerce-button%s button %s" aria-label="%s">%s</a>',
                        esc_url($action['url']),
                        esc_attr($wp_button_class),
                        sanitize_html_class($key),
                        esc_attr($aria),
                        esc_html($action['name'])
                    );
                }
            } else {
                $content = '';
            }

            $cells[$column_id] = [
                'name'      => $column_name,
                'content'   => $content,
                'is_header' => 'order-number' === $column_id,
            ];
        }

        $order_rows[] = [
            'status' => esc_attr($order->get_status()),
            'cells'  => $cells,
        ];
    }
}

\Timber\Timber::render('views/woocommerce/myaccount/orders.twig', [
    'has_orders'    => $has_orders,
    'columns'       => $columns,
    'order_rows'    => $order_rows,
    'current_page'  => $current_page,
    'max_pages'     => $has_orders ? (int) ($customer_orders->max_num_pages ?? 1) : 1,
    'prev_url'      => $current_page > 1 ? esc_url(wc_get_endpoint_url('orders', $current_page - 1)) : '',
    'next_url'      => esc_url(wc_get_endpoint_url('orders', $current_page + 1)),
    'shop_url'      => esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))),
    'btn_class'     => esc_attr($wp_button_class ?? ''),
]);

do_action('woocommerce_after_account_orders', $has_orders);
