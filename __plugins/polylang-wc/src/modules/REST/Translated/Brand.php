<?php
/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translated;

use PLL_REST_API;

/**
 * Exposes the term language and translations in the REST API for product brands.
 *
 * Requires WooCommerce 9.4+.
 *
 * @since 2.2
 */
class Brand extends Term {

	/**
	 * Constructor.
	 *
	 * @since 2.2
	 *
	 * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
	 */
	public function __construct( PLL_REST_API $rest_api ) {
		parent::__construct( $rest_api, 'product_brand' );
	}

	/**
	 * Checks if this handler should process the given batch route.
	 *
	 * Handles product brands batch requests:
	 * - /wc/v2/products/brands/batch
	 * - /wc/v3/products/brands/batch
	 *
	 * @since 2.2
	 *
	 * @param string $route The REST route being requested.
	 * @return bool True if this is a batch request for product brands.
	 */
	protected function is_batch_route( string $route ): bool {
		return (bool) preg_match( '#/wc/v[23]/products/brands/batch#', $route );
	}
}
