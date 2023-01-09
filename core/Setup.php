<?php

use Timber\Timber;

add_filter('timber/twig', function (Twig\Environment $twig) {
    $twig->addGlobal('_post', $_POST);
    $twig->addGlobal('_get', $_GET);

    return $twig;
});

new Timber();
