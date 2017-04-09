<?php

/**
 * connent views and routes
 * @param $controller
 * @param $view
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

/**
 * helper function die and dump
 * @param  [type] $data
 */
function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}

/**
* Register post types
*/
class PostType
{

    public function register($type, $name, $singular)
    {
    register_post_type($type,
            array(
              'labels' => array(
                'name' => __($name),
                'singular_name' => __($singular)
              ),
              'public' => true,
              'has_archive' => true,
              'menu_icon'   => 'dashicons-format-aside',
              'supports' => array( 'title', 'editor')
            )
        );
    }

    add_action('init', self);

}

new PostType;
