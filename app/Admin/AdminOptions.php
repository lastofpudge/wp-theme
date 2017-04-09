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

 }

new AdminOptions();
