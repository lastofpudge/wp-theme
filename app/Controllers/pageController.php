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

        // if (is_page_template('page-home.php')) {
        //        $returned_data['field_1'] = carbon_get_post_meta(get_the_ID(), 'field_1');
        // }
        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
