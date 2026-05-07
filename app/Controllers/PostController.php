<?php

namespace App\Controllers;

use Timber\Timber;

class PostController extends Controller
{
    public function index(): array
    {
        $this->data['post'] = Timber::get_post();

        return $this->data;
    }
}
