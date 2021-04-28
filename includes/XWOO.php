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
		$this->include_addons();
		$this->initial_activation();
		do_action('XWOO_before_load');
		$this->run();
		do_action('XWOO_after_load');
	}

	// Include Core
	public function includes_core() {
		require_once XWOO_DIR_PATH.'includes/Initial_Setup.php';
		require_once XWOO_DIR_PATH.'settings/Admin_Menu.php';
		require_once XWOO_DIR_PATH.'includes/Gutenberg.php';
		new settings\Admin_Menu();
		new \XWOO\Gutenberg();
	}

	//Checking Vendor
	public function run() {
		if( xwoo_function()->is_woocommerce() ) {
			$initial_setup = new \XWOO\Initial_Setup();
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				if ( xwoo_function()->wc_version() ) {
					require_once XWOO_DIR_PATH.'includes/woocommerce/Base.php';
					require_once XWOO_DIR_PATH.'includes/woocommerce/Common.php';
					require_once XWOO_DIR_PATH.'includes/woocommerce/Templating.php';
					require_once XWOO_DIR_PATH.'includes/woocommerce/Woocommerce.php';
					require_once XWOO_DIR_PATH.'includes/woocommerce/Actions.php';
					require_once XWOO_DIR_PATH.'includes/woocommerce/Template_Hooks.php';
					new \XWOO\woocommerce\Base();
					new \XWOO\woocommerce\Common();
					$templating_obj = new \XWOO\woocommerce\Templating(); //variable used @compatibility actions
					new \XWOO\woocommerce\Woocommerce();
					new \XWOO\woocommerce\Actions();
					$template_hook_obj = new \XWOO\woocommerce\Template_Hooks(); //variable used @compatibility actions
					require_once XWOO_DIR_PATH.'includes/compatibility/Actions.php'; //require file for compatibility
					add_action( 'widgets_init', array($this, 'register_backer_widget') );
				} else {
					add_action( 'admin_notices', array( $initial_setup , 'wc_low_version' ) );
					deactivate_plugins( plugin_basename( __FILE__ ) );
				}
			} else {
				$xwoo_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';
				if (file_exists($xwoo_file) && ! is_plugin_active('woocommerce/woocommerce.php')) {
					add_action( 'admin_notices', array($initial_setup, 'free_plugin_installed_but_inactive_notice') );
				} elseif ( ! file_exists($xwoo_file) ) {
					add_action( 'admin_notices', array($initial_setup, 'free_plugin_not_installed') );
				}
			}
		}else{
			// Local Code
		}
	}
	
	// Register Widgets
	public function register_backer_widget(){
		require_once XWOO_DIR_PATH.'includes/woocommerce/Widget.php';
		register_widget( 'XWOO\Latest_Backers' );
	}

	// Include Shortcode
	public function include_shortcode() {
		if( class_exists( 'WooCommerce' ) ){
			include_once XWOO_DIR_PATH.'shortcode/Search.php';
	
			$XWOO_search_box = new \XWOO\shortcode\Search();
	
			//require file for compatibility
			require_once XWOO_DIR_PATH.'includes/compatibility/Shortcodes.php';
		}
	}

	// Include Addons directory
	public function include_addons() {
		$addons_dir = array_filter(glob(XWOO_DIR_PATH.'addons/*'), 'is_dir');
		if (count($addons_dir) > 0) {
			foreach( $addons_dir as $key => $value ) {
				$addon_dir_name = str_replace(dirname($value).'/', '', $value);
				$file_name = XWOO_DIR_PATH . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
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