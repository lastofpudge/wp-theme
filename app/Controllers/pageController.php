<?php

namespace Http\Controllers;

use Timber;
use TimberPost;

class pageController extends Controller
{
    /*
     * get post data
     */
    public static function index()
    {
        $data = Timber::get_context();
        $post = new TimberPost();
        $data['post'] = $post;
        return $data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
