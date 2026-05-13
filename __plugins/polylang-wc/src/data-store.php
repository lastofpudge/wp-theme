<?php
/**
 * @package Polylang-WC
 */

/**
 * Data store factory.
 *
 * As our language data stores don't implement the WC_Object_Data_Store_Interface
 * interface, it appears risky to use WC_Data_Store directly, so it has been thought
 * to be better to create our own class which can be used in a similar way.
 *
 * @since 1.0
 */
class PLLWC_Data_Store {

	/**
	 * Array of data stores.
	 *
	 * @var array<string, class-string>
	 */
	private static $stores = array(
		'order_language'   => 'PLLWC_Order_Language_CPT',
		'product_language' => 'PLLWC_Product_Language_CPT',
	);

	/**
	 * Loads a data store.
	 *
	 * @since 1.0
	 *
	 * @throws Exception If the data store doesn't exist.
	 *
	 * @param 'order_language'|'product_language' $object_type Identifier for the data store.
	 * @return PLLWC_Order_Language_CPT|PLLWC_Product_Language_CPT
	 *         PLLWC_Order_Language_CPT if $object_type is 'order_language',
	 *         PLLWC_Product_Language_CPT if $object_type is 'product_language'.
	 * @phpstan-return ($object_type is 'order_language' ? PLLWC_Order_Language_CPT : PLLWC_Product_Language_CPT)
	 */
	public static function load( $object_type ) {
		/**
		 * Filters the list of available data stores.
		 *
		 * @since 1.0
		 *
		 * @param array<string, class-string> $stores Available data stores.
		 */
		self::$stores = apply_filters( 'pllwc_data_stores', self::$stores );

		/** @var class-string */
		$store = self::$stores[ $object_type ];

		if ( class_exists( $store ) ) {
			/** @var PLLWC_Order_Language_CPT|PLLWC_Product_Language_CPT */
			return new $store();
		}

		throw new Exception( 'Invalid data store.' );
	}
}
