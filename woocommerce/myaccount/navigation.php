<?php

defined('ABSPATH') || exit;

do_action('woocommerce_before_account_navigation');

$menu_items = wc_get_account_menu_items();
$nav_items  = [];

foreach ($menu_items as $endpoint => $label) {
    $nav_items[] = [
        'endpoint' => $endpoint,
        'label'    => $label,
        'url'      => wc_get_account_endpoint_url($endpoint),
        'classes'  => wc_get_account_menu_item_classes($endpoint),
        'current'  => wc_is_current_account_menu_item($endpoint),
    ];
}

\Timber\Timber::render('views/woocommerce/myaccount/navigation.twig', [
    'nav_items' => $nav_items,
]);

do_action('woocommerce_after_account_navigation');
