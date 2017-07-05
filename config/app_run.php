<?php

class AppConfig
{
    public function __construct(array $config)
    {
        $this->config = $config;
        self::check_bars();
        add_action('admin_menu', [$this, 'hide_items']);
        add_action('admin_menu', [$this, 'hide_comments']);
        add_action('admin_menu', [$this, 'hide_tools']);
    }

    /**
     * show/hide admin bar
     */
    public function check_bars() {
        if ($this->config['show_admin_bar'] === false) {
            add_filter('show_admin_bar', '__return_false');
        }
    }

    /**
     * hide admin posts and pages.
     */
    public function hide_items()
    {
        if ($this->config['show_posts'] === false) {
            remove_menu_page('edit.php');
            add_action('wp_before_admin_bar_render', [$this, 'hide_post_add']);
        }

        if ($this->config['show_pages'] === false) {
            remove_menu_page('edit.php?post_type=page');
            add_action('wp_before_admin_bar_render', [$this, 'hide_page_add']);
        }
    }

    public function hide_post_add()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('new-post');
    }

    public function hide_page_add()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('new-page');
    }

    /**
     * hide comments.
     */
    public function hide_comments()
    {
        if ($this->config['enable_comments'] === false) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
            remove_menu_page('edit-comments.php');
        }
    }

    public function hide_tools()
    {
        if ($this->config['show_tools'] === false) {
            remove_menu_page('tools.php');
        }
    }
}

new AppConfig($config);
