<?php

use Carbon_Fields\Carbon_Fields;

const APP_PATH = __DIR__.'/../app';
const BASE_PATH = __DIR__.'/..';

if (!file_exists($composer = BASE_PATH.'/vendor/autoload.php')) {
    wp_die('Error locating autoloader. Please run <code>composer install</code>.');
}

require $composer;

/**
 * Boot Carbon Fields for advanced custom fields.
 */
add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});
