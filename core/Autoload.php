<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;
use App\Admin\PostTypes;

new AdminOptions();
new HiddenData();
new PostTypes();

$data = [
    'setup'               => __DIR__.'/Setup.php',
    'helpers'             => __DIR__.'/Helpers.php',
    'config'              => __DIR__.'/../config/app.php',
    'post_register_types' => __DIR__.'/PostType.php',
    'app_config'          => __DIR__.'/AppConfig.php',
    'ajax'                => APP_PATH.'/Actions/ajax.php',
    'theme_options'       => APP_PATH.'/CarbonFields/OptionFields.php',
    //    'share_slugs'          => __DIR__.'/ShareSlugs.php',
    //    'trans_strings'        => __DIR__.'/../app/Langs/strings.php',
];

foreach ($data as $key => $value) {
    require_once $value;
}
