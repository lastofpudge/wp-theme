<?php

namespace App\Extensions;

use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimberTwigExtensions
{
    public function __construct()
    {
        add_filter('timber/twig', [$this, 'register']);
    }

    public function register(Environment $twig): Environment
    {
        // Price formatting: {{ 1990|wc_price }} → formatted HTML price
        $twig->addFilter(new TwigFilter('wc_price', 'wc_price', ['is_safe' => ['html']]));

        // Raw decimal formatting: {{ '19.9'|wc_format_decimal }}
        $twig->addFilter(new TwigFilter('wc_format_decimal', 'wc_format_decimal'));

        // WC product object from ID: {% set wc = wc_product(post.ID) %}
        $twig->addFunction(new TwigFunction('wc_product', 'wc_get_product'));

        // Currency symbol: {{ wc_currency() }}
        $twig->addFunction(new TwigFunction('wc_currency', 'get_woocommerce_currency_symbol'));

        // WC page permalink: {{ wc_page_url('shop') }}
        $twig->addFunction(new TwigFunction('wc_page_url', 'wc_get_page_permalink'));

        return $twig;
    }
}
