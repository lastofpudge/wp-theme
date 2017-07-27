<?php

namespace App\Admin;

use TimberMenu;

class AdminOptions
{
    public function __construct()
    {
        self::index();
        add_action('after_setup_theme', [$this, 'registerMenus']);
        add_action('wp_enqueue_scripts', [$this, 'ajaxScripts']);
        add_filter('timber_context', [$this, 'addToContext']);
    }

    /*
     * twicks
     */
    public function index()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
    }

    public function ajaxScripts()
    {
        wp_enqueue_script('ajax_forms', get_template_directory_uri().'/assets/dist/js/ajaxForms.js');

        // ajax data prepare
        $ajax_data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ajax-nonce'),
        ];

        // send data to script
        wp_localize_script('ajax_forms', 'vars', $ajax_data);
    }

    /*
     * register menus
     */
    public function registerMenus()
    {
        register_nav_menus([
            'header_menu' => 'Header Menu',
            // 'footer_menu' => 'Footer menu'
        ]);
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
        $context['ver'] = '1.0';

        return $context;
    }
}

new AdminOptions();
