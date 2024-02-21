<?php

namespace App\Controllers;

class Controller
{
    public function __construct()
    {
        add_filter('timber/context', function ($context) {
            $context['cart'] = WC()->cart;
            $context['currency_symbol'] = get_woocommerce_currency_symbol();

            return $context;
        });
    }
}
