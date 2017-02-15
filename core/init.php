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

    require_once __DIR__. "/../http/Controllers/Controller.php";
    require_once __DIR__."/../http/Controllers/" .$ctr[0]. ".php";

    $data = $d::{$ctr[1]}();

    /*
    * render views
    */
    $v = 'views/'.$view.'.twig';
    Timber::render($v, $data);
}

/*
 * custom routes
 */
require_once(__DIR__ . '/../routes/custom.php');

/*
 * custom modules
 */
require_once(__DIR__ . '/modules/redux-framework/ReduxCore/framework.php');
require_once(__DIR__ . '/../http/Admin/AdminOptions.php');
