<?php

namespace App\Controllers;

use Timber\Post as TimberPost;
use Timber\Timber;

class PageController extends Controller
{
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = Timber::get_context();
    }

    public function index(): array
    {
        $this->data['post'] = new TimberPost();
        //$this->data['p_items'] = carbon_get_post_meta(get_the_ID(), 'p_items');
        return $this->data;
    }

    // about
    public function about(): array
    {
        $this->data['post'] = new TimberPost();

        return $this->data;
    }

    // list
    public function list(): array
    {
        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => $paged,
        ];

        query_posts($args);

        $this->data['posts'] = new Timber\PostQuery($args);
        $this->data['pagination'] = Timber::get_pagination();
        $this->data['categories'] = Timber::get_terms('category');

        return $this->data;
    }
}
