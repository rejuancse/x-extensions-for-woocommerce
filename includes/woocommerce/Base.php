<?php
namespace XWOO\woocommerce;

defined( 'ABSPATH' ) || exit;

class Base {

    /**
     * @var null
     *
     * Instance of this class
     */
    protected static $_instance = null;

    /**
     * @return null|Base
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Base constructor.
     *
     * @hook
     */
    public function __construct() {
        add_action('admin_enqueue_scripts',            array($this, 'admin_script')); //Add Additional backend js and css
        add_action('wp_enqueue_scripts',               array($this, 'frontend_script')); //Add frontend js and css
        add_action('init',                             array($this, 'media_pluggable'));
        add_action('admin_head',                       array($this, 'add_mce_button'));
        add_action('wp_ajax_xwoo_settings_reset',      array($this, 'settings_reset'));
        add_action('wp_ajax_xwoo_addon_enable_disable',array($this, 'addon_enable_disable'));
        add_filter('admin_footer_text',                 array($this, 'admin_footer_text'), 2); 
    }
    
    public function media_pluggable(){
        if (is_user_logged_in()){
            if(is_admin()){
                if (current_user_can('product_form_submit')){
                    add_action( 'pre_get_posts', array($this, 'set_user_own_media') );
                }
            }
        }
    }

    // Attachment Filter
    public function set_user_own_media($query){
        if ($query) {
            if (! empty($query->query['post_type'])) {
                if(!current_user_can('administrator')){
                    if ($query->query['post_type'] == 'attachment') {
                        $user = wp_get_current_user();
                        $query->set('author', $user->ID);
                    }
                }
            }
        }
    }

    // Hooks your functions into the correct filters
    function add_mce_button() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_buttons', array($this, 'register_mce_button') );
        }
    }

    public function admin_script(){
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'wp-xwoo-css', XWOO_DIR_URL .'assets/css/xwoo-admin.css', false, XWOO_VERSION );
        wp_enqueue_script( 'xwoo-jquery-scripts', XWOO_DIR_URL .'assets/js/xwoo.min.js', array('jquery','wp-color-picker'), XWOO_VERSION, true );
    }

    /**
     * Registering necessary js and css
     * @ Frontend
     */
    public function frontend_script(){
        wp_enqueue_style( 'xwoo-css-front', XWOO_DIR_URL .'assets/css/xwoo-front.css', false, XWOO_VERSION );
        wp_enqueue_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
         
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );
        wp_enqueue_script( 'wp-xwoo-front', XWOO_DIR_URL .'assets/js/xwoo-front.js', array('jquery'), XWOO_VERSION, true);
        wp_localize_script( 'wp-xwoo-front', 'xwoo_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_media();
    }

    // Register new button in the editor
    function register_mce_button( $buttons ) {
        array_push( $buttons, 'xwoo_button' );
        return $buttons;
    }

    public function admin_footer_text($footer_text){
        $footer_text = sprintf( __( 'Thanks so much for using <strong>XWOO Extensions</strong>', 'xwoo' ));
        return $footer_text;
    }

    /**
     * Reset method
     */

    public function settings_reset(){
        $initial_setup = new \XWOO\Initial_Setup();
        $initial_setup->settings_reset();
    }

    /**
     * Method for enable / disable extensions
     */
    public function addon_enable_disable(){
        $extensionsConfig = maybe_unserialize(get_option('xwoo_extensions_config'));
        $isEnable = (bool) sanitize_text_field( xwoo_function()->avalue_dot('isEnable', $_POST) );
        $addonFieldName = sanitize_text_field( xwoo_function()->avalue_dot('addonFieldName', $_POST) );
        $extensionsConfig[$addonFieldName]['is_enable'] = ($isEnable) ? 1 : 0;
        update_option('xwoo_extensions_config', $extensionsConfig);
        wp_send_json_success();
    }
}
