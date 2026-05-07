<?php

namespace App\Admin;

class HiddenData
{
    public function __construct()
    {
        $this->removeEmojiActions();
        $this->removeHeadActions();
        $this->removeTheGeneratorTag();
        add_action('wp_enqueue_scripts', [$this, 'removeStyles']);
        add_action('wp_enqueue_scripts', [$this, 'dequeueAssets'], 99);
        add_action('wp_print_scripts', [$this, 'dequeueAssets'], 1);
        add_action('wp_print_styles', [$this, 'dequeueAssets'], 1);
        add_action('enqueue_block_assets', [$this, 'dequeueWooCommerceBlockAssets']);
        add_filter('woocommerce_enqueue_styles', [$this, 'filterWooCommerceStyles']);
    }

    public function removeEmojiActions(): void
    {
        add_filter('emoji_svg_url', '__return_false');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }

    public function removeHeadActions(): void
    {
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'feed_links', 2);
    }

    public function removeTheGeneratorTag(): void
    {
        remove_action('wp_head', 'wp_generator');
    }

    public function removeStyles(): void
    {
        wp_dequeue_style('global-styles');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }

    public function dequeueAssets(): void
    {
        if (is_admin()) {
            return;
        }

        global $wp_scripts;

        if (isset($wp_scripts->registered['jquery'])) {
            $wp_scripts->registered['jquery']->deps = ['jquery-core'];
        }
        wp_dequeue_script('jquery-migrate');
        wp_deregister_script('jquery-migrate');

        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            wp_dequeue_script('payu-gateway');
            wp_dequeue_script('jquery');
            wp_dequeue_script('jquery-core');
            wp_deregister_script('jquery');
            wp_deregister_script('jquery-core');
        }

        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            return;
        }

        foreach (['wc-blocks-style', 'wc-blocks-vendors-style', 'wc-blocks', 'payu-gateway'] as $style) {
            wp_dequeue_style($style);
        }

        foreach ($wp_scripts->queue as $handle) {
            if (str_contains((string) ($wp_scripts->registered[$handle]->src ?? ''), 'plugins/woocommerce')) {
                wp_dequeue_script($handle);
            }
        }
    }

    public function dequeueWooCommerceBlockAssets(): void
    {
        if (is_admin() || is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            return;
        }

        wp_deregister_style('wc-blocks-style');
        wp_dequeue_style('wc-blocks-style');
    }

    public function filterWooCommerceStyles(array $styles): array
    {
        return (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) ? $styles : [];
    }
}
