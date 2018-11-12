<?php

namespace App\Controllers;

use Timber;

class postController extends Controller
{
    public function __construct()
    {
        $this->returned_data = Timber::get_context();
    }

    /*
     * get post data
     */
    public function index()
    {
        $this->returned_data['post'] = Timber::query_post();
        return $this->returned_data;
    }
}

/*
 * get controller data
 */
$d = new postController();
