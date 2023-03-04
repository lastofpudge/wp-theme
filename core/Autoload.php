<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;

$modules = [
    __DIR__ . '/Helpers.php',
    __DIR__ . '/../config/app.php',
    __DIR__ . '/PostType.php',
    APP_PATH . '/Actions/ajax.php',
    APP_PATH . '/CarbonFields/OptionFields.php',
    APP_PATH . '/CarbonFields/PostFields.php',
    APP_PATH . '/CarbonFields/Blocks.php',
];

foreach ($modules as $module) {
    require $module;
}

/** @var array $config */
new \Core\AppConfig($config);
new AdminOptions();
new HiddenData();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
