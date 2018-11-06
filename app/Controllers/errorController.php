<?php

namespace App\Controllers;

use Timber;

class errorController extends Controller
{
    public function __construct()
    {
        $returned_data = parent::getData();
    }

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data['context'] = Timber::get_context();

        return $returned_data;
    }
}

$d = new errorController();
