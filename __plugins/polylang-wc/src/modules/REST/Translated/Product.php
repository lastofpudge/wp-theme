<?php

/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translated;

use WC_Product;
use PLL_REST_API;
use PLLWC_Data_Store;
use PLLWC_Product_Language_CPT;
use WP_Syntex\Polylang_Pro\REST\Translated\Post;
use WP_REST_Response;
use WP_REST_Request;

/**
 * Exposes the product language and translations in the REST API.
 *
 * @since 2.2
 */
class Product extends Post
{
    use Batch;

    /**
     * Product language data store.
     *
     * @var PLLWC_Product_Language_CPT
     */
    private $data_store;

    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
     */
    public function __construct(PLL_REST_API $rest_api)
    {
        $post_types = array( 'product', 'product_variation' );

        parent::__construct($rest_api, $post_types);

        $this->data_store = PLLWC_Data_Store::load('product_language');

        foreach ($post_types as $post_type) {
            add_filter("woocommerce_rest_prepare_{$post_type}_object", array( $this, 'prepare_response' ), 10, 3);
        }

        add_filter('pllwc_language_for_unique_sku', array( $this, 'filter_language_with_request' ));
        add_filter('pllwc_language_for_lock_on_sku', array( $this, 'filter_language_with_request' ));
        add_filter('pllwc_language_for_global_unique_id', array( $this, 'filter_language_with_request' ));

        // Init batch support.
        add_filter('rest_pre_dispatch', array( $this, 'init_batch_lang_queue' ), 10, 3);
        add_action('woocommerce_after_product_object_save', array( $this, 'next_batch_item' ));
    }

    /**
     * Checks if this handler should process the given batch route.
     *
     * @since 2.2
     *
     * @param string $route The REST route being requested.
     * @return bool True if this is a products' batch request.
     */
    protected function is_batch_route(string $route): bool
    {
        return (bool) preg_match('#/wc/v[23]/products/batch#', $route);
    }

    /**
     * Returns the object language.
     *
     * @since 2.2
     *
     * @param array $object Product array.
     * @return string|false
     */
    public function get_language($object)
    {
        return $this->data_store->get_language($object['id']);
    }

    /**
     * Sets the object language.
     *
     * @since 2.2
     *
     * @param string     $lang   Language code.
     * @param WC_Product $object Instance of `WC_Product`.
     * @return bool True when successfully assigned. False otherwise (or if the given language is already assigned to
     *              the object).
     */
    public function set_language($lang, $object)
    {
        if ($object instanceof WC_Product) {
            return $this->data_store->set_language($object->get_id(), $lang);
        }

        return parent::set_language($lang, $object);
    }

    /**
     * Returns the object translations.
     *
     * @since 2.2
     *
     * @param array $object Product array.
     * @return array An array of translation IDs with language codes as keys.
     */
    public function get_translations($object)
    {
        return $this->data_store->get_translations($object['id']);
    }

    /**
     * Save the translations.
     *
     * @since 2.2
     *
     * @param int[]      $translations Array of translations with language codes as keys and object ids as values.
     * @param WC_Product $object       Instance of `WC_Product`.
     * @return bool True when successfully saved. False otherwise.
     */
    public function save_translations($translations, $object)
    {
        if (! $object instanceof WC_Product) {
            return parent::save_translations($translations, $object);
        }

        $language_slug = $this->data_store->get_language($object->get_id());

        if (! $language_slug) {
            return false;
        }

        $expected = array_merge(
            array( $language_slug => $object->get_id() ),
            $translations
        );

        $translations[ $language_slug ] = $object->get_id();
        $new_translations = $this->data_store->save_translations($translations);

        /* Use loose comparison to avoid ordering issues */
        return $new_translations == $expected; // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
    }

    /**
     * Returns the database identifier for the item.
     *
     * @since 3.8
     *
     * @param array|object $item Item array or object, usually a post or term.
     * @return int The database identifier, 0 if not found.
     */
    protected function get_db_id($item): int
    {
        // `WC_Product` for `wc/v3/products/{id}` update callback.
        if ($item instanceof WC_Product) {
            return $item->get_id();
        }

        // `WP_Post` for `wp/v2/product/{id}` update callback.
        return parent::get_db_id($item);
    }

    /**
     * Allows sharing the product slug across languages.
     * Modifies the REST response accordingly.
     *
     * @since 2.2
     *
     * @param WP_REST_Response       $response The response object.
     * @param WC_Product             $product  Product object.
     * @param WP_REST_Request<array> $request  Request object.
     * @return WP_REST_Response The response object.
     */
    public function prepare_response($response, $product, $request)
    {
        global $wpdb;

        if (! in_array($request->get_method(), array( 'POST', 'PUT', 'PATCH' ), true)) {
            return $response;
        }

        $data = $response->get_data();

        if (! is_array($data) || empty($data['slug'])) {
            return $response;
        }

        $params     = $request->get_params();
        $attributes = $request->get_attributes();

        if (! empty($params['slug'])) {
            $requested_slug = $params['slug'];
        } elseif (isset($attributes['callback']) && is_array($attributes['callback'])
                    && 'create_item' === $attributes['callback'][1]) {
            // Allow sharing slug by default when creating a new product.
            $requested_slug = sanitize_title($product->get_name());
        }

        if (! isset($requested_slug) || $product->get_slug() === $requested_slug) {
            return $response;
        }

        $slug = wp_unique_post_slug($requested_slug, $product->get_id(), $product->get_status(), (string) get_post_type($product->get_id()), $product->get_parent_id());

        if ($slug === $data['slug'] || ! $wpdb->update($wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $product->get_id() ))) {
            return $response;
        }

        $data['slug'] = $slug;
        $response->set_data($data);

        return $response;
    }
}
