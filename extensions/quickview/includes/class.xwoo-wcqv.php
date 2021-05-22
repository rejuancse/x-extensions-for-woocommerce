<?php
/**
 * Main class
 *
 * @author  XWOO
 * @package XWOO WooCommerce Quick View
 * @version 1.0.0
 */

defined( 'XWOO_QUICK_VIEW' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'XWOO_QUICK_VIEW' ) ) {
	/**
	 * XWOO WooCommerce Quick View
	 *
	 * @since 1.0.0
	 */
	class XWOO_QUICK_VIEW {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var XWOO_QUICK_VIEW
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = XWOO_QUICK_VIEW_VERSION;

		/**
		 * Plugin object
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $obj = null;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return XWOO_QUICK_VIEW
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {

			// Load Plugin Framework.
			// add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			if ( $this->can_load() ) {
				if ( $this->load_frontend() ) {
					require_once 'class.xwoo-wcqv-frontend.php';
					XWOO_QUICK_VIEW_Frontend();
				}
			}
		}

		/**
		 * Check if the plugin can load. Exit if is WooCommerce AJAX.
		 *
		 * @since  1.5
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function can_load() {
			$action = array(
				'woocommerce_get_refreshed_fragments',
				'woocommerce_apply_coupon',
				'woocommerce_remove_coupon',
				'woocommerce_update_shipping_method',
				'woocommerce_update_order_review',
				'woocommerce_add_to_cart',
				'woocommerce_checkout',
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $action, true ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if context is admin
		 *
		 * @since  1.2.0
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_admin() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['context'] ) && 'frontend' === $_REQUEST['context'] );
			return apply_filters( 'xwoo_quickview_is_admin', is_admin() && ! $is_ajax );
		}

		/**
		 * Check if load or not frontend
		 *
		 * @since  1.2.0
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function load_frontend() {
			$enable           = get_option( 'wp_quick_view', 'true' ) === 'true';
			$enable_on_mobile = get_option( 'mobile_quick_view', 'true' ) === 'true';
			$is_mobile        = wp_is_mobile();

			return apply_filters( 'xwoo_quickview_load_frontend', ( ! $is_mobile && $enable ) || ( $is_mobile && $enable_on_mobile ) );
		}


		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @author Andrea Grillo <andrea.grillo@xwooemes.com>
		 * @return void
		 */
		// public function plugin_fw_loader() {
		// 	if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
		// 		global $plugin_fw_data;
		// 		if ( ! empty( $plugin_fw_data ) ) {
		// 			$plugin_fw_file = array_shift( $plugin_fw_data );
		// 			require_once $plugin_fw_file;
		// 		}
		// 	}
		// }
	}
}

/**
 * Unique access to instance of XWOO_QUICK_VIEW class
 *
 * @since 1.0.0
 * @return XWOO_QUICK_VIEW
 */
function XWOO_QUICK_VIEW() { // phpcs:ignore
	return XWOO_QUICK_VIEW::get_instance();
}
