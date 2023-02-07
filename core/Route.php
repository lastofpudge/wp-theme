<?php

namespace Core;

use Timber\Timber;

class Route
{
    /**
     * @uses Route::load()
     */
    public static function load(string $controller, string $method, string $view): void
    {
        $controllerInstance = new $controller();
        $data = $controllerInstance->$method();
        static::renderView($view, $data);
    }

    private static function renderView(string $view, array $data): void
    {
        Timber::render('views/'.$view.'.twig', $data);
    }

    public static function view(string $view): void
    {
        static::renderView($view, []);
    }
}
