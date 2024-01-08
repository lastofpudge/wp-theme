<?php

namespace App\Controllers;

use Timber\Timber;

class PageController extends Controller
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
        return $this->data;
    }

    public function about(): array
    {
        return $this->data;
    }

    public function list(): array
    {
        global $paged;

        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $this->data['posts'] = Timber::get_posts([
            'post_type' => 'post',
            'posts_per_page' => 10,
            'paged' => $paged,
        ]);

        $this->data['categories'] = Timber::get_terms('category');

        return $this->data;
    }
}
