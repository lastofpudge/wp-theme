<?php

namespace Core;

use Timber\Timber;

class Route
{
    /**
     * Load a controller method and render the associated view.
     *
     * @param string $controller The fully qualified class name of the controller.
     * @param string $method The method to call on the controller.
     * @param string $view The view file to render.
     *
     */
    public static function load(string $controller, string $method, string $view): void
    {
        if (!class_exists($controller) || !method_exists($controller, $method)) {
            return;
        }

        $controllerInstance = new $controller();
        $data = $controllerInstance->$method();

        static::renderView($view, $data);
    }

    /**
     * Render a view with Timber.
     *
     * @param string $view The view file to render.
     * @param array $data The data to pass to the view.
     */
    private static function renderView(string $view, array $data): void
    {
        Timber::render('views/' . $view . '.twig', $data);
    }

    /**
     * Render a view without a controller.
     *
     * @param string $view The view file to render.
     */
    public static function view(string $view): void
    {
        static::renderView($view, []);
    }
}
