<?php

namespace App\Controllers;

use Timber;

class postController extends Controller
{
    public function __construct()
    {
        $returned_data = parent::getData();
    }

    /*
     * get post data
     */
    public static function index()
    {
        $post = Timber::query_post();
        $returned_data['post'] = $post;

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new postController();
