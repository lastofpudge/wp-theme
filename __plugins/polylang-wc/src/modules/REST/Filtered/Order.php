<?php

/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Filtered;

use PLL_REST_API;
use WP_Syntex\Polylang_Pro\REST\Filtered\Post;

/**
 * Filters orders by language in the REST API.
 *
 * @since 2.2
 */
class Order extends Post
{
    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
     */
    public function __construct(PLL_REST_API $rest_api)
    {
        parent::__construct($rest_api, array( 'shop_order' ));

        add_filter('woocommerce_rest_shop_order_object_query', array( $this, 'add_language_query_arg_in_rest' ), 10, 2);
    }

    /**
     * Adds a `lang` entry to the given array, depending on the language requested in the REST API.
     * This is used to filter the orders by language in WC's REST route V3 (`/wc/v3/orders`).
     * Hooked to `woocommerce_rest_{$post_type}_object_query`.
     *
     * @see WC_REST_CRUD_Controller::prepare_objects_query()
     *
     * @since 1.9
     *
     * @param array            $args    Key value array of query var to query value.
     * @param \WP_REST_Request $request The request used.
     * @return array
     *
     * @phpstan-param \WP_REST_Request<array{lang?: string}> $request
     */
    public function add_language_query_arg_in_rest($args, $request)
    {
        $args['lang'] = $request->get_param('lang');
        return $args;
    }
}
