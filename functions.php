<?php

use Carbon_Fields\Carbon_Fields;

const APP_PATH = __DIR__.'/app';
const BASE_PATH = __DIR__;

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    @ini_set('display_errors', 1);
}

if (!file_exists(BASE_PATH.'/core/vendor/autoload.php')) {
    wp_die('No "autoload.php" file');
}

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});

require_once BASE_PATH.'/core/vendor/autoload.php';

collect(['core/Autoload', 'core/Setup', 'core/Helpers', 'routes/custom', 'core/PostType', 'core/ShareSlugs'])
->each(function ($file) {
    $file = "/$file.php";
    locate_template($file, true);
});
