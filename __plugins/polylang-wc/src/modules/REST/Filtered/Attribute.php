<?php
/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Filtered;

use PLL_REST_API;
use WP_Syntex\Polylang_Pro\REST\Filtered\Term;

/**
 * Filters products by language in the REST API.
 *
 * @since 2.2
 */
class Attribute extends Term {
	/**
	 * Constructor.
	 *
	 * @since 2.2
	 *
	 * @param PLL_REST_API $rest_api  Instance of `PLL_REST_API`.
	 */
	public function __construct( PLL_REST_API $rest_api ) {
		parent::__construct( $rest_api, array( 'product_attribute_term' ) );
	}
}
