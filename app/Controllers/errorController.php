<?php

namespace App\Controllers;

use Timber;

class errorController extends Controller
{
    public function __construct() {
        $this->returned_data = Timber::get_context();
    }
    /*
     * get post data
     */
    public function index()
    {
        return $this->returned_data;
    }
}

$d = new errorController();
