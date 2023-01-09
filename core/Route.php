<?php

namespace Core;

use Timber\Timber;

class Route
{
    public static function load($controller, $method, $view)
    {
        $controller = new $controller();

        Timber::render('views/'.$view.'.twig', $controller->$method(), false);
    }

    public static function view($view)
    {
        Timber::render('views/'.$view.'.twig', [], false);
    }
}

