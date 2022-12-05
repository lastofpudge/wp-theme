<?php

namespace App\Controllers;

use Timber\Timber;

class PostController extends Controller
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
        $this->data['post'] = Timber::query_post();

        return $this->data;
    }
}
