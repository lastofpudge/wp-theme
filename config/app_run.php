<?php

class AppConfig
{

    public function __construct(array $config)
    {
        $this->config = $config;
        add_action('admin_menu', array($this, 'hide_items'));
        add_action('admin_menu', array($this, 'hide_comments'));
        add_action('admin_menu', array($this, 'hide_tools'));
    }

    /**
     * hide admin posts and pages
     */
    public function hide_items()
    {
        if ($this->config['show_posts'] === false) {
            remove_menu_page('edit.php');
            add_action('wp_before_admin_bar_render', array($this, 'hide_post_add'));
        }

         if ($this->config['show_pages'] === false) {
            remove_menu_page('edit.php?post_type=page');
            add_action('wp_before_admin_bar_render', array($this, 'hide_page_add'));
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
     * hide comments
     */
    public function hide_comments()
    {
        if ($this->config['enable_comments'] === false) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
            remove_menu_page('edit-comments.php');
        }
    }

    public function hide_tools() {
        if ($this->config['show_tools'] === false) {
            remove_menu_page('tools.php');
        }
    }

}

new AppConfig($config);
