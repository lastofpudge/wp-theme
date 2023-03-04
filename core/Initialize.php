<?php

use App\Admin\AdminOptions;
use App\Admin\HiddenData;

require  __DIR__ . '/../config/app.php';

/** @var array $config */
new \Core\AppConfig($config);
new AdminOptions();
new HiddenData();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
