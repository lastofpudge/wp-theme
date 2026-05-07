<?php

namespace App\Admin;

use App\Extensions\TimberTwigExtensions;
use Timber\Timber;

class AdminOptions
{
    public function __construct()
    {
        new TimberTwigExtensions();

        $this->index();
        $this->manageAdminAccess();
        add_action('init', [$this, 'registerMenus']);
        add_action('pre_get_posts', [$this, 'filterByPrice']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
        add_filter('timber/context', [$this, 'registerContext']);
        add_filter('woocommerce_get_myaccount_page_id', 'pll_translate_post_id');
        add_filter('woocommerce_get_cart_page_id', 'pll_translate_post_id');
        add_filter('woocommerce_get_checkout_page_id', 'pll_translate_post_id');
    }

    public function index(): void
    {
        add_theme_support('woocommerce');
        add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form', 'script', 'style']);
        remove_theme_support('block-templates');
        remove_theme_support('core-block-patterns');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('responsive-embeds');
    }

    public function manageAdminAccess(): void
    {
        if (!current_user_can('administrator')) {
            show_admin_bar(false);
        }

        if (!current_user_can('administrator') && is_admin() && !wp_doing_ajax()) {
            wp_safe_redirect(home_url());
            exit;
        }
    }

    public function registerMenus(): void
    {
        register_nav_menus(['main_menu' => 'Main menu']);
    }

    public function registerScripts(): void
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

    public function filterByPrice(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        if (!$query->is_post_type_archive('product') && !$query->is_tax(['product_cat', 'product_tag'])) {
            return;
        }

        $min = get_requested_price('min_price');
        $max = get_requested_price('max_price');
        if ($min === null && $max === null) {
            return;
        }

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $meta = (array) $query->get('meta_query');
        if ($min !== null) {
            $meta[] = ['key' => '_price', 'value' => $min, 'compare' => '>=', 'type' => 'DECIMAL'];
        }
        if ($max !== null) {
            $meta[] = ['key' => '_price', 'value' => $max, 'compare' => '<=', 'type' => 'DECIMAL'];
        }
        $query->set('meta_query', $meta);
    }

    public function registerContext($context): array
    {
        $context['main_menu'] = Timber::get_menu('main_menu');
        $context['site_url'] = home_url('/');
        $context['site_name'] = get_bloginfo('name');
        $context['logo'] = get_custom_logo();

        if (function_exists('pll_the_languages')) {
            $context['languages'] = pll_the_languages(['raw' => 1, 'echo' => 0]);
        }

        if (function_exists('WC') && WC()->cart) {
            $context['cart'] = WC()->cart;
            $context['currency_symbol'] = get_woocommerce_currency_symbol();
            $context['cart_link'] = wc_get_page_permalink('cart');
            $context['checkout_link'] = wc_get_page_permalink('checkout');
            $context['account_link'] = wc_get_page_permalink('myaccount');
        }

        return $context;
    }
}
