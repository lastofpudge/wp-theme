<?php

namespace App\Controllers;

use Timber\Timber;

class ErrorController extends Controller
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
    public function index(): array
    {
        return $this->data;
    }
}
