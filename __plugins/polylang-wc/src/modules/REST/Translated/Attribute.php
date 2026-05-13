<?php
/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translated;

use PLL_REST_API;

/**
 * Exposes the term language in the REST API for the product attributes.
 *
 * Supports all product attribute terms (pa_*).
 *
 * @since 2.2
 */
class Attribute extends Term {
	/**
	 * Constructor.
	 *
	 * @since 2.2
	 *
	 * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
	 */
	public function __construct( PLL_REST_API $rest_api ) {
		parent::__construct( $rest_api, 'product_attribute_term' );

		// Register hooks for all existing attributes.
		foreach ( wc_get_attribute_taxonomies() as $tax ) {
			$attr_name = wc_attribute_taxonomy_name( $tax->attribute_name );
			add_action( "woocommerce_rest_insert_{$attr_name}", array( $this, 'next_batch_item' ) );
		}
	}

	/**
	 * Returns the REST field type for a content type.
	 *
	 * @since 2.2
	 *
	 * @param string $type Taxonomy name.
	 * @return string REST API field type.
	 */
	protected function get_rest_field_type( $type ) {
		if ( ! str_starts_with( $type, 'pa_' ) ) {
			return $type;
		}

		foreach ( wc_get_attribute_taxonomies() as $tax ) {
			if ( wc_attribute_taxonomy_name( $tax->attribute_name ) === $type ) {
				return 'product_attribute_term';
			}
		}

		return $type;
	}

	/**
	 * Checks if this handler should process the given batch route.
	 *
	 * Handles WooCommerce product attribute batch requests:
	 * - /wc/v2/products/attributes/{id}/terms/batch
	 * - /wc/v3/products/attributes/{id}/terms/batch
	 *
	 * @since 2.2
	 *
	 * @param string $route The REST route being requested.
	 * @return bool True if this is a batch request for product attributes.
	 */
	protected function is_batch_route( string $route ): bool {
		return (bool) preg_match( '#/wc/v[23]/products/attributes/\d+/terms/batch#', $route );
	}
}
