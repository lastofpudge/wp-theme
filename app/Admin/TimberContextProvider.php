<?php

namespace App\Admin;

use Timber\Timber;

class TimberContextProvider
{
    public function __construct()
    {
        add_filter('timber/context', [$this, 'register']);
    }

    public function register(array $context): array
    {
        $context['main_menu'] = Timber::get_menu('main_menu');
        $context['site_url']  = home_url('/');
        $context['site_name'] = get_bloginfo('name');
        $context['logo']      = get_custom_logo();

        if (function_exists('pll_the_languages')) {
            $context['languages'] = pll_the_languages(['raw' => 1, 'echo' => 0]);
        }

        if (function_exists('WC') && WC()->cart) {
            $context['cart']            = WC()->cart;
            $context['currency_symbol'] = get_woocommerce_currency_symbol();
            $context['cart_link']       = wc_get_page_permalink('cart');
            $context['checkout_link']   = wc_get_page_permalink('checkout');
            $context['account_link']    = wc_get_page_permalink('myaccount');
        }

        return $context;
    }
}
