<?php
/**
 * Polylang for WooCommerce
 *
 * @package              Polylang-WC
 * @author               WP SYNTEX
 * @license              GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin name:          Polylang for WooCommerce
 * Plugin URI:           https://polylang.pro
 * Description:          Adds multilingual capability to WooCommerce
 * Version:              2.2.1
 * Requires at least:    6.5
 * Requires PHP:         7.4
 * Requires Plugins:     polylang, woocommerce
 * Author:               WP SYNTEX
 * Author URI:           https://polylang.pro
 * Text Domain:          polylang-wc
 * Domain Path:          /languages
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * WC requires at least: 9.2
 * WC tested up to:      10.4
 *
 * Copyright 2016-2020 Frédéric Demarle
 * Copyright 2020-2026 WP SYNTEX
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

use Automattic\WooCommerce\Utilities\OrderUtil;
use WP_Syntex\Polylang_WC\Updater\Updater;
use Automattic\WooCommerce\Utilities\FeaturesUtil;
use WP_Syntex\Polylang_WC\REST;

defined( 'ABSPATH' ) || exit;

define( 'PLLWC_VERSION', '2.2.1' );
define( 'PLLWC_MIN_PLL_VERSION', '3.7' );

define( 'PLLWC_FILE', __FILE__ ); // This file.
define( 'PLLWC_BASENAME', plugin_basename( PLLWC_FILE ) ); // Plugin name as known by WP.

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/functions.php';

/**
 * Plugin controller.
 *
 * @since 0.1
 */
class Polylang_Woocommerce {
	/**
	 * @var Updater|null
	 */
	public $updater;

	/**
	 * @var PLLWC_Admin_Menus
	 */
	public $admin_menus;

	/**
	 * @var PLLWC_Admin_Products
	 */
	public $admin_products;

	/**
	 * @var PLLWC_Admin_Product_Duplicate
	 */
	public $admin_product_duplicate;

	/**
	 * @var PLLWC_Admin_Orders
	 */
	public $admin_orders;

	/**
	 * @var PLLWC_Admin_Reports
	 */
	public $admin_reports;

	/**
	 * @var PLLWC_Admin_Status_Reports
	 */
	public $admin_status_reports;

	/**
	 * @var PLLWC_Admin_Taxonomies
	 */
	public $admin_taxonomies;

	/**
	 * @var PLLWC_Admin_WC_Install
	 */
	public $admin_wc_install;

	/**
	 * @var PLLWC_Frontend_Cart
	 */
	public $cart;

	/**
	 * @var PLLWC_Coupons
	 */
	public $coupons;

	/**
	 * @var PLLWC_Xdata
	 */
	public $data;

	/**
	 * @var PLLWC_Emails
	 */
	public $emails;

	/**
	 * @var PLLWC_Product_Export
	 */
	public $product_export;

	/**
	 * @var PLLWC_Frontend
	 */
	public $frontend;

	/**
	 * @var PLLWC_Product_Import
	 */
	public $product_import;

	/**
	 * @var PLLWC_Links
	 */
	public $links;

	/**
	 * @var PLLWC_Frontend_Account
	 */
	public $my_account;

	/**
	 * @var PLLWC_Post_Types
	 */
	public $post_types;

	/**
	 * @var PLLWC_Products
	 */
	public $products;

	/**
	 * @var REST\Module|PLLWC_REST_API
	 */
	public $rest_api;

	/**
	 * @var PLLWC_Admin_Site_Health
	 */
	public $site_health;

	/**
	 * @var PLLWC_Stock
	 */
	public $stock;

	/**
	 * @var PLLWC_Strings
	 */
	public $strings;

	/**
	 * @var PLLWC_Sync_Content
	 */
	public $sync_content;

	/**
	 * @var PLLWC_Frontend_WC_Pages
	 */
	public $wc_pages;

	/**
	 * @var PLLWC_Wizard
	 */
	public $wizard;

	/**
	 * @var PLLWC_Translation_Export|null
	 */
	public $translation_export;

	/**
	 * @var PLLWC_Translation_Import|null
	 */
	public $translation_import;

	/**
	 * @var PLLWC_HPOS_Orders_Query|null
	 */
	public $hpos_orders_query;

	/**
	 * @var PLLWC_Store_Blocks
	 */
	public $blocks;

	/**
	 * Singleton.
	 *
	 * @var Polylang_Woocommerce
	 */
	protected static $instance;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		// Registers an action when the plugin is activated.
		add_action( 'activated_plugin', array( $this, 'activated_plugin' ), 10, 2 );

		$install = new PLLWC_Install( plugin_basename( __FILE__ ) );

		// Stopping here if we are going to deactivate the plugin ( avoids breaking rewrite rules ).
		if ( $install->is_deactivation() ) {
			return;
		}

		// WC 3.3: Maybe update default product categories after WooCommerce did it.
		$db_version = get_option( 'woocommerce_db_version' );
		if ( is_string( $db_version ) && version_compare( $db_version, '3.3.0', '<' ) ) {
			add_action( 'add_option_woocommerce_db_version', array( 'PLLWC_Admin_WC_Install', 'update_330_wc_db_version' ), 10, 2 );
		}

		/*
		 * Fix home url when using plain permalinks and the shop is on front.
		 * Added here because the filter is fired before the action 'pll_init'.
		 */
		add_filter( 'pll_additional_language_data', array( 'PLLWC_Links', 'set_home_url' ), 20, 2 ); // After Polylang.

		add_filter( 'pll_is_ajax_on_front', array( $this, 'fix_ajax_product_import' ) );

		// The "ajax" request for feature product is indeed a direct link and thus does not include the pll_ajax_backend query var.
		if ( isset( $_GET['action'] ) && 'woocommerce_feature_product' === $_GET['action'] ) {  // phpcs:ignore WordPress.Security.NonceVerification
			define( 'PLL_ADMIN', true );
		}

		add_action( 'pll_pre_init', array( $this, 'pre_init' ) ); // `plugins_loaded` prio 1.
		PLLWC_Plugins_Compat::instance();
		add_action( 'before_woocommerce_init', array( $this, 'declare_features_compatibility' ), 0 ); // `init` prio 0.
	}

	/**
	 * Get the Polylang for WooCommerce instance.
	 *
	 * @since 0.1
	 *
	 * @return Polylang_Woocommerce
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Tells Polylang that the product import ajax request is made from the backend.
	 *
	 * @since 1.5.3
	 *
	 * @param bool $is_ajax_on_front Whether the current request is an ajax request on front.
	 * @return bool
	 */
	public function fix_ajax_product_import( $is_ajax_on_front ) {
		return isset( $_POST['action'] ) && 'woocommerce_do_ajax_product_import' === $_POST['action'] ? false : $is_ajax_on_front; // phpcs:ignore WordPress.Security.NonceVerification
	}

	/**
	 * Initializes the plugin.
	 *
	 * @since 2.2
	 *
	 * @param PLL_Base $polylang Polylang object.
	 * @return void
	 */
	public function pre_init( $polylang ): void {
		// Silently disable the plugin if WooCommerce are not active.
		if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			return;
		}

		// If the version of Polylang is too old.
		if ( version_compare( POLYLANG_VERSION, PLLWC_MIN_PLL_VERSION, '<' ) ) {
			add_action( 'all_admin_notices', array( $this, 'admin_notices' ) );
			return;
		}

		// if ( $polylang instanceof PLL_Admin_Base ) {
		// 	$this->updater = new Updater( __FILE__, 'Polylang for WooCommerce', PLLWC_VERSION, 'polylang-wc' );
		// }

		add_action( 'pll_init', array( $this, 'init' ) ); // `plugins_loaded` prio 1.
	}

	/**
	 * Initializes the plugin.
	 *
	 * @since 0.1
	 * @since 2.2 Pass Polylang's instance as 1st parameter.
	 *
	 * @param PLL_Base $polylang Polylang object.
	 * @return void
	 */
	public function init( $polylang ): void {
		if ( $polylang instanceof PLL_Admin_Base ) {
			// Instantiate `PLLWC_Wizard` before any language test to display the WooCommerce step in the Wizard.
			if ( class_exists( 'PLL_Wizard' ) && Polylang::is_wizard() && isset( $polylang->wizard ) ) {
				$this->wizard = new PLLWC_Wizard( $polylang->model, $polylang->wizard );
			}
			$this->admin_status_reports = new PLLWC_Admin_Status_Reports();
		}

		add_action( 'admin_init', array( $this, 'maybe_install' ) );

		// Bail early if no language has been defined yet.
		if ( ! pll_languages_list() ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'maybe_upgrade' ) );

		add_action( 'woocommerce_delete_product_transients', array( $this, 'delete_product_transients' ) );

		PLLWC_Variation_Data_Store_CPT::init();

		$this->post_types     = new PLLWC_Post_Types();
		$this->links          = defined( 'POLYLANG_PRO' ) && POLYLANG_PRO && get_option( 'permalink_structure' ) ? new PLLWC_Links_Pro() : new PLLWC_Links();
		$this->stock          = new PLLWC_Stock();
		$this->emails         = new PLLWC_Emails();
		$this->strings        = new PLLWC_Strings();
		$this->data           = new PLLWC_Xdata();
		$this->product_export = new PLLWC_Product_Export();
		$this->product_import = new PLLWC_Product_Import();
		$this->products       = new PLLWC_Products();
		$this->blocks         = new PLLWC_Store_Blocks();

		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			$this->hpos_orders_query = ( new PLLWC_HPOS_Orders_Query() )->init();
		}

		$this->blocks->init();

		if ( defined( 'POLYLANG_PRO' ) && POLYLANG_PRO ) {
			// Backward compatibility with Polylang < 3.8.
			if ( ! class_exists( 'WP_Syntex\Polylang_Pro\REST\Translated\Post' ) ) {
				$this->rest_api = new PLLWC_REST_API();
			} else {
				$this->rest_api = new REST\Module();
			}
			$this->sync_content = new PLLWC_Sync_Content();
		}

		/*
		 * We need to load our cart integration on all ajax requests, as WooCommerce does,
		 * but also on REST requests for WooCommerce Blocks 2.5+.
		 */
		if ( $polylang instanceof PLL_Frontend || wp_doing_ajax() || $polylang instanceof PLL_REST_Request ) {
			$this->cart    = new PLLWC_Frontend_Cart();
			$this->coupons = new PLLWC_Coupons();
		}

		// Frontend only.
		if ( $polylang instanceof PLL_Frontend ) {
			$this->frontend   = new PLLWC_Frontend();
			$this->my_account = new PLLWC_Frontend_Account();

			// WC pages on front.
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$this->wc_pages = new PLLWC_Frontend_WC_Pages();
			}
		} else {
			$this->admin_wc_install = new PLLWC_Admin_WC_Install();

			// Admin only ( but not useful on Polylang settings pages ).
			if ( $polylang instanceof PLL_Admin ) {
				$this->admin_taxonomies        = new PLLWC_Admin_Taxonomies();
				$this->admin_products          = new PLLWC_Admin_Products();
				$this->admin_product_duplicate = new PLLWC_Admin_Product_Duplicate();
				$this->admin_reports           = new PLLWC_Admin_Reports();
				$this->admin_menus             = new PLLWC_Admin_Menus();
				$this->coupons                 = new PLLWC_Admin_Coupons();
				$this->site_health             = new PLLWC_Admin_Site_Health();
				$this->admin_orders            = OrderUtil::custom_orders_table_usage_is_enabled() ?
					new PLLWC_Admin_Orders_HPOS() : new PLLWC_Admin_Orders_Legacy();

				if ( defined( 'POLYLANG_PRO' ) && POLYLANG_PRO ) {
					$this->translation_export = ( new PLLWC_Translation_Export() )->init();
					$this->translation_import = ( new PLLWC_Translation_Import( $this->products ) )->init();
				}

				add_action( 'woocommerce_system_status_report', array( $this->admin_status_reports, 'status_report' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}

			// Translation import is also needed in Polylang settings pages for XLIFF import.
			if ( $polylang instanceof PLL_Settings && defined( 'POLYLANG_PRO' ) && POLYLANG_PRO ) {
				$this->translation_import = ( new PLLWC_Translation_Import( $this->products ) )->init();
			}
		}

		/**
		 * Fires after the Polylang for WooCommerce object is initialized.
		 *
		 * @since 0.3.2
		 *
		 * @param object &$this The Polylang for WooCommerce object.
		 */
		do_action_ref_array( 'pllwc_init', array( &$this ) );
	}

	/**
	 * Displays an admin notice if Polylang is not at the right version.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function admin_notices() {
		printf(
			'<div class="error"><p>%s</p><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: %s is the plugin name (Polylang or Polylang Pro) */
					__( 'Polylang for WooCommerce has been deactivated because you are using an old version of %s.', 'polylang-wc' ),
					POLYLANG
				)
			),
			esc_html(
				sprintf(
					/* translators: %1$s and %2$s are plugin version numbers, %3$s is the plugin name (Polylang or Polylang Pro) */
					__( 'You are using %3$s %1$s. Polylang for WooCommerce requires at least %3$s %2$s.', 'polylang-wc' ),
					POLYLANG_VERSION,
					PLLWC_MIN_PLL_VERSION,
					POLYLANG
				)
			)
		);
	}

	/**
	 * Manages updates of the plugin.
	 *
	 * @since 0.9.3
	 *
	 * @return void
	 */
	public function maybe_upgrade() {
		$options = get_option( 'polylang-wc' );

		if ( is_array( $options ) && version_compare( $options['version'], PLLWC_VERSION, '<' ) ) {
			// Version 0.4.3.
			if ( version_compare( $options['version'], '0.4.3', '<' ) ) {
				delete_transient( 'woocommerce_cache_excluded_uris' );
			}

			// Version 0.4.6.
			if ( version_compare( $options['version'], '0.4.6', '<' ) ) {
				// Same as Polylang 2.0.8, for WP 4.7.
				global $wpdb;
				$wpdb->update( $wpdb->usermeta, array( 'meta_key' => 'locale' ), array( 'meta_key' => 'user_lang' ) );
			}

			// Version 0.9.3, if already updated to WC 3.3.
			if ( version_compare( $options['version'], '0.9.3', '<' ) ) {
				if ( version_compare( WC()->version, '3.3.0', '>=' ) ) {
					PLLWC_Admin_WC_Install::create_default_product_cats();
					PLLWC_Admin_WC_Install::replace_default_product_cats();
				}
			}

			$options['previous_version'] = $options['version']; // Remember the previous version.
			$options['version'] = PLLWC_VERSION;
			update_option( 'polylang-wc', $options );
		}

		PLLWC_Admin_WC_Install::create_default_product_cats();
	}

	/**
	 * Manage plugin set up.
	 *
	 * @since 1.5.7
	 *
	 * @return void
	 */
	public function maybe_install() {
		$options = get_option( 'polylang-wc' );

		if ( empty( $options ) ) {
			$options = array( 'version' => PLLWC_VERSION );
			update_option( 'polylang-wc', $options );
		}
	}

	/**
	 * Clear all transients cache for translations when WC clears a product transient.
	 *
	 * @since 0.4.5
	 *
	 * @param int $product_id Product ID.
	 * @return void
	 */
	public function delete_product_transients( $product_id ) {
		static $ids;
		$ids[] = $product_id;

		$data_store = PLLWC_Data_Store::load( 'product_language' );
		foreach ( $data_store->get_translations( $product_id ) as $tr_id ) {
			if ( ! in_array( $tr_id, $ids ) ) {
				wc_delete_product_transients( $tr_id );
			}
		}
	}

	/**
	 * Enqueues the stylesheet.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'pll_wc_admin', plugins_url( '/css/build/admin' . $suffix . '.css', PLLWC_FILE ), array(), PLLWC_VERSION );
	}

	/**
	 * Saves a transient when Polylang For WooCommerce is activating to redirect to the Polylang wizard.
	 *
	 * @since 1.4
	 *
	 * @param string $plugin_name  Plugin basename.
	 * @param bool   $network_wide If activated for all sites in the network.
	 * @return void
	 */
	public static function activated_plugin( $plugin_name, $network_wide ) {
		$options = get_option( 'polylang-wc' ); // If the polylang-wc option is set, the wizard has already been launched once.

		if ( wp_doing_ajax() || $network_wide || ! empty( $options ) ) {
			return;
		}

		if ( PLLWC_BASENAME === $plugin_name && class_exists( 'PLL_Wizard' ) ) {
			set_transient( 'pll_activation_redirect', 1, 30 );
		}
	}

	/**
	 * Declares Polylang For WooCommerce compatibility with WooCommerce features.
	 *
	 * @since 1.9.3
	 *
	 * @return void
	 */
	public function declare_features_compatibility() {
		// Declare compatibility with custom order tables for WooCommerce (HPOS).
		FeaturesUtil::declare_compatibility( 'custom_order_tables', PLLWC_FILE, true );

		// Declare compatibility with cart checkout blocks.
		FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', PLLWC_FILE, true );
	}
}

PLLWC();
