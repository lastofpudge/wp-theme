<?php

/*
 * register custom tax
 */

add_action('init', 'create_topics_hierarchical_taxonomy', 0);

/*
 * tax for cpt
 */
function create_topics_hierarchical_taxonomy()
{

  /*
   * [$url, $name, $singular, $public, $has_archive, $menu_icon, $supports] - paramas.
   */

  // PostType::register('uslugi', 'Услуги', 'Услуга', true, true, 'dashicons-format-aside', ['title', 'editor', 'thumbnail']);

   /*
   * Услуги таксономии
   */
  // $labels = array(
  //   'name' => _x('Категории для услуг', 'taxonomy general name'),
  //   'singular_name' => _x('Категорий', 'taxonomy singular name'),
  //   'search_items' =>  __('Поиск'),
  //   'all_items' => __('Все категории'),
  //   'parent_item' => __('Родительская категория'),
  //   'parent_item_colon' => __('Родительская категория:'),
  //   'edit_item' => __('Edit'),
  //   'update_item' => __('Update'),
  //   'add_new_item' => __('Add'),
  //   'new_item_name' => __('New'),
  //   'menu_name' => __('Категории'),
  // );

  // register_taxonomy('usls-cat', array('uslugi'), array(
  //     'hierarchical' => true,
  //     'public' =>  true,
  //     'labels' => $labels,
  //     'show_ui' => true,
  //     'show_in_menu' => true,
  //     'show_in_nav_menus' => true,
  //     'publicly_queryable' => true,
  //     'show_admin_column' => true,
  //     'query_var' => true,
  //   ));

  //   flush_rewrite_rules();
}
