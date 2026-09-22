<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('XEWC_PRODUCT_SEARCH_FILE', __FILE__);
define('XEWC_SEARCH_DIR_PATHE', plugin_dir_path( XEWC_PRODUCT_SEARCH_FILE ) );
define('XEWC_SEARCH_BASE_NAME', plugin_basename( XEWC_PRODUCT_SEARCH_FILE ) );

/**
 * Showing config for extensions central lists
 */
add_filter('xewc_extensions_lists_config', 'xewc_product_search_config');
function xewc_product_search_config( $config ) {
	$basicConfig = array(
		'name'          => __( 'Product Search', 'x-extensions-for-woocommerce' ),
		'description'   => __( 'WooCommerce product search using ajax request', 'x-extensions-for-woocommerce' ),
		'path'			=> XEWC_SEARCH_DIR_PATHE,
		'url'			=> plugin_dir_url( XEWC_PRODUCT_SEARCH_FILE ),
		'basename'		=> XEWC_SEARCH_BASE_NAME,
	);
	$config[ XEWC_SEARCH_BASE_NAME ] = $basicConfig;
	return $config;
}

$xewc_addon_config = xewc_function()->get_addon_config( XEWC_SEARCH_BASE_NAME );
$xewc_is_enable = (bool) xewc_function()->avalue_dot( 'is_enable', $xewc_addon_config );
if ( $xewc_is_enable ) {
	include_once 'classes/Init.php';
}
