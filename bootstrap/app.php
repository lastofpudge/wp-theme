<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;
use App\Extensions\SyncWCPricesPolylang;
use Core\AppConfig;

$configPath = __DIR__.'/../config/admin.php';
$config = include $configPath;

new AppConfig($config);
new AdminOptions();
new HiddenData();

add_action('plugins_loaded', static function () {
    if (function_exists('pll_get_post_translations')) {
        SyncWCPricesPolylang::init();
    }
});
