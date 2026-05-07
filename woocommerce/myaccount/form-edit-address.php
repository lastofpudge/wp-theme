<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_address_form');

if (!$load_address) {
    wc_get_template('myaccount/my-address.php');
    return;
}

$page_title = apply_filters(
    'woocommerce_my_account_edit_address_title',
    'billing' === $load_address
        ? esc_html__('Billing address', 'woocommerce')
        : esc_html__('Shipping address', 'woocommerce'),
    $load_address
);

$before_form = capture_action("woocommerce_before_edit_address_form_{$load_address}");
$after_form  = capture_action("woocommerce_after_edit_address_form_{$load_address}");

ob_start();
foreach ($address as $key => $field) {
    woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
}
$fields_html = ob_get_clean();

$nonce = wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce', true, false);

\Timber\Timber::render('views/woocommerce/myaccount/form-edit-address.twig', [
    'page_title'  => $page_title,
    'before_form' => $before_form,
    'after_form'  => $after_form,
    'fields_html' => $fields_html,
    'nonce'       => $nonce,
]);

do_action('woocommerce_after_edit_account_address_form');
