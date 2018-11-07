<?php

$data = [
    'wpt__helpers'             => __DIR__.'/Helpers.php',
    'wpt__config'              => __DIR__.'/../config/app.php',
    'wpt__config_run'          => __DIR__.'/AppConfig.php',
    'wpt__post_register_types' => __DIR__.'/PostType.php',
    'wpt__admin_options'       => __DIR__.'/../app/Admin/AdminOptions.php',
    'wpt__acts'                => __DIR__.'/../app/Actions/ajax.php',
    'wpt__langs'               => __DIR__.'/../app/Langs/strings.php',
    'wpt__hidden_wp_data'      => __DIR__.'/../app/Admin/HiddenData.php',
    'wpt__theme_options'       => __DIR__.'/../app/CarbonFields/OptionFields.php',
    'wpt__post_fields'         => __DIR__.'/../app/CarbonFields/PostFields.php',
    'wpt__post_types'          => __DIR__.'/../app/Admin/PostTypes.php',
    'wpt__cron_tasks'          => __DIR__.'/../app/Jobs/Cron.php',
    'wpt__routes'              => __DIR__.'/../routes/api.php',
];

foreach ($data as $key => $value) {
    require_once $data[$key];
}
