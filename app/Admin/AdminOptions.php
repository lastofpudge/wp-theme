<?php

namespace App\Admin;

use TimberMenu;

class AdminOptions
{
    public function __construct()
    {
        self::index();
        add_action('init', [$this, 'registerStuff']);
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
        add_theme_support('custom-logo');
    }

    public function ajaxScripts()
    {
        wp_enqueue_script('ajax_forms', get_template_directory_uri().'/assets/dist/js/ajaxForms.js');
        // wp_enqueue_script('noty', get_template_directory_uri().'/assets/dist/js/noty.js');

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
    public function registerStuff()
    {
        // flush_rewrite_rules();

        //register menus
        register_nav_menus([
            'left_menu' => 'Меню слева',
            // 'right_menu' => 'Меню справа'
        ]);
    }

    /*
     * add menus to timber
     */
    public function addToContext($context)
    {
        $context['left_menu'] = new TimberMenu('left_menu');
        // $context['right_menu'] = new TimberMenu('right_menu');

        /*
         * assets version
         */
        $context['ver'] = '1.0';

        return $context;
    }
}

new AdminOptions();
