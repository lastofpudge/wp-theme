<?php

    /*
     * Autoload
     */
    if (file_exists(__DIR__ . '/vendor/autoload.php'))
    {
        require_once(__DIR__ . '/vendor/autoload.php');
        new \Timber\Timber();
    }
    else
    {
        wp_die('No "autoload.php" file');
    }

    require_once(__DIR__ . '/core/Helpers.php');
    require_once(__DIR__ . '/core/PostType.php');

    require_once(__DIR__ . '/app/Admin/AdminOptions.php');
    require_once(__DIR__ . '/app/Admin/PostTypes.php');

    require_once(__DIR__ . '/core/modules/carbon-fields/carbon-fields-plugin.php');
    require_once(__DIR__ . '/app/CarbonFields/PostFields.php');
    require_once(__DIR__ . '/app/CarbonFields/OptionFields.php');
