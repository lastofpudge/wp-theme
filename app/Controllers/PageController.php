<?php

namespace App\Controllers;

use Timber\Post as TimberPost;
use Timber\Timber;

class PageController extends Controller
{
    /**
     * @var array
     */
    private array $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = Timber::get_context();
    }

    public function index(): array
    {
        $this->data['post'] = new TimberPost();

        return $this->data;
    }

    public function about(): array
    {
        $this->data['post'] = new TimberPost();

        return $this->data;
    }

    public function list(): array
    {
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => (get_query_var('paged')) ? get_query_var('paged') : 1,
        ];

        query_posts($args);

        $this->data['posts'] = new Timber\PostQuery($args);
        $this->data['pagination'] = Timber::get_pagination();
        $this->data['categories'] = Timber::get_terms('category');

        return $this->data;
    }
}
