<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form');

$form_tag    = capture_action('woocommerce_edit_account_form_tag');
$form_start  = capture_action('woocommerce_edit_account_form_start');
$form_fields = capture_action('woocommerce_edit_account_form_fields');
$form_extra  = capture_action('woocommerce_edit_account_form');
$form_end    = capture_action('woocommerce_edit_account_form_end');
$nonce       = wp_nonce_field('save_account_details', 'save-account-details-nonce', true, false);

\Timber\Timber::render('views/woocommerce/myaccount/form-edit-account.twig', [
    'user'        => $user,
    'form_tag'    => $form_tag,
    'form_start'  => $form_start,
    'form_fields' => $form_fields,
    'form_extra'  => $form_extra,
    'form_end'    => $form_end,
    'nonce'       => $nonce,
]);

do_action('woocommerce_after_edit_account_form');
