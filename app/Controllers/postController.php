<?php

namespace App\Controllers;

use Timber;

class postController extends Controller
{
    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = parent::getData();
        $post = Timber::query_post();
        $returned_data['post'] = $post;

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new postController();
