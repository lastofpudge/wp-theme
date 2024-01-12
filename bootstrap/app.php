<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;
use Core\AppConfig;


$configPath = __DIR__ . '/../config/app.php';
$config = include $configPath;

new AppConfig($config);
new AdminOptions();
new HiddenData();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

