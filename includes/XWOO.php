<?php
namespace XWOO;

defined( 'ABSPATH' ) || exit;

final class XWOO_Extensions {

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->includes_core();
		$this->include_shortcode();
		$this->include_extensions();
		$this->initial_activation();
		do_action('xwoo_before_load');
		$this->run();
		do_action('xwoo_after_load');
	}

	// Include Core
	public function includes_core() {
		require_once XWOO_DIR_PATH.'includes/Initial_Setup.php';
		require_once XWOO_DIR_PATH.'settings/Admin_Menu.php';
		new settings\Admin_Menu();
	}

	//Checking Vendor
	public function run() {
		if( xwoo_function()->is_woocommerce() ) {
			$initial_setup = new \XWOO\Initial_Setup();
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				if ( xwoo_function()->wc_version() ) {
					require_once XWOO_DIR_PATH.'includes/woocommerce/Base.php';
					new \XWOO\woocommerce\Base();
				} else {
					add_action( 'admin_notices', array( $initial_setup , 'wc_low_version' ) );
					deactivate_plugins( plugin_basename( __FILE__ ) );
				}
			} else {
				$cf_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';
				if (file_exists($cf_file) && ! is_plugin_active('woocommerce/woocommerce.php')) {
					add_action( 'admin_notices', array($initial_setup, 'free_plugin_installed_but_inactive_notice') );
				} elseif ( ! file_exists($cf_file) ) {
					add_action( 'admin_notices', array($initial_setup, 'free_plugin_not_installed') );
				}
			}
		}else{
			// Local Code
		}
	}
	
	// Include Shortcode
	public function include_shortcode() {
		if( class_exists( 'WooCommerce' ) ){
			include_once XWOO_DIR_PATH.'shortcode/ProductListing.php';
			include_once XWOO_DIR_PATH.'shortcode/ProductSearch.php';
			$xwoo_product_listing = new \XWOO\shortcode\Product_Listing();
			$xwoo_product_search = new \XWOO\shortcode\Product_Search();
	
			//require file for compatibility
			require_once XWOO_DIR_PATH.'includes/compatibility/Shortcodes.php';
		}
	}

	// Include Addons directory
	public function include_extensions() {
		$extensions_dir = array_filter(glob(XWOO_DIR_PATH.'extensions/*'), 'is_dir');
		if (count($extensions_dir) > 0) {
			foreach( $extensions_dir as $key => $value ) {
				$addon_dir_name = str_replace(dirname($value).'/', '', $value);
				$file_name = XWOO_DIR_PATH . 'extensions/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
				if ( file_exists($file_name) ) {
					include_once $file_name;
				}
			}
		}
	}

	// Activation & Deactivation Hook
	public function initial_activation() {
		$initial_setup = new \XWOO\Initial_Setup();
		register_activation_hook( XWOO_FILE, array( $initial_setup, 'initial_plugin_activation' ) );
		register_deactivation_hook( XWOO_FILE , array( $initial_setup, 'initial_plugin_deactivation' ) );
	}
}
