<?php

    require_once(__DIR__ . '/core/init.php');
    /*
     * Autoload
     */
    require_once(__DIR__ . '/vendor/autoload.php');
    $timber = new \Timber\Timber();

    require_once(__DIR__ . '/app/Admin/AdminOptions.php');
    require_once(__DIR__ . '/app/Admin/PostTypes.php');

    require_once(__DIR__ . '/core/modules/carbon-fields/carbon-fields-plugin.php');
    require_once(__DIR__ . '/app/CarbonFields/PostFields.php');
    require_once(__DIR__ . '/app/CarbonFields/OptionFields.php');
