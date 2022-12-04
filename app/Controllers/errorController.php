<?php

namespace App\Controllers;

use Timber\Timber;

class errorController extends Controller
{
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = Timber::get_context();
    }

    /*
     * get post data
     */
    public function index()
    {
        return $this->data;
    }
}

$d = new errorController();
