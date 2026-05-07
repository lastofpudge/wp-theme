<?php

namespace App\Controllers;

use Timber\Timber;

class CategoryController extends Controller
{
    public function index(): array
    {
        $this->data['term']  = Timber::get_term();
        $this->data['posts'] = Timber::get_posts();

        return $this->data;
    }
}
