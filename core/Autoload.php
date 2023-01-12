<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;

new AdminOptions();
new HiddenData();

$modules = [
    __DIR__.'/Helpers.php',
    __DIR__.'/../config/app.php',
    __DIR__.'/PostType.php',
    __DIR__.'/AppConfig.php',
    APP_PATH.'/Admin/PostTypes.php',
    APP_PATH.'/Actions/ajax.php',
    APP_PATH.'/CarbonFields/OptionFields.php',
];

foreach ($modules as $module) {
    require $module;
}
