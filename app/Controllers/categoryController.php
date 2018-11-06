<?php

namespace App\Controllers;

use Timber;
use TimberTerm;

class categoryController extends Controller
{
    public function __construct() {
        $returned_data = parent::getData();
    }
    /*
     * get post data
     */
    public static function index()
    {
        $returned_data['term'] = new TimberTerm();
        $returned_data['pagination'] = Timber::get_pagination();
        $returned_data['posts'] = Timber::get_posts();

        return $returned_data;
    }
}

/*
 * get controller data
 */
$d = new categoryController();
