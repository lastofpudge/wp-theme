<?php

namespace App\Admin;

use TimberMenu;

class AdminOptions
{
    public function __construct()
    {
        self::index();
        add_action('after_setup_theme', array( $this, 'registerMenus' ));
        add_filter('timber_context', array( $this, 'addToContext' ));
        add_filter( 'emoji_svg_url', '__return_false' );
        add_action('init', array( $this, 'unusedFeatures' ));
        // add_action('admin_menu', array( $this, 'unusedAdminFeatures' ));
    }

    /*
     * twicks
     */
    public function index()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
    }

    /*
     * register menus
     */
    public function registerMenus()
    {
        register_nav_menus(array(
            'header_menu' => 'Header Menu',
            // 'footer_menu' => 'Footer menu'
        ));
    }

    /*
     * add menus to timber
     */
    public function addToContext($context)
    {
        $context['header_menu'] = new TimberMenu();

        /*
         * assets version
         */
        $context['version'] = '1';
        return $context;
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
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_menu_page('edit-comments.php');
    }

}

new AdminOptions();
