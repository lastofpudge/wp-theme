<?php

namespace App\Controllers;

use Timber;
use TimberPost;

class pageController extends Controller
{
    public function __construct()
    {
        $this->returned_data = Timber::get_context();
    }

    // homepage
    public function index()
    {
        $this->returned_data['post'] = new TimberPost();
        //$this->returned_data['p_items'] = carbon_get_post_meta(get_the_ID(), 'p_items');
        return $this->returned_data;
    }

    // about
    public function about()
    {
        $this->returned_data['post'] = new TimberPost();

        return $this->returned_data;
    }

    // list
    public function list()
    {
        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => $paged,
        ];

        query_posts($args);
        $this->returned_data['posts'] = new Timber\PostQuery($args);
        $this->returned_data['pagination'] = Timber::get_pagination();
        $this->returned_data['categories'] = Timber::get_terms('category');

        return $this->returned_data;
    }
}

/*
 * get controller data
 */
$d = new pageController();
