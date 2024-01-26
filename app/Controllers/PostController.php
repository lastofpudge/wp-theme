<?php

namespace App\Controllers;

use Timber\Timber;

class PostController extends Controller
{
    /** @var array */
    private array $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = Timber::context();
    }

    public function index(): array
    {
        $this->data['post'] = Timber::get_post();

        return $this->data;
    }
}
