<?php

use Carbon_Fields\Carbon_Fields;

const APP_PATH = __DIR__ . '/app';
const BASE_PATH = __DIR__;

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    @ini_set('display_errors', 1);
}

if (!file_exists(BASE_PATH . '/core/vendor/autoload.php')) {
    if (!is_admin()) {
        wp_die('No autoload file');
    }
}

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});

require BASE_PATH . '/core/vendor/autoload.php';
require BASE_PATH . '/core/Autoload.php';
