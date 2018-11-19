<?php

/*
 * register custom tax
 */
add_action('init', function () {

  /*
   * [$url, $name, $singular, $public, $has_archive, $menu_icon, $supports] - paramas.
   */
  // PostType::register('uslugi', 'Услуги', 'Услуга', true, true, 'dashicons-format-aside', ['title', 'editor', 'thumbnail']);

  /*
   * [$post_Type, $post_tax, $tax_cat_name] - paramas.
   */
  // PostType::loadtax('uslugi', 'uslugi_cat', 'Категории');
  //   flush_rewrite_rules();


}, 0);
