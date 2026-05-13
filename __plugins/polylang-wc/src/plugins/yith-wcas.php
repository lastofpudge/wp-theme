<?php
/**
 * @package Polylang-WC
 */

/**
 * Manages the compatibility with Yith WooCommerce Ajax Search.
 * Version tested: 2.8.1.
 *
 * @since 0.9
 */
class PLLWC_Yith_WCAS {

	/**
	 * Constructor.
	 *
	 * @since 0.9
	 */
	public function __construct() {
		// Only versions >= 2.0.0 are supported.
		add_filter( 'ywcas_block_common_localize', array( $this, 'filter_block_localize' ), 99 );
		add_action( 'parse_request', array( $this, 'fix_lang_query_var' ), 5 ); // Early, so hooks that use the default priority will get the right `lang` query var.
	}

	/**
	 * Filters the site URL in the current language in localization information.
	 *
	 * @since 1.9.5
	 *
	 * @param array $script_localize An array of information about localization.
	 * @return array
	 */
	public function filter_block_localize( $script_localize ) {
		$script_localize['siteURL'] = pll_home_url();
		return $script_localize;
	}

	/**
	 * Fixes the query var `lang` that has been replaced by Yith.
	 * Ex: `example.com/fr/?ywcas=1&post_type=product&lang=fr_FR&s=xxx`.
	 *
	 * @since 2.1.2
	 *
	 * @param WP $wp Current WordPress environment instance (passed by reference).
	 * @return void
	 */
	public function fix_lang_query_var( WP $wp ): void {
		if ( empty( $wp->query_vars['lang'] ) || empty( $_GET['ywcas'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// Nothing to do.
			return;
		}

		if ( ! empty( PLL()->curlang ) ) {
			$wp->query_vars['lang'] = PLL()->curlang->slug;
			return;
		}

		if ( ! empty( $wp->matched_query ) ) {
			// Pretty permalinks, the language is in the URL.
			parse_str( $wp->matched_query, $matched_query );

			if ( ! empty( $matched_query['lang'] ) ) {
				$wp->query_vars['lang'] = $matched_query['lang'];
			}
			return;
		}

		if ( ! empty( PLL()->options['hide_default'] ) && PLL()->options['force_lang'] < 3 ) {
			// The language is not displayed in the URL (`hide_default` is `true`).
			$language_slug = pll_default_language();

			if ( ! empty( $language_slug ) ) {
				$wp->query_vars['lang'] = $language_slug;
			}
			return;
		}
	}
}
