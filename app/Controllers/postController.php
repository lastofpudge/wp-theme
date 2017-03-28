<?php

namespace App\Controllers;

use Timber;

class postController extends Controller
{

    use Traits\GlobalData;

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = self::getData();
        $post = Timber::query_post();
        $returned_data['post'] = $post;

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new postController();
