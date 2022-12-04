<?php

namespace App\Admin;

use TimberMenu;

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
    }

    public function registerScripts()
    {
        wp_enqueue_script('wp_jquery', get_template_directory_uri().'/assets/dist/js/jquery.js');
        wp_enqueue_script('wp_main', get_template_directory_uri().'/assets/dist/js/wp_main.js', [], filemtime(get_theme_file_path('/assets/dist/js/wp_main.js')));
        wp_enqueue_script('wp_noty', get_template_directory_uri().'/assets/dist/js/noty.js');

        wp_enqueue_style('wp_bundle', get_template_directory_uri().'/assets/dist/css/bundle.css', [], false);
        wp_enqueue_style('wp_plugins', get_template_directory_uri().'/assets/dist/css/plugins.css', [], false);

        wp_localize_script('wp_main', 'data', [
            'ajax_url'  => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('ajax-nonce'),
        ]);
    }

    public function registerMenus()
    {
        register_nav_menus([
            'left_menu' => 'Left menu',
        ]);
    }

    public function registerContext($context)
    {
        $context['left_menu'] = new TimberMenu('left_menu');
        $context['template_url'] = get_bloginfo('template_url');

        return $context;
    }
}

new AdminOptions();
