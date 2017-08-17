<?php

    /**
     * Autoload.
     */
    if (!file_exists(__DIR__.'/core/vendor/autoload.php')) {
        wp_die('No "autoload.php" file');
    }

    add_action( 'after_setup_theme', function() { \Carbon_Fields\Carbon_Fields::boot(); } );

    require_once __DIR__.'/core/vendor/autoload.php';
    require_once __DIR__.'/core/Autoload.php';

    new \Timber\Timber();
