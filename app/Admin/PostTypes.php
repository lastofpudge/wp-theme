<?php

/*
 * register custom tax
 */
add_action('init', function () {

  /*
   * [$url, $name, $singular, $public, $has_archive, $menu_icon, $supports] - params.
   */
  // PostType::register('services', 'Services', 'Service', true, true, 'dashicons-format-aside', ['title', 'editor', 'thumbnail']);

  /*
   * [$post_Type, $post_tax, $tax_cat_name] - params.
   */
  // PostType::loadTax('services', 'services_cat', 'Category');
  //   flush_rewrite_rules();
}, 0);
