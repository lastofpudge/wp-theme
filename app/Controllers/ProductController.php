<?php

namespace App\Controllers;

use Timber\Timber;

class ProductController extends Controller
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
        $this->data['product'] = wc_get_product($this->data['post']->ID);

        $related_limit = wc_get_loop_prop('columns');
        $related_ids = wc_get_related_products($this->data['post']->id, $related_limit);
        $this->data['related_products'] = Timber::get_posts($related_ids);

        return $this->data;
    }

    public function category(): array
    {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $this->data['category'] = get_term($term_id, 'product_cat');
        $this->data['title'] = single_term_title('', false);

        return $this->data;
    }
}
