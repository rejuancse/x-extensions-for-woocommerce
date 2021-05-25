<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('XWOO_PRODUCT_SEARCH_FILE', __FILE__);
define('XWOO_SEARCH_DIR_PATHE', plugin_dir_path( XWOO_PRODUCT_SEARCH_FILE ) );
define('XWOO_SEARCH_BASE_NAME', plugin_basename( XWOO_PRODUCT_SEARCH_FILE ) );

/**
 * Showing config for extensions central lists
 */
add_filter('xwoo_extensions_lists_config', 'xwoo_product_search_config');
function xwoo_product_search_config( $config ) {
	$basicConfig = array(
		'name'          => __( 'Product Search', 'xwoo' ),
		'description'   => __( 'WooCommerce product search using ajax request', 'xwoo' ),
		'path'			=> XWOO_SEARCH_DIR_PATHE,
		'url'			=> plugin_dir_url( XWOO_PRODUCT_SEARCH_FILE ),
		'basename'		=> XWOO_SEARCH_BASE_NAME,
	);
	$config[ XWOO_SEARCH_BASE_NAME ] = $basicConfig;
	return $config;
}

$addonConfig = xwoo_function()->get_addon_config( XWOO_SEARCH_BASE_NAME );
$isEnable = (bool) xwoo_function()->avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include_once 'classes/Init.php';
}
