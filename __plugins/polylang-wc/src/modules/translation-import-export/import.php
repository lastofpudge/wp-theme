<?php
/**
 * @package Polylang-WC
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class to manage WooCommerce product import with Polylang Pro XLIFF Importer.
 *
 * @since 1.8
 */
class PLLWC_Translation_Import {
	/**
	 * Object to manage products.
	 *
	 * @var PLLWC_Products
	 */
	private $products;

	/**
	 * Constructor.
	 *
	 * @since 2.2
	 *
	 * @param PLLWC_Products $products Object to manage products.
	 */
	public function __construct( PLLWC_Products $products ) {
		$this->products = $products;
	}

	/**
	 * Adds hooks.
	 *
	 * @since 1.8
	 *
	 * @return self
	 */
	public function init() {
		add_action( 'pll_after_post_import', array( $this, 'process_variations' ), 10, 2 );
		add_action( 'pll_after_post_machine_translation', array( $this, 'process_variations' ), 10, 2 );
		add_filter( 'wp_insert_post_data', array( $this, 'set_variations_post_status' ) );
		add_action( 'pll_after_post_translation', array( $this, 'translate_variations' ), 10, 3 );

		return $this;
	}

	/**
	 * Processes imported posts to translate parent ID for variation products.
	 * Not done by Polylang Pro because `WC_Product_Variation` and `WC_Product_Variable` don't share the same post type.
	 *
	 * @since 1.8
	 *
	 * @param PLL_Language $target_language      The targeted language for import.
	 * @param array        $imported_objects_ids The imported object ids of the import.
	 * @return void
	 */
	public function process_variations( $target_language, $imported_objects_ids ) {
		if ( empty( $imported_objects_ids ) ) {
			return;
		}

		$data_store = PLLWC_Data_Store::load( 'product_language' );

		$args = array(
			'type'    => 'variation',
			'limit'   => count( $imported_objects_ids ),
			'include' => $imported_objects_ids,
			'lang'    => '',
		);

		$variations = wc_get_products( $args );
		if ( empty( $variations ) ) {
			return;
		}

		// Temporarily disable to avoid reverse sync.
		remove_action( 'woocommerce_after_product_object_save', array( $this->products, 'copy_product' ) );

		/** @var WC_Product_Variation[] $variations */
		foreach ( $variations as $variation ) {
			// Get the translated variation ID.
			$tr_variation_id = $data_store->get( $variation->get_id(), $target_language->slug );
			if ( ! $tr_variation_id ) {
				continue;
			}

			$tr_variation = wc_get_product( $tr_variation_id );
			if ( ! $tr_variation ) {
				continue;
			}

			// Get the source and translated parents.
			$source_parent = $variation->get_parent_id();
			$tr_parent     = $data_store->get( $source_parent, $target_language->slug );
			if ( empty( $tr_parent ) ) {
				continue;
			}

			// Set the translated variation's parent.
			$tr_variation->set_parent_id( $tr_parent );
			$tr_variation->save();
		}

		// Re-enable `copy_product`.
		add_action( 'woocommerce_after_product_object_save', array( $this->products, 'copy_product' ) );
	}

	/**
	 * Sets the `post_status` to `publish` for product variations, otherwise
	 * the variation is not accessible in backoffice, even if the parent is a draft.
	 *
	 * @since 1.8
	 *
	 * @param array $data An array of slashed, sanitized, and processed post data.
	 * @return array Filtered post data.
	 */
	public function set_variations_post_status( $data ) {
		if ( 'product_variation' === $data['post_type'] ) {
			$data['post_status'] = 'publish';
		}

		return $data;
	}

	/**
	 * Copies variations if not already done yet.
	 *
	 * @since 2.2
	 *
	 * @param WP_Post      $source_post     The source post.
	 * @param WP_Post      $tr_post         The target post.
	 * @param PLL_Language $target_language The language to translate into.
	 * @return void
	 */
	public function translate_variations( $source_post, $tr_post, $target_language ): void {
		if ( 'product' !== $source_post->post_type ) {
			return;
		}

		$source_product = wc_get_product( $source_post->ID );
		$tr_product     = wc_get_product( $tr_post->ID );

		if (
			$source_product && $tr_product
			&& $source_product->is_type( 'variable' )
			&& ! empty( $source_product->get_children() )
			&& empty( $tr_product->get_children() )
		) {
			// Temporarily disable to avoid reverse sync during variation copy.
			remove_action( 'woocommerce_after_product_object_save', array( $this->products, 'copy_product' ) );

			$this->products->copy_variations( $source_product->get_id(), $tr_product->get_id(), $target_language->slug );

			// Re-enable.
			add_action( 'woocommerce_after_product_object_save', array( $this->products, 'copy_product' ) );
		}
	}
}
