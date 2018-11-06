<?php

namespace App\Controllers;

use Timber;
use TimberPost;

class pageController extends Controller
{
    // homepage
    public static function index()
    {
        $post = new TimberPost();

        $returned_data = parent::getData();
        $returned_data['post'] = $post;

        return $returned_data;
    }

    // about
    public static function about()
    {
        $post = new TimberPost();
        $returned_data = parent::getData();
        $returned_data['post'] = $post;

        return $returned_data;
    }

    // list
    public static function list()
    {
        $returned_data = parent::getData();

        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $context = Timber::get_context();
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => $paged,
        ];

        query_posts($args);
        $returned_data['posts'] = new Timber\PostQuery($args);
        $returned_data['pagination'] = Timber::get_pagination();
        $returned_data['categories'] = Timber::get_terms('category');

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
