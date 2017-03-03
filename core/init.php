<?php

/*
 * load site controllers
 */
function makeView($controller, $view)
{
    /*
     * explode classname and method
     */
    $ctr = explode("@", $controller, 2);

    require_once __DIR__. "/../app/Controllers/Controller.php";
    require_once __DIR__."/../app/Controllers/" .$ctr[0]. ".php";

    $data = $d::{$ctr[1]}();

    /*
    * render views
    */
    $v = 'views/'.$view.'.twig';
    Timber::render($v, $data);
}

/*
 * helper function die and dump
 */
function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}
