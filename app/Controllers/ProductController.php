<?php

namespace App\Controllers;

use Timber\Timber;

class ProductController extends Controller
{
    public function index(): array
    {
        $id = get_the_ID();
        $product = wc_get_product($id);

        $this->data['product'] = $product;
        $this->data['post'] = Timber::get_post();
        $this->data['categories'] = get_the_terms($id, 'product_cat');

        $related_limit = wc_get_loop_prop('columns');
        $related_ids = wc_get_related_products($id, $related_limit);
        $this->data['related_products'] = Timber::get_posts($related_ids);

        $gallery_ids = array_filter(array_merge(
            [$product->get_image_id()],
            $product->get_gallery_image_ids()
        ));
        $this->data['gallery'] = array_values(array_map(function (int $img_id) {
            return [
                'full'  => wp_get_attachment_image_url($img_id, 'full'),
                'thumb' => wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail'),
                'alt'   => get_post_meta($img_id, '_wp_attachment_image_alt', true),
            ];
        }, $gallery_ids));

        $this->data['description'] = $product->get_description();

        $product_attributes = [];
        foreach ($product->get_attributes() as $attribute) {
            if (!$attribute->get_visible()) {
                continue;
            }
            if ($attribute->is_taxonomy()) {
                $terms = wp_get_post_terms($id, $attribute->get_name());
                $values = is_wp_error($terms) ? [] : array_column($terms, 'name');
            } else {
                $values = $attribute->get_options();
            }
            $product_attributes[] = [
                'label'  => wc_attribute_label($attribute->get_name()),
                'values' => $values,
            ];
        }
        $this->data['product_attributes'] = $product_attributes;

        $this->data['reviews_open'] = comments_open($id);
        $this->data['reviews_count'] = (int) $product->get_review_count();
        ob_start();
        comments_template();
        $this->data['reviews_html'] = ob_get_clean();

        $upsell_ids = $product->get_upsell_ids();
        $this->data['upsells'] = !empty($upsell_ids)
            ? Timber::get_posts(array_slice($upsell_ids, 0, 4))
            : [];

        return $this->data;
    }
}
