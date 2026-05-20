<?php

defined('ABSPATH') || exit;

echo \Timber\Timber::compile(
    'views/woocommerce/emails/customer-new-account.twig',
    wc_email_frame_context($email_heading ?? '', $email ?? null, $additional_content ?? '') + [
        'user_login'         => $user_login,
        'blogname'           => $blogname,
        'password_generated' => $password_generated,
        'set_password_url'   => $set_password_url ?? '',
        'myaccount_url'      => wc_get_page_permalink('myaccount'),
    ]
);
