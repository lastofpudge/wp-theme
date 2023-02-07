<?php

use Carbon_Fields\Carbon_Fields;

const APP_PATH = __DIR__ . '/../app';
const BASE_PATH = __DIR__ . '/..';

if (!file_exists(BASE_PATH . '/core/vendor/autoload.php') && !is_admin()) {
    wp_die('No autoload file');
}

require BASE_PATH . '/core/vendor/autoload.php';
require BASE_PATH . '/core/Autoload.php';

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});
