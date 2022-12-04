<?php

$data = [
    'helpers'              => __DIR__.'/Helpers.php',
    'config'               => __DIR__.'/../config/app.php',
    'post_register_types'  => __DIR__.'/PostType.php',
    'app_config'           => __DIR__.'/AppConfig.php',
    'admin_options'        => APP_PATH.'/Admin/AdminOptions.php',
    'ajax'                 => APP_PATH.'/Actions/ajax.php',
    'hidden_wp_data'       => APP_PATH.'/Admin/HiddenData.php',
    'theme_options'        => APP_PATH.'/CarbonFields/OptionFields.php',
    'post_fields'          => APP_PATH.'/CarbonFields/PostFields.php',
    //    'post_types'           => APP_PATH.'/Admin/PostTypes.php',
    //    'share_slugs'          => __DIR__.'/ShareSlugs.php',
    //    'trans_strings'        => __DIR__.'/../app/Langs/strings.php',
];

foreach ($data as $key => $value) {
    require_once $value;
}
