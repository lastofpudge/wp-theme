<?php

use Carbon_Fields\Carbon_Fields;

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    @ini_set('display_errors', 1);
}

if (!file_exists(__DIR__.'/core/vendor/autoload.php')) {
    wp_die('No "autoload.php" file');
}

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});

const APP_PATH = __DIR__.'/app';

require_once __DIR__.'/core/vendor/autoload.php';

collect(['core/Autoload', 'core/Setup', 'core/Helpers', 'core/PostType', 'core/ShareSlugs'])
->each(function ($file) {
    $file = "/{$file}.php";

    if (!locate_template($file, true, true)) {
        wp_die(
            sprintf('Error locating <code>%s</code> for inclusion. {$file}')
        );
    }
});
