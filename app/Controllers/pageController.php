<?php

namespace App\Controllers;

use Timber;
use TimberPost;

class pageController extends Controller
{

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = parent::getData();
        $post = new TimberPost();
        $returned_data['post'] = $post;

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
