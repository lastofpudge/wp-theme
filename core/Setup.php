<?php

use Carbon_Fields\Carbon_Fields;
use Timber\Timber;

add_action('after_setup_theme', function () {
    Carbon_Fields::boot();
});

add_filter('timber/twig', function (Twig\Environment $twig) {
    $twig->addGlobal('_post', $_POST);
    $twig->addGlobal('_get', $_GET);

    return $twig;
});

new Timber();
