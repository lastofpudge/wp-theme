<?php
/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Filtered;

use PLL_REST_API;
use WP_Syntex\Polylang_Pro\REST\Filtered\Post;

/**
 * Filters products by language in the REST API.
 *
 * @since 2.2
 */
class Product extends Post {
	/**
	 * Constructor.
	 *
	 * @since 2.2
	 *
	 * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
	 */
	public function __construct( PLL_REST_API $rest_api ) {
		parent::__construct( $rest_api, array( 'product' ) );

		add_filter( 'get_terms_args', array( $this, 'get_terms_args' ) ); // Before Auto translate.
	}

	/**
	 * Deactivate Auto translate to allow queries of attribute terms in the right language.
	 *
	 * @since 1.1
	 *
	 * @param array $args `WP_Term_Query` arguments.
	 * @return array Modified `WP_Term_Query` arguments.
	 */
	public function get_terms_args( $args ) {
		if ( ! empty( $args['include'] ) ) {
			$args['lang'] = '';
		}
		return $args;
	}
}
