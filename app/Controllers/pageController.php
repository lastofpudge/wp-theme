<?php

namespace App\Controllers;

use Timber;
use TimberPost;

class pageController extends Controller
{

    use Traits\GlobalData;

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = self::getData();
        $post = new TimberPost();
        $returned_data['post'] = $post;

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
