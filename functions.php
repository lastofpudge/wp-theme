<?php

    /**
     * Autload.
     */
    if (!file_exists(__DIR__.'/core/vendor/autoload.php')) {
        wp_die('No "autoload.php" file');
    }

    require_once __DIR__.'/core/vendor/autoload.php';
    require_once __DIR__.'/core/Autoload.php';

    new \Timber\Timber();
