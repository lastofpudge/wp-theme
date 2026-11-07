<?php

/**
 * A class to filter the REST API.
 * Needs Polylang Pro 2.2.1 or later.
 * Tested with the WC API v2 or later ( WC 3.0 or later ).
 *
 * @since 0.9
 */
class PLLWC_REST_API
{
    /**
     * @var PLLWC_REST_Product|null
     */
    public $product;

    /**
     * @var PLLWC_REST_Order|null
     */
    public $order;

    /**
     * @var PLLWC_REST_Attribute_Term|null
     */
    public $attribute_term;

    /**
     * Constructor.
     * Setups actions and filters.
     *
     * @since 0.9
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'init'], 20); // After Polylang.
        add_filter('pll_rest_api_post_types', [$this, 'post_types']);
        add_filter('pll_rest_api_taxonomies', [$this, 'taxonomies']);
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
        if (!isset(PLL()->rest_api) || !PLL()->rest_api instanceof PLL_REST_API) {
            // Should not happen since this class is instantiated only if `POLYLANG_PRO` is defined.
            return;
        }

        $this->product = new PLLWC_REST_Product(PLL()->rest_api);
        $this->order = new PLLWC_REST_Order(PLL()->rest_api);
        $this->attribute_term = new PLLWC_REST_Attribute_Term(PLL()->rest_api);
    }

    /**
     * Removes the translations from the response when querying orders.
     *
     * @since 0.9
     *
     * @param array $args Options passed to PLL_REST_Post.
     *
     * @return array
     */
    public function post_types($args)
    {
        $args['product_variation'] = [];
        $args['shop_order']['translations'] = false;

        return $args;
    }

    /**
     * Adds the language and translations in the response when querying product categories and tags.
     *
     * @since 0.9
     *
     * @param array $args Options passed to PLL_REST_Term.
     *
     * @return array
     */
    public function taxonomies($args)
    {
        $args['product_cat'] = [];
        $args['product_tag'] = [];
        unset($args['product_attribute_term']); // Handled in `PLLWC_REST_Attribute_Term`.

        return $args;
    }
}
