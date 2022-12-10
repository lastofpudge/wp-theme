<?php

namespace Core;

class AppConfig
{
  /**
   * @var array
   */
  private $config;

  public function __construct(array $config)
  {
    $this->config = $config;
    self::checkBars();
    add_action("admin_menu", [$this, "hideItems"]);
    add_action("admin_menu", [$this, "hideComments"]);
    add_action("admin_menu", [$this, "hideTools"]);
  }

  /**
   * show/hide admin bar.
   */
  public function checkBars()
  {
    if ($this->config["show_admin_bar"] === false) {
      add_filter("show_admin_bar", "__return_false");
    }
  }

  /**
   * hide admin posts and pages.
   */
  public function hideItems()
  {
    if ($this->config["show_posts"] === false) {
      remove_menu_page("edit.php");
      add_action("wp_before_admin_bar_render", [$this, "hidePostAdd"]);
    }

    if ($this->config["show_pages"] === false) {
      remove_menu_page("edit.php?post_type=page");
    }
  }

  public function hidePostAdd()
  {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu("new-post");
  }

  /**
   * hide comments.
   */
  public function hideComments()
  {
    if ($this->config["enable_comments"] === false) {
      remove_action("admin_bar_menu", "wp_admin_bar_comments_menu", 60);
      remove_meta_box("dashboard_recent_comments", "dashboard", "normal");
      remove_menu_page("edit-comments.php");
    }
  }

  public function hideTools()
  {
    if ($this->config["show_tools"] === false) {
      remove_menu_page("tools.php");
    }
  }
}

/** @var array $config */
new AppConfig($config);
