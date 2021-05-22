<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define( 'XWOO_QUICK_VIEW_VERSION', '1.0.0' );
define('XWOO_QUICK_VIEW_FILE', __FILE__);
define( 'XWOO_QUICK_VIEW', true );
define( 'XWOO_QUICK_VIEW_URL', plugin_dir_url( __FILE__ ) );
define( 'XWOO_QUICK_VIEW_ASSETS_URL', XWOO_QUICK_VIEW_URL . 'assets' );
define('XWOO_QUICK_VIEW_DIR_PATH', plugin_dir_path( XWOO_QUICK_VIEW_FILE ) );
define('XWOO_QUICK_VIEW_BASE_NAME', plugin_basename( XWOO_QUICK_VIEW_FILE ) );

/**
 * Showing config for extensions central lists
 */
add_filter('xwoo_extensions_lists_config', 'xwoo_quick_view_config');
function xwoo_quick_view_config( $config ) {
	$basicConfig = array(
		'name'          => __( 'Product Quick View', 'xwoo' ),
		'description'   => __( 'WooCommerce product quick view extension', 'xwoo' ),
		'path'			=> XWOO_QUICK_VIEW_DIR_PATH,
		'url'			=> plugin_dir_url( XWOO_QUICK_VIEW_FILE ),
		'basename'		=> XWOO_QUICK_VIEW_BASE_NAME,
	);
	$config[ XWOO_QUICK_VIEW_BASE_NAME ] = $basicConfig;
	return $config;
}

$addonConfig = xwoo_function()->get_addon_config( XWOO_QUICK_VIEW_BASE_NAME );
$isEnable = (bool) xwoo_function()->avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include_once 'classes/Init.php';
}

add_action( 'xwoo_quickview_init', 'xwoo_quickview_init' );
function xwoo_quickview_init() {
	require_once 'includes/class.xwoo-wcqv.php';
	XWOO_QUICK_VIEW();
}

add_action( 'plugins_loaded', 'xwoo_quickview_install', 11 );
function xwoo_quickview_install() {
	do_action( 'xwoo_quickview_init' );
}
