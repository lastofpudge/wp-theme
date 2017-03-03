<?php

namespace Http\Controllers;

use Timber;
use Redux;

class homeController extends Controller
{
    public function __construct()
    {
        $this->prev_next();
    }

    public static function index()
    {
        /*
         * get timber data
         */
        $data = Timber::get_context();
        $data['foo'] = 'it is data!';

        /*
         * get redux data
         */
        global $redux_opt;
        $data['redux_option_example'] = Redux::getOption($redux_opt, 'text-example');

        /*
         * get etc function
         */
        $data['php_function'] = self::getSomeData();
        return $data;
    }

    /*
     * example function
     */
    public static function getSomeData()
    {
        return function () {
            echo 'hello world from function';
        };
    }

    /*
     * remove prev/next links on homepage
     */
    public static function prev_next()
    {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
}

/*
 * get controller data
 */
$d = new homeController();
