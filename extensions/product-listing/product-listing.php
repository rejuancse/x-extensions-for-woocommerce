<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('XWOO_PRODUCT_LISTING_FILE', __FILE__);
define('XWOO_PRODUCT_DIR_PATHE', plugin_dir_path( XWOO_PRODUCT_LISTING_FILE ) );
define('XWOO_PRODUCT_BASE_NAME', plugin_basename( XWOO_PRODUCT_LISTING_FILE ) );

/**
 * Showing config for extensions central lists
 */
add_filter('xwoo_extensions_lists_config', 'xwoo_product_listing_config');
function xwoo_product_listing_config( $config ) {
	$basicConfig = array(
		'name'          => __( 'Product Listing', 'xwoo' ),
		'description'   => __( 'WooCommerce product listing', 'xwoo' ),
		'path'			=> XWOO_PRODUCT_DIR_PATHE,
		'url'			=> plugin_dir_url( XWOO_PRODUCT_LISTING_FILE ),
		'basename'		=> XWOO_PRODUCT_BASE_NAME,
	);
	$config[ XWOO_PRODUCT_BASE_NAME ] = $basicConfig;
	return $config;
}

$addonConfig = xwoo_function()->get_addon_config( XWOO_PRODUCT_BASE_NAME );
$isEnable = (bool) xwoo_function()->avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include_once 'classes/Init.php';
}
