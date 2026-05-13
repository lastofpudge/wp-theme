<?php

defined('ABSPATH') || exit;

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action('woocommerce_before_account_downloads', $has_downloads);

if ($has_downloads) {
    $before  = capture_action('woocommerce_before_available_downloads');
    $content = capture_action('woocommerce_available_downloads', $downloads);
    $after   = capture_action('woocommerce_after_available_downloads');
} else {
    $before = $content = $after = '';
}

\Timber\Timber::render('views/woocommerce/myaccount/downloads.twig', [
    'has_downloads' => $has_downloads,
    'before'        => $before,
    'content'       => $content,
    'after'         => $after,
    'shop_url'      => esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))),
]);

do_action('woocommerce_after_account_downloads', $has_downloads);
