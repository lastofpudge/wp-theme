<?php

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    @ini_set('display_errors', 1);
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/
if (! file_exists($composer = __DIR__ . '/core/vendor/autoload.php')) {
    wp_die('Error locating autoloader. Please run <code>composer install</code>.');
}

require $composer;

/*
|--------------------------------------------------------------------------
| Useful variables
|--------------------------------------------------------------------------
*/
define('MAIL_CONFIG', __DIR__.'/config/mail.php');
define('APP_PATH', __DIR__.'/app');


/*
|--------------------------------------------------------------------------
| Register Theme Files
|--------------------------------------------------------------------------
*/
collect(['core/Autoload', 'core/Setup', 'core/Helpers', 'core/PostType', 'core/ShareSlugs'])
    ->each(function ($file) {
        $file = "/{$file}.php";

        if (!locate_template($file, true, true)) {
            wp_die(
                sprintf('Error locating <code>%s</code> for inclusion. {$file}')
            );
        }
    });
