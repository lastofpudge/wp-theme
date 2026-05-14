<?php

/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translated;

use PLL_REST_API;
use WP_Syntex\Polylang_Pro\REST\Translated\Term as Polylang_Term;

/**
 * Base class for exposing term language and translations in the REST API for WooCommerce taxonomies.
 *
 * @since 2.2
 */
abstract class Term extends Polylang_Term
{
    use Batch;

    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
     * @param string       $taxonomy Taxonomy name.
     */
    public function __construct(PLL_REST_API $rest_api, string $taxonomy)
    {
        parent::__construct($rest_api, array( $taxonomy ));

        add_filter('rest_pre_dispatch', array( $this, 'init_batch_lang_queue' ), 10, 3);
        add_filter('pll_inserted_term_language', array( $this, 'filter_language_with_request' ));
        add_action("woocommerce_rest_insert_{$taxonomy}", array( $this, 'next_batch_item' ));
    }

    /**
     * Checks if this handler should process the given batch route.
     *
     * @since 2.2
     *
     * @param string $route The REST route being requested.
     * @return bool True if this is a batch request for WooCommerce taxonomies.
     */
    abstract protected function is_batch_route(string $route): bool;
}
