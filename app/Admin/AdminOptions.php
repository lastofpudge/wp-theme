<?php

namespace App\Admin;

use Timber\Menu;

class AdminOptions
{
    public function __construct()
    {
        self::index();
        add_action('init', [$this, 'registerMenus']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
        add_filter('timber_context', [$this, 'registerContext']);
    }

    public function index()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('responsive-embeds');
    }

    public function registerMenus()
    {
        register_nav_menus([
            'left_menu' => 'Left menu',
        ]);
    }

    public function registerScripts()
    {
        wp_enqueue_script(
            'wp_main',
            get_theme_file_uri('/assets/dist/js/wp_main.min.js'),
            [],
            filemtime(get_theme_file_path('/assets/dist/js/wp_main.min.js')),
        );

        wp_enqueue_style('wp_bundle_css', get_theme_file_uri('/assets/dist/css/bundle.min.css'), [], false);

        wp_localize_script('wp_main', 'data', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
        ]);
    }

    public function registerContext($context)
    {
        $context['left_menu'] = new Menu('left_menu');

        return $context;
    }
}
