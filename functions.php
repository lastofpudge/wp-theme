<?php

    /**
     * Autload
     */
    if (!file_exists(__DIR__ . '/vendor/autoload.php'))
    {
        wp_die('No "autoload.php" file');
    }

    require_once(__DIR__ . '/vendor/autoload.php');
    require_once(__DIR__ . '/core/autoload.php');

    new \Timber\Timber();
