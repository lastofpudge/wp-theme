<?php

namespace App\Controllers;

use Timber\Timber;

class ProductController extends Controller
{
    /** @var array */
    protected array $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = Timber::context();
    }

    public function index(): array
    {
        $this->data['product'] = wc_get_product(get_the_ID());
        $this->data['post'] = Timber::get_post();
        $this->data['categories'] = get_the_terms(get_the_ID(), 'product_cat');

        $related_limit = wc_get_loop_prop('columns');
        $related_ids = wc_get_related_products(get_the_ID(), $related_limit);
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

    public function archive(): array
    {
        $this->data['attributes'] = array_map(fn ($taxonomy) => [
            'label' => $taxonomy->attribute_label,
            'name' => $taxonomy->attribute_name,
            'terms' => get_terms([
                    'taxonomy' => 'pa_' . $taxonomy->attribute_name,
                    'hide_empty' => false,
                ]) ?? [],
        ], wc_get_attribute_taxonomies());

        return $this->data;
    }

    public function cart(): array
    {
        $this->data['coupons_enabled'] = wc_coupons_enabled();

        return $this->data;
    }
}
