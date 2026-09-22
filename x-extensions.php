<?php
/**
 * Plugin Name: X-Extensions for WooCommerce
 * Description: Enhance WooCommerce with AJAX product search, quick view popup & product listing grid. Free WooCommerce extensions to boost UX, speed & sales.
 * Author: Rejuan Ahamed
 * Version: 2.0.0
 * Requires at least: 6.2
 * Requires Plugins: woocommerce
 * Requires PHP: 7.4
 * Tested up to: 7.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: x-extensions-for-woocommerce
 * Domain Path: /languages/
 *
 * @package x-extensions-for-woocommerce
 */

defined( 'ABSPATH' ) || exit;

/**
* @Type
* @Version
* @Directory URL
* @Directory Path
* @Plugin Base Name
*/
define('XEWC_FILE', __FILE__);
define('XEWC_VERSION', '2.0.0');
define('XEWC_DIR_URL', plugin_dir_url( XEWC_FILE ));
define('XEWC_DIR_PATH', plugin_dir_path( XEWC_FILE ));
define('XEWC_BASENAME', plugin_basename( XEWC_FILE ));

/**
* Load Text Domain Language
*/
add_action('init', 'xewc_language_load');
function xewc_language_load(){
    load_plugin_textdomain('x-extensions-for-woocommerce', false, basename(dirname( XEWC_FILE )).'/languages/');
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
