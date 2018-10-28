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

        /*
         * Custom paginated page
         */
        // if (is_page_template('page-list.php'))
        // {
        //     global $paged;

        //     if (!isset($paged) || !$paged){ $paged = 1; }

        //     $context = Timber::get_context();

        //     $args = array(
        //       'post_type' => 'post',
        //       'posts_per_page' => 10,
        //       'paged' => $paged
        //     );

        //     query_posts($args);
        //     $returned_data['posts'] = new Timber\PostQuery($args);
        //     $returned_data['pagination'] = Timber::get_pagination();
        //     $returned_data['categories'] = Timber::get_terms('category');
        // }

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
