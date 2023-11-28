<?php

namespace App\Controllers;

use Timber\Timber;

class CategoryController extends Controller
{
    /**
     * @var array
     */
    private array $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = Timber::context();
    }

    public function index(): array
    {
        $this->data['term'] = Timber::get_term();
        $this->data['posts'] = Timber::get_posts();

        return $this->data;
    }
}
