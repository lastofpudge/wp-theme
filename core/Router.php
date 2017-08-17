<?php

/**
 * Custom router.
 */
class Router
{
    public function Home($controller, $page)
    {
        if (is_front_page()) : makeView($controller, $page);
        endif;
    }

    public static function Category($controller, $page)
    {
    }

    public static function CustomPostTypeCategory($category, $controller, $page)
    {
        if (is_post_type_archive($category)) : makeView($controller, $page);
        endif;
    }

    public static function Single($controller, $page)
    {
    }

    public static function Page($controller, $page)
    {
    }

    public static function Error($controller, $page)
    {
    }
}

$router = new Router();
