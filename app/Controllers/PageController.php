<?php

namespace App\Controllers;

use Timber\Timber;

class PageController extends Controller
{
    public function index(): array
    {
        // Bestsellers
        $this->data['bestsellers'] = Timber::get_posts([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'meta_query'     => [[
                'key'   => '_stock_status',
                'value' => 'instock',
            ]],
        ]);

        // New arrivals
        $this->data['new_arrivals'] = Timber::get_posts([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        // On sale
        $on_sale_ids = wc_get_product_ids_on_sale();
        $this->data['on_sale'] = !empty($on_sale_ids) ? Timber::get_posts([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post__in'       => $on_sale_ids,
            'orderby'        => 'rand',
        ]) : [];

        // Featured
        $this->data['featured'] = Timber::get_posts([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'tax_query'      => [[
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ]],
        ]);

        // Top rated
        $this->data['top_rated'] = Timber::get_posts([
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'meta_key'       => '_wc_average_rating',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'meta_query'     => [[
                'key'     => '_wc_average_rating',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'DECIMAL',
            ]],
        ]);

        return $this->data;
    }

    public function page(): array
    {
        $this->data['post'] = Timber::get_post();

        return $this->data;
    }

    public function about(): array
    {
        return $this->data;
    }

    public function account(): array
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
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => $paged,
        ]);

        $this->data['categories'] = Timber::get_terms('category');

        return $this->data;
    }
}
