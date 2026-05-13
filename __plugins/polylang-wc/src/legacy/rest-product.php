<?php
/**
 * @package Polylang-WC
 */

/**
 * Expose the product language and translations in the REST API.
 * Used for backward compatibility with Polylang < 3.8.
 *
 * @since 1.1
 */
class PLLWC_REST_Product extends PLL_REST_Translated_Object {
	/**
	 * Product language data store.
	 *
	 * @var PLLWC_Product_Language_CPT
	 */
	protected $data_store;

	/**
	 * Constructor.
	 *
	 * @since 1.1
	 * @since 2.2 Added the parameter `$rest_api`.
	 *
	 * @param PLL_REST_API $rest_api Instance of `PLL_REST_API`.
	 */
	public function __construct( PLL_REST_API $rest_api ) {
		parent::__construct( $rest_api, array( 'product' => array( 'filters' => false ) ) );

		$this->type           = 'post';
		$this->setter_id_name = 'ID';

		$this->data_store = PLLWC_Data_Store::load( 'product_language' );

		foreach ( array( 'product', 'product_variation' ) as $post_type ) {
			add_filter( "woocommerce_rest_prepare_{$post_type}_object", array( $this, 'prepare_response' ), 10, 3 );
		}

		add_filter( 'get_terms_args', array( $this, 'get_terms_args' ) ); // Before Auto translate.

		add_filter( 'pllwc_language_for_unique_sku', array( $this, 'filter_language_with_request' ) );
		add_filter( 'pllwc_language_for_lock_on_sku', array( $this, 'filter_language_with_request' ) );
		add_filter( 'pllwc_language_for_global_unique_id', array( $this, 'filter_language_with_request' ) );
	}

	/**
	 * Returns the object language.
	 *
	 * @since 1.1
	 *
	 * @param array $object Product array.
	 * @return string|false
	 */
	public function get_language( $object ) {
		return $this->data_store->get_language( $object['id'] );
	}

	/**
	 * Sets the object language.
	 *
	 * @since 1.1
	 *
	 * @param string $lang   Language code.
	 * @param object $object Instance of WC_Product.
	 * @return bool
	 */
	public function set_language( $lang, $object ) {
		if ( $object instanceof WC_Product ) {
			$this->data_store->set_language( $object->get_id(), $lang );
		} else {
			parent::set_language( $lang, $object );
		}
		return true;
	}

	/**
	 * Returns the object translations.
	 *
	 * @since 1.1
	 *
	 * @param array $object Product array.
	 * @return array
	 */
	public function get_translations( $object ) {
		return $this->data_store->get_translations( $object['id'] );
	}

	/**
	 * Save the translations.
	 *
	 * @since 1.1
	 *
	 * @param int[]  $translations Array of translations with language codes as keys and object ids as values.
	 * @param object $object       Instance of WC_Product.
	 * @return bool
	 */
	public function save_translations( $translations, $object ) {
		if ( $object instanceof WC_Product ) {
			$translations[ $this->data_store->get_language( $object->get_id() ) ] = $object->get_id();
			$this->data_store->save_translations( $translations );
		} else {
			parent::save_translations( $translations, $object );
		}
		return true;
	}

	/**
	 * Deactivate Auto translate to allow queries of attribute terms in the right language.
	 *
	 * @since 1.1
	 *
	 * @param array $args WP_Term_Query arguments.
	 * @return array
	 */
	public function get_terms_args( $args ) {
		if ( ! empty( $args['include'] ) ) {
			$args['lang'] = '';
		}
		return $args;
	}

	/**
	 * Returns the language to use when searching if a sku is unique.
	 *
	 * @since 1.3
	 *
	 * @param PLL_Language|false $language Found language object, `false` if none.
	 * @return PLL_Language|false The requested language object, `false` if a wrong slug is passed.
	 */
	public function filter_language_with_request( $language ) {
		if ( isset( $this->request['lang'] ) && in_array( $this->request['lang'], $this->model->get_languages_list( array( 'fields' => 'slug' ) ) ) ) {
			$language = PLL()->model->get_language( $this->request['lang'] );
		}

		return $language;
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
	public function prepare_response( $response, $product, $request ) {
		global $wpdb;

		if ( ! in_array( $request->get_method(), array( 'POST', 'PUT', 'PATCH' ), true ) ) {
			return $response;
		}

		$data = $response->get_data();

		if ( ! is_array( $data ) || empty( $data['slug'] ) ) {
			return $response;
		}

		$params     = $request->get_params();
		$attributes = $request->get_attributes();

		if ( ! empty( $params['slug'] ) ) {
			$requested_slug = $params['slug'];
		} elseif ( is_array( $attributes['callback'] ) && 'create_item' === $attributes['callback'][1] ) {
			// Allow sharing slug by default when creating a new product.
			$requested_slug = sanitize_title( $product->get_name() );
		}

		if ( ! isset( $requested_slug ) || $product->get_slug() === $requested_slug ) {
			return $response;
		}

		$slug = wp_unique_post_slug( $requested_slug, $product->get_id(), $product->get_status(), (string) get_post_type( $product->get_id() ), $product->get_parent_id() );

		if ( $slug === $data['slug'] || ! $wpdb->update( $wpdb->posts, array( 'post_name' => $slug ), array( 'ID' => $product->get_id() ) ) ) {
			return $response;
		}

		$data['slug'] = $slug;
		$response->set_data( $data );

		return $response;
	}
}
