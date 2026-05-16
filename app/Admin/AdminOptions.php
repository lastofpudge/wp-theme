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
        add_action('pre_get_posts', [$this, 'filterByCategory']);
        add_action('pre_get_posts', [$this, 'setProductsPerPage']);
        add_action('pre_get_posts', [$this, 'prepareShopArchiveQuery']);
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

    public function setProductsPerPage(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($this->isProductArchiveQuery($query)) {
            $query->set('posts_per_page', 8);
        }
    }

    /**
     * The shop page is a static WordPress page. When paginating it (/page/N/), WordPress
     * sets query var 'page' (for <!--nextpage--> content), not 'paged' (for post archives).
     * WooCommerce product queries rely on 'paged', so page 2+ returns empty and triggers a 404.
     *
     * is_shop() is unreliable at pre_get_posts time because queried_object_id is not resolved
     * yet. Instead we match 'pagename' against all Polylang translations of the shop page slug.
     */
    public function prepareShopArchiveQuery(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query() || !function_exists('wc_get_page_id')) {
            return;
        }

        $page = (int) ($query->query_vars['page'] ?? 0);
        if ($page < 2) {
            return;
        }

        $pagename = (string) ($query->query_vars['pagename'] ?? '');
        if ($pagename === '') {
            return;
        }

        $shopPageId = (int) wc_get_page_id('shop');
        if ($shopPageId < 1) {
            return;
        }

        $ids = function_exists('pll_get_post_translations')
            ? array_values(pll_get_post_translations($shopPageId))
            : [$shopPageId];

        $slugs = array_filter(array_map(
            fn (int $id) => (string) get_post_field('post_name', $id),
            $ids
        ));

        if (!in_array($pagename, $slugs, true)) {
            return;
        }

        $query->set('post_type', 'product');
        $query->set('paged', $page);
        $query->set('page', 0);
        $query->set('pagename', '');
        $query->is_page = false;
        $query->is_archive = true;
        $query->is_post_type_archive = true;
    }

    public function filterByPrice(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        if (!$this->isProductArchiveQuery($query)) {
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

    public function filterByCategory(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        // On product_cat archives the taxonomy constraint is already embedded in the query;
        // adding a second tax_query for a different category yields no results.
        if (!$this->isProductArchiveQuery($query) || $query->is_tax('product_cat')) {
            return;
        }

        $slug = sanitize_text_field(wp_unslash($_GET['filter_product_cat'] ?? ''));

        if ($slug === '') {
            return;
        }

        $tax = (array) $query->get('tax_query');
        $tax[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $slug,
        ];

        $query->set('tax_query', $tax);
    }

    private function isProductArchiveQuery(\WP_Query $query): bool
    {
        if ($query->is_post_type_archive('product')) {
            return true;
        }

        return $query->is_tax($this->getProductTaxonomies());
    }

    private function getProductTaxonomies(): array
    {
        $taxonomies = ['product_cat', 'product_tag', 'product_brand'];

        if (function_exists('wc_get_attribute_taxonomy_names')) {
            $taxonomies = array_merge($taxonomies, wc_get_attribute_taxonomy_names());
        }

        return array_values(array_unique($taxonomies));
    }
}
