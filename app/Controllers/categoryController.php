<?php

namespace App\Controllers;

use Timber;

class categoryController extends Controller
{
    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = parent::getData();
        $returned_data['pagination'] = Timber::get_pagination();
        $returned_data['posts'] = Timber::get_posts();

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new categoryController();
