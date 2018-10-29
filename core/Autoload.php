<?php

$data = [
    'wp__helpers'             => __DIR__.'/Helpers.php',
    'wp__config'              => __DIR__.'/../config/app.php',
    'wp__config_run'          => __DIR__.'/../config/app_run.php',
    'wp__post_register_types' => __DIR__.'/PostType.php',
    'wp__admin_options'       => __DIR__.'/../app/Admin/AdminOptions.php',
    'wp__acts'                => __DIR__.'/../app/Actions/ajax.php',
    'wp__langs'               => __DIR__.'/../app/Langs/strings.php',
    'wp__hidden_wp_data'      => __DIR__.'/../app/Admin/HiddenData.php',
    'wp__theme_options'       => __DIR__.'/../app/CarbonFields/OptionFields.php',
    'wp__post_fields'         => __DIR__.'/../app/CarbonFields/PostFields.php',
    'wp__post_types'          => __DIR__.'/../app/Admin/PostTypes.php',
    'wp__cron_tasks'          => __DIR__.'/../app/Jobs/Cron.php',
    'wp__routes'              => __DIR__.'/../routes/api.php',
];

foreach ($data as $key => $value) {
    require_once $data[$key];
}
