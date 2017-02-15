<?php

namespace Http\Controllers;

use Timber;

class errorController extends Controller
{
    /*
     * get post data
     */
    public static function index()
    {
        $data = Timber::get_context();
        return $data;
    }
}


$d = new errorController();
