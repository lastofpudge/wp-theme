<?php

namespace App\Controllers;

use Timber;

class errorController extends Controller
{

    use Traits\GlobalData;

    /*
     * get post data
     */
    public static function index()
    {
        $returned_data = self::getData();
        $returned_data = Timber::get_context();

        return $returned_data;
    }
}


$d = new errorController();
