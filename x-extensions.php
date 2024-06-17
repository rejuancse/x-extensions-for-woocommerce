<?php
/**
 * Plugin Name: X-Extensions for WooCommerce
 * Description: Boost your WooCommerce store with X-Extensions, featuring advanced product listing, AJAX search, and product quick view. Enjoy instant search results, customizable displays, and seamless quick view functionality, all in one powerful, performance-optimized plugin.
 * Version: 1.0.2
 * Requires at least: 5.9
 * Tested up to: 6.1
 * Author: Rejuan Ahamed
 * Text Domain: xewc
 * Domain Path: /languages/
 *
 * @package UserRegistration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
* Support for Multi Network Site
*/
if( !function_exists('is_plugin_active_for_network') ){
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

/**
* @Type
* @Version
* @Directory URL
* @Directory Path
* @Plugin Base Name
*/
define('XEWC_FILE', __FILE__);
define('XEWC_VERSION', '1.0.2');
define('XEWC_DIR_URL', plugin_dir_url( XEWC_FILE ));
define('XEWC_DIR_PATH', plugin_dir_path( XEWC_FILE ));
define('XEWC_BASENAME', plugin_basename( XEWC_FILE ));
/**
* Load Text Domain Language
*/
add_action('init', 'xewc_language_load');
function xewc_language_load(){
    load_plugin_textdomain('xewc', false, basename(dirname( XEWC_FILE )).'/languages/');
}

if (!function_exists('xewc_function')) {
    function xewc_function() {
        require_once XEWC_DIR_PATH . 'includes/Functions.php';
        return new \XEWC\Functions();
    }
}

if (!class_exists( 'XEWC_Extensions' )) {
    require_once XEWC_DIR_PATH . 'includes/XEWC.php';
    new \XEWC\XEWC_Extensions();
}
