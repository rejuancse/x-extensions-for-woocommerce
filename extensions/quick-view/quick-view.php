<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('XWOO_QUICK_VIEW_FILE', __FILE__);
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
