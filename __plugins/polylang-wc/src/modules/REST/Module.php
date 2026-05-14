<?php

/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST;

use PLL_REST_API;

/**
 * A class to manage the REST API integration.
 * Requires Polylang Pro 3.8 or later.
 *
 * @since 2.2
 */
class Module
{
    /**
     * @var Translated\Product|null
     */
    public $product;

    /**
     * @var Translatable\Order|null
     */
    public $order;

    /**
     * @var Translated\Category|null
     */
    public $product_category;

    /**
     * @var Translated\Tag|null
     */
    public $product_tag;

    /**
     * @var Translated\Brand|null
     */
    public $product_brand;

    /**
     * @var Translated\Attribute|null
     */
    public $attribute_term;

    /**
     * @var Filtered\Product|null
     */
    public $filtered_product;

    /**
     * @var Filtered\Order|null
     */
    public $filtered_order;

    /**
     * @var Filtered\Attribute|null
     */
    public $filtered_attribute_term;

    /**
     * Constructor.
     *
     * @since 2.2
     */
    public function __construct()
    {
        add_action('rest_api_init', array( $this, 'init' ), 20); // After Polylang.
        add_filter('pll_rest_api_post_types', array( $this, 'post_types' ));
        add_filter('pll_rest_api_taxonomies', array( $this, 'taxonomies' ));
    }

    /**
     * Initializes filters after the Polylang REST API has been initialized.
     *
     * @since 0.9
     *
     * @return void
     */
    public function init()
    {
        if (! isset(PLL()->rest_api) || ! PLL()->rest_api instanceof PLL_REST_API) {
            // Should not happen since this class is instantiated only if `POLYLANG_PRO` is defined.
            return;
        }

        $this->product = new Translated\Product(PLL()->rest_api);
        $this->order   = new Translatable\Order(PLL()->rest_api);

        // WooCommerce taxonomies.
        $this->product_category = new Translated\Category(PLL()->rest_api);
        $this->product_tag      = new Translated\Tag(PLL()->rest_api);

        if (taxonomy_exists('product_brand')) {
            // WooCommerce Brands (WooCommerce 9.4+).
            $this->product_brand = new Translated\Brand(PLL()->rest_api);
        }

        // Product attributes.
        $this->attribute_term = new Translated\Attribute(PLL()->rest_api);

        $this->filtered_product        = new Filtered\Product(PLL()->rest_api);
        $this->filtered_order          = new Filtered\Order(PLL()->rest_api);
        $this->filtered_attribute_term = new Filtered\Attribute(PLL()->rest_api);
    }

    /**
     * Removes the order and product related post types from the Polylang Pro REST integration.
     * Polylang WC holds both WP and WC REST API integrations.
     * `register_rest_field()` registers the callbacks for all controllers extending `WP_REST_Controller`,
     * so the integration is centralized in this module.
     *
     * @since 2.2
     *
     * @param array $args Options passed to Translated\Post.
     * @return array
     */
    public function post_types($args)
    {
        return array_diff($args, array( 'shop_order', 'product', 'product_variation' ));
    }

    /**
     * Removes the product attribute term taxonomy, categories, tags and brand from the Polylang Pro REST integration
     * since they're handled by Translated\Term class.
     *
     * @since 2.2
     *
     * @param array $args Options passed to Translated\Term.
     * @return array
     */
    public function taxonomies($args)
    {
        return array_diff($args, array( 'product_cat', 'product_tag', 'product_brand', 'product_attribute_term' ));
    }
}
