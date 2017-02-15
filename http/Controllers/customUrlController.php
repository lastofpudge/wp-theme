<?php

namespace Http\Controllers;

use Timber;

class customUrlController extends Controller
{
    /*
     * get data
     */
    public static function index()
    {
        $params = array();
        $params['my_title'] = 'This is my custom title';
        return $params;
    }
}

/*
 * get controller data
 */
$d = new customUrlController();
