<?php

defined('ABSPATH') || exit;

$reset_link = add_query_arg(
    ['key' => $reset_key, 'id' => $user_id, 'login' => rawurlencode($user_login)],
    wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))
);

echo \Timber\Timber::compile(
    'views/woocommerce/emails/customer-reset-password.twig',
    wc_email_frame_context($email_heading ?? '', $email ?? null, $additional_content ?? '') + [
        'user_login' => $user_login,
        'blogname'   => $blogname,
        'reset_link' => esc_url($reset_link),
    ]
);
