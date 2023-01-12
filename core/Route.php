<?php

namespace Core;

use Timber\Timber;

class Route
{
    public static function load(string $controller, string $method, string $view)
    {
        $controller = new $controller();

        Timber::render('views/'.$view.'.twig', $controller->$method(), false);
    }

    public static function view(string $view)
    {
        Timber::render('views/'.$view.'.twig', [], false);
    }
}

