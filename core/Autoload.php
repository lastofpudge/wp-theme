<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;
use App\Admin\PostTypes;

new AdminOptions();
new HiddenData();
new PostTypes();

$modules = [
    __DIR__.'/Setup.php',
    __DIR__.'/Helpers.php',
    __DIR__.'/../config/app.php',
    __DIR__.'/PostType.php',
    __DIR__.'/AppConfig.php',
    APP_PATH.'/Actions/ajax.php',
    APP_PATH.'/CarbonFields/OptionFields.php',
];

foreach ($modules as $module) {
    require $module;
}
