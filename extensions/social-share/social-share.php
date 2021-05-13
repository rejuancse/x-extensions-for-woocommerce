<?php

defined( 'ABSPATH' ) || exit;

/**
 * Defined the main file
 */
define('XWOO_EXTENSIONS_FILE', __FILE__);
define('XWOO_EXTENSIONS_DIR_PATH', plugin_dir_path( XWOO_EXTENSIONS_FILE ) );
define('XWOO_EXTENSIONS_BASE_NAME', plugin_basename( XWOO_EXTENSIONS_FILE ) );

/**
 * Showing config for extensions central lists
 */
add_filter('xwoo_extensions_lists_config', 'xwoo_extensions_config');
function xwoo_extensions_config( $config ) {
	$basicConfig = array(
		'name'          => __( 'Product Quick View', 'xwoo' ),
		'description'   => __( 'Connect with more visitors by sharing your site with Product Quick View Extensions', 'xwoo' ),
		'path'			=> XWOO_EXTENSIONS_DIR_PATH,
		'url'			=> plugin_dir_url( XWOO_EXTENSIONS_FILE ),
		'basename'		=> XWOO_EXTENSIONS_BASE_NAME,
	);
	$config[ XWOO_EXTENSIONS_BASE_NAME ] = $basicConfig;
	return $config;
}

$addonConfig = xwoo_function()->get_addon_config( XWOO_EXTENSIONS_BASE_NAME );
$isEnable = (bool) xwoo_function()->avalue_dot( 'is_enable', $addonConfig );
if ( $isEnable ) {
	include_once 'classes/Init.php';
}
