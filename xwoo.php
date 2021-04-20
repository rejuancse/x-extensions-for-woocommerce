<?php
/**
 * Plugin Name: XWOO Extensions
 * Plugin URI: rejuandev.live/plugins/xwoo
 * Description: Woocommerce extension plugin
 * Version: 1.0.0
 * Author: Rejuan Ahamed
 * Author URI: rejuandev.live
 * Text Domain: xwoo
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
define('XWOO_FILE', __FILE__);
define('XWOO_VERSION', '1.0.0');
define('XWOO_DIR_URL', plugin_dir_url( XWOO_FILE ));
define('XWOO_DIR_PATH', plugin_dir_path( XWOO_FILE ));
define('XWOO_BASENAME', plugin_basename( XWOO_FILE ));
/**
* Load Text Domain Language
*/
add_action('init', 'XWOO_language_load');
function XWOO_language_load(){
    load_plugin_textdomain('xwoo', false, basename(dirname( XWOO_FILE )).'/languages/');
}

if (!function_exists('xwoo_function')) {
    function xwoo_function() {
        require_once XWOO_DIR_PATH . 'includes/Functions.php';
        return new \XWOO\Functions();
    }
}

if (!class_exists( 'Crowdfunding' )) {
    require_once XWOO_DIR_PATH . 'includes/Crowdfunding.php';
    new \XWOO\Crowdfunding();
}