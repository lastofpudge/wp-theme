<?php

namespace App\Controllers;

use Timber;

class postController extends Controller
{
    public function __construct() {
        $this->returned_data = Timber::get_context();
    }
    /*
     * get post data
     */
    public function index()
    {
        $post = Timber::query_post();
        $this->returned_data['post'] = $post;

        return $this->returned_data;
    }
}

/*
 * get controller data
 */
$d = new postController();
