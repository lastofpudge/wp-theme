<?php

namespace App\Controllers;

use Timber;
use TimberTerm;

class categoryController extends Controller
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
        $this->returned_data['term'] = new TimberTerm();
        $this->returned_data['pagination'] = Timber::get_pagination();
        $this->returned_data['posts'] = Timber::get_posts();

        return $this->returned_data;
    }
}

/*
 * get controller data
 */
$d = new categoryController();
