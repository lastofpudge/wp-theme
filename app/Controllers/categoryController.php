<?php

namespace App\Controllers;

use Timber;

class categoryController extends Controller
{

    use Traits\GlobalData;

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = self::getData();
        $returned_data['pagination'] = Timber::get_pagination();
        $returned_data['posts'] = Timber::get_posts();
        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new categoryController();
