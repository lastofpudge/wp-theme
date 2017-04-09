<?php

// namespace App\Admin;

class HiddenData
{
    public function __construct()
    {
        add_filter( 'emoji_svg_url', '__return_false' );
        add_action('init', array( $this, 'unusedFeatures' ));
        add_action('admin_menu', array( $this, 'unusedAdminFeatures' ));
    }

   /**
     * [unusedFeatures] Hide unused features
     */
    public function unusedFeatures()
    {
        // hide emoji
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

        remove_action ('wp_head', 'rsd_link');
        remove_action( 'wp_head', 'wlwmanifest_link');
        remove_action( 'wp_head','rest_output_link_wp_head');

        // hide wp generator
        add_filter('the_generator', function() { return ''; });

        // unregister_taxonomy_for_object_type('post_tag', 'post');
        // remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }

    /**
     * [unusedAdminFeatures ] Hide unused pages, boxes *(comments)
     */
    public function unusedAdminFeatures()
    {
        // remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        // remove_menu_page('edit-comments.php');
    }
}

new HiddenData();
