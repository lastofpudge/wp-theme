<?php

namespace App\Controllers;

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
        // $returned_data['test_posts'] = Timber::get_posts('post_type=test_postsy&numberposts=4');

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
