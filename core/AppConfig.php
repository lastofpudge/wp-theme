<?php

namespace Core;

class AppConfig
{
    /**
     * @var array
     */
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->configureAdminArea();
    }

    public function configureAdminArea()
    {
        add_action('admin_menu', [$this, 'checkAdminBar']);
        add_action('admin_menu', [$this, 'hideAdminItems']);
        add_action('admin_menu', [$this, 'hideAdminComments']);
        add_action('admin_menu', [$this, 'hideAdminTools']);
    }

    public function checkAdminBar()
    {
        if (!$this->config['show_admin_bar']) {
            add_filter('show_admin_bar', '__return_false');
        }
    }

    public function hideAdminItems()
    {
        if (!$this->config['show_posts']) {
            remove_menu_page('edit.php');
            add_action('wp_before_admin_bar_render', [$this, 'hidePostAdd']);
        }

        if (!$this->config['show_pages']) {
            remove_menu_page('edit.php?post_type=page');
        }
    }

    public function hideAdminComments()
    {
        if (!$this->config['enable_comments']) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
            remove_menu_page('edit-comments.php');
        }
    }

    public function hideAdminTools()
    {
        if (!$this->config['show_tools']) {
            remove_menu_page('tools.php');
        }
    }
}

/** @var array $config */
new AppConfig($config);
