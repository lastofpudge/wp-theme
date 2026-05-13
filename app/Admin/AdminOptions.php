<?php

namespace App\Admin;

use App\Extensions\TimberTwigExtensions;

class AdminOptions
{
    public function __construct()
    {
        new TimberTwigExtensions();
        new AssetManager();
        new TimberContextProvider();

        $this->index();
        $this->manageAdminAccess();
        add_action('init', [$this, 'registerMenus']);
        add_action('pre_get_posts', [$this, 'filterByPrice']);
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
}
