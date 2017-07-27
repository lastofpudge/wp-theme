<?php

/*
 * load site controllers
 */
if (!function_exists('makeView')) {
    function makeView($controller, $view)
    {
        $ctr = explode('@', $controller, 2);

        require_once __DIR__.'/../app/Controllers/Controller.php';
        require_once __DIR__.'/../app/Controllers/'.$ctr[0].'.php';

        $data = $d::{$ctr[1]}();

        $v = 'views/'.$view.'.twig';
        Timber::render($v, $data);
        exit;
    }
}

/*
 * helper function die and dump
 */
if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        die(var_dump($data));
        echo '</pre>';
    }
}
