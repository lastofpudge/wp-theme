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
}
