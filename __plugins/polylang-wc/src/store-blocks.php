<?php

/**
 * @package Polylang-WC
 */

use Automattic\WooCommerce\Blocks\Assets\AssetDataRegistry;
use Automattic\WooCommerce\Blocks\Package;

/**
 * A class to handle blocks.
 *
 * @since 1.9.5
 */
class PLLWC_Store_Blocks
{
    /**
     * Setups actions filters.
     *
     * @since 1.9.5
     *
     * @return void
     */
    public function init()
    {
        if (did_action('pll_language_defined')) {
            $this->add_filters();
        } else {
            add_action('pll_language_defined', array( $this, 'add_filters' ), 1);
        }

        // The language is not defined yet in REST.
        if (Polylang::is_rest_request()) {
            add_filter('locale', array( $this, 'get_locale' ));
        }
    }

    /**
     * Setups actions filters once the language is defined.
     *
     * @since 1.9.5
     *
     * @return void
     */
    public function add_filters()
    {
        add_action('wp_footer', array( $this, 'filter_dynamic_blocks' ), 0);

        add_action('woocommerce_blocks_checkout_enqueue_data', array( $this, 'hydrate_store_routes' ));
        add_action('woocommerce_blocks_cart_enqueue_data', array( $this, 'hydrate_store_routes' ));

        // Use `render_block_data` to translate only attribute ids rather than content (which contains content such as post titles and links).
        add_filter('render_block_data', array( $this, 'filter_reviews_by_product_block_id' ));

        // Fix assets URLs when using one domain per language.
        if (PLL()->options['force_lang'] > 1) {
            add_filter('transient_woocommerce_blocks_asset_api_script_data', array( $this, 'blocks_assets_links' ));
            add_filter('transient_woocommerce_blocks_asset_api_script_data_ssl', array( $this, 'blocks_assets_links' ));
        }

        add_action('woocommerce_store_api_checkout_order_processed', array( $this, 'ensure_order_language' ));
    }

    /**
     * Adds a script to allow filtering blocks relying on the WC REST API.
     *
     * @since 1.3
     *
     * @return void
     */
    public function filter_dynamic_blocks()
    {
        $script = $this->get_filter_script('/wc/store/v1');

        // Since WC 5.6.
        wp_add_inline_script('wc-reviews-block-frontend', $script, 'before');
        wp_add_inline_script('wc-all-products-block-frontend', $script, 'before');

        // Since WC 6.9.
        wp_add_inline_script('wc-checkout-block-frontend', $script, 'before');

        // Backward compatibility with WC < 7.1.
        wp_add_inline_script('wc-attribute-filter-block-frontend', $script, 'before');

        // Since WC 7.1.
        wp_add_inline_script('wc-filter-wrapper-block-frontend', $script, 'before');

        /**
         * WooCommerce `wc-cart-checkout-base` script which send REST requests to apply or remove coupon
         * is loaded before `wp-api-fetch` we're using to register our script to filter by language.
         * So we need to check `wc-cart-checkout-base` is enqueued to add our script after `wp-api-fetch`
         * only in this case.
        */
        if (wp_script_is('wc-cart-checkout-base')) {
            wp_add_inline_script('wp-api-fetch', $script, 'after');
        }
    }

    /**
     * Adds the current language to the paths being prefetched.
     *
     * This prevents doing 1 request to the URL modified by `get_filter_script()`, and another to the original one.
     * Thanks to Tofandel for finding the source of the issue and providing a fix.
     *
     * @since 2.1.5
     *
     * @return void
     */
    public function hydrate_store_routes(): void
    {
        if (! is_admin() && ! WC()->is_rest_api_request()) {
            $asset_data_registry = Package::container()->get(AssetDataRegistry::class);
            $asset_data_registry->hydrate_api_request('/wc/store/v1/cart?lang=' . pll_current_language());

            if (current_action() === 'woocommerce_blocks_checkout_enqueue_data') {
                $asset_data_registry->hydrate_data_from_api_request('checkoutData', '/wc/store/v1/checkout?lang=' . pll_current_language());
            }
        }
    }

    /**
     * Get a script to allow filtering blocks relying on the WC REST API.
     *
     * @since 1.5.3
     *
     * @param string $path The REST API path to filter.
     * @return string Inline js script to add.
     */
    protected function get_filter_script($path)
    {
        /** @var string $current_language This cannot be false because the language is defined at this point */
        $current_language = pll_current_language();

        $path   = esc_js($path);
        $lang   = esc_js($current_language);

        return "wp.apiFetch.use(
			function( options, next ) {
				if ( 'undefined' !== options.path && options.path.indexOf( '{$path}' ) >= 0 ) {
					options.path = wp.url.addQueryArgs( options.path, { lang: '{$lang}' } );
				}
				return next( options );
			}
		);";
    }

    /**
     * Translates the product ID for the widget block reviews by product.
     *
     * @since 1.9
     *
     * @param array $parsed_block The block being rendered.
     * @return array
     */
    public function filter_reviews_by_product_block_id($parsed_block)
    {
        if ('woocommerce/reviews-by-product' !== $parsed_block['blockName']) {
            return $parsed_block;
        }

        if (empty(PLL()->curlang) || empty($parsed_block['attrs']['productId'])) {
            return $parsed_block;
        }

        $data_store = PLLWC_Data_Store::load('product_language');

        $product_language = $data_store->get_language($parsed_block['attrs']['productId']);
        if (PLL()->curlang->slug === $product_language) {
            return $parsed_block;
        }

        $translated_product_id = $data_store->get($parsed_block['attrs']['productId']);
        if (! $translated_product_id) {
            return $parsed_block;
        }

        $parsed_block['attrs']['productId'] = $translated_product_id;

        return $parsed_block;
    }

    /**
     * Filters the locale when an account is created during checkout (REST request).
     *
     * @since 1.9.5
     *
     * @param  string $locale The locale ID.
     * @return string
     */
    public function get_locale($locale)
    {
        $requested_url = pll_get_requested_url();
        if (! is_string($requested_url) || ! strpos($requested_url, '/wc/store/v1')) {
            return $locale;
        }

        if (empty($_GET['lang'])) { // phpcs:ignore WordPress.Security.NonceVerification
            return $locale;
        }

        $lang = PLL()->model->get_language(sanitize_key($_GET['lang'])); // phpcs:ignore WordPress.Security.NonceVerification
        if (empty($lang)) {
            return $locale;
        }

        return $lang->locale;
    }

    /**
     * Replaces blocks assets URLs in WooCommerce transient depending on the current language
     * when using one domain per language.
     *
     * @since 2.1
     *
     * @param string|false $value Current value of the WooCommerce transient (JSON encoded).
     * @return string|false WooCommerce transient with blocks assets URLs modified with the current language.
     */
    public function blocks_assets_links($value)
    {
        $transient_value = (array) json_decode((string) $value, true);

        if (
            json_last_error() !== JSON_ERROR_NONE ||
            empty($transient_value['script_data']) ||
            ! is_array($transient_value['script_data'])
        ) {
            return $value;
        }

        foreach ($transient_value['script_data'] as $key => $script_data) {
            if (empty($script_data['src'])) {
                continue;
            }
            /** @var PLL_Language $language */
            $language = PLL()->curlang;

            $transient_value['script_data'][ $key ]['src'] = PLL()->links_model->switch_language_in_link($script_data['src'], $language);
        }

        return wp_json_encode($transient_value);
    }



    /**
     * Ensures the order language is set correctly.
     *
     * @since 2.2
     *
     * @param WC_Order $order Order being processed.
     * @return void
     */
    public function ensure_order_language($order)
    {
        if (PLL()->curlang instanceof PLL_Language) {
            PLLWC_Data_Store::load('order_language')->set_language($order->get_id(), PLL()->curlang);
        }
    }
}
