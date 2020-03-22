<?php

$data = [
    'helpers'             => __DIR__.'/Helpers.php',
    'config'              => __DIR__.'/../config/app.php',
    'post_register_types' => __DIR__.'/PostType.php',
    'admin_options'       => __DIR__.'/../app/Admin/AdminOptions.php',
    'ajaxs'               => __DIR__.'/../app/Actions/ajax.php',
    'hidden_wp_data'      => __DIR__.'/../app/Admin/HiddenData.php',
    'theme_options'       => __DIR__.'/../app/CarbonFields/OptionFields.php',
    'post_fields'         => __DIR__.'/../app/CarbonFields/PostFields.php',
    'post_types'          => __DIR__.'/../app/Admin/PostTypes.php',
    'share_slugs'         => __DIR__.'/ShareSlugs.php',
];

foreach ($data as $key => $value) {
    require_once $data[$key];
}
