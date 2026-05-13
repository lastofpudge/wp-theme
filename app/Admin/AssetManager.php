<?php

namespace App\Admin;

class AssetManager
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register']);
    }

    public function register(): void
    {
        wp_enqueue_style('app_css', get_theme_file_uri('/resources/style.css'));
        wp_enqueue_style('app_bundle_css', get_theme_file_uri('/resources/assets/dist/css/bundle.min.css'));

        wp_enqueue_script(
            'app',
            get_theme_file_uri('/resources/assets/dist/js/app.min.js'),
            [],
            filemtime(get_theme_file_path('/resources/assets/dist/js/app.min.js'))
        );

        wp_localize_script('app', 'data', [
            'ajax_url'     => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('ajax-nonce'),
            'price_slider' => [
                'currency_symbol'              => html_entity_decode(get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8'),
                'currency_format'              => html_entity_decode(get_woocommerce_price_format(), ENT_QUOTES, 'UTF-8'),
                'currency_format_num_decimals' => wc_get_price_decimals(),
                'currency_format_decimal_sep'  => wc_get_price_decimal_separator(),
                'currency_format_thousand_sep' => wc_get_price_thousand_separator(),
            ],
        ]);
    }
}
