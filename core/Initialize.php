<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;
use Core\AppConfig;

require  __DIR__ . '/../config/app.php';

/** @var array $config */
new AppConfig($config);
new AdminOptions();
new HiddenData();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
