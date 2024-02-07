<?php

namespace App\Admin;

use Timber\Timber;

class AdminOptions
{
    public function __construct()
    {
        $this->index();
        $this->manageAdminAccess();
        add_action('init', [$this, 'registerMenus']);
        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
        add_filter('timber/context', [$this, 'registerContext']);
    }

    public function index(): void
    {
        add_theme_support('html5', [
            'caption',
            'comment-form',
            'comment-list',
            'gallery',
            'search-form',
            'script',
            'style'
        ]);
        remove_theme_support('block-templates');
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

        if (!current_user_can('administrator') && is_admin() &!wp_doing_ajax()){
            wp_redirect(home_url());
        }
    }

    public function registerMenus(): void
    {
        register_nav_menus([
            'left_menu' => 'Left menu',
        ]);
    }

    public function registerScripts(): void
    {
        wp_enqueue_style('app_css', get_theme_file_uri('/resources/style.css'));
        wp_enqueue_style('app_bundle_css', get_theme_file_uri('/resources/assets/dist/css/bundle.min.css'));

        wp_enqueue_script(
            'app',
            get_theme_file_uri('/resources/assets/dist/js/app.min.js'),
            [],
            filemtime(get_theme_file_path('/resources/assets/dist/js/app.min.js'))
        );

        wp_localize_script('app', 'data', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
        ]);
    }

    public function registerContext($context): array
    {
        $context['left_menu'] = Timber::get_menu('left_menu');

        return $context;
    }
}
