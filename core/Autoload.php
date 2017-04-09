<?php

$data = [
    'config' => __DIR__ . '/../config/app.php',
    'helpers' => __DIR__ . '/Helpers.php',
    'theme_config' => __DIR__ . '/../config/app.php',
    'post_register_types' => __DIR__ . '/PostType.php',
    'admin_options' => __DIR__ . '/../app/Admin/AdminOptions.php',
    'hidden_wp_data' => __DIR__ . '/../app/Admin/HiddenData.php',
    'carbon_fields_plugin' => __DIR__ . '/modules/carbon-fields/carbon-fields-plugin.php',
    'theme_options' => __DIR__ . '/../app/CarbonFields/OptionFields.php',
    'post_fields' => __DIR__ . '/../app/CarbonFields/PostFields.php',
    'post_types' => __DIR__ . '/../app/Admin/PostTypes.php',
];

foreach ($data as $key => $value) {
   require_once($data[$key]);
}
