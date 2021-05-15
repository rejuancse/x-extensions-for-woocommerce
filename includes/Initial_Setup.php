<?php
namespace XWOO;

defined( 'ABSPATH' ) || exit;

if (! class_exists('Initial_Setup')) {

    class Initial_Setup {

        public function __construct() {
            add_action( 'admin_init', array( $this, 'initial_compatibility_check') );
            add_action('wp_ajax_install_woocommerce_plugin',        array($this, 'install_woocommerce_plugin'));
            add_action('admin_action_activate_woocommerce_free',    array($this, 'activate_woocommerce_free'));
        }

        public function initial_compatibility_check() {
            if (version_compare(XWOO_VERSION, '1.0.0', '>')) {
                $option_check = get_option('xwoo_show_description');
                if($option_check != 'true' && $option_check != 'false'){
                    $default_value = array(
                        'xwoo_show_description' => 'true',
                        'xwoo_show_terms_and_conditions' => 'true'
                    );
                    foreach ($default_value as $key => $value ) {
                        update_option( $key , $value );
                    }
                }
            }
        }
        
        /**
         * Do some task during plugin activation
         */
        public function initial_plugin_activation() {
            if (get_option('wp_xwoo_is_used')) { // Check is plugin used before or not
                return false;
            }
            self::update_option();
        }

        /**
         * Insert settings option data
         */
        public function update_option() {
            $init_setup_data = array(
                'wp_xwoo_is_used' => XWOO_VERSION,
                'wp_xwoo_selected_theme' => 'basic',
                'vendor_type' => 'woocommerce',
                'wp_default_campaign_status' => 'draft',
                'wp_campaign_edit_status' => 'pending',
                'wp_enable_color_styling' => 'true',
                'xwoo_show_description' => 'true',
                'wp_enable_paypal_per_campaign_email' => 'true',
                'wp_single_page_id' => 'true',
                'wp_requirement_agree_title' => 'I agree with the terms and conditions.',
            );

            foreach ($init_setup_data as $key => $value ) {
                update_option( $key , $value );
            }
    
            //Upload Permission
            update_option( 'wp_user_role_selector', array('administrator', 'editor', 'author', 'shop_manager') );
            $role_list = get_option( 'wp_user_role_selector' );
            if( is_array( $role_list ) ){
                if( !empty( $role_list ) ){
                    foreach( $role_list as $val ){
                        $role = get_role( $val );
                        if ($role){
	                        $role->add_cap( 'campaign_form_submit' );
	                        $role->add_cap( 'upload_files' );
                        }
                    }
                }
            }
        }

        /**
         * Reset method, the ajax will call that method for Reset Settings
         */
        public function settings_reset() {
            self::update_option();
        }

        /**
         * Deactivation Hook For Xwoo
         */
        public function initial_plugin_deactivation(){

        }

        public function activation_css() {
            ?>
            <style type="text/css">
                .xwoo-install-notice{
                    padding: 20px;
                }
                .xwoo-install-notice-inner{
                    display: flex;
                    align-items: center;
                }
                .xwoo-install-notice-inner .button{
                    padding: 5px 30px;
                    height: auto;
                    line-height: 20px;
                    text-transform: capitalize;
                }
                .xwoo-install-notice-content{
                    flex-grow: 1;
                    padding-left: 20px;
                    padding-right: 20px;
                }
                .xwoo-install-notice-icon img{
                    width: 64px;
                    border-radius: 4px;
                    display: block;
                }
                .xwoo-install-notice-content h2{
                    margin-top: 0;
                    margin-bottom: 5px;
                }
                .xwoo-install-notice-content p{
                    margin-top: 0;
                    margin-bottom: 0px;
                    padding: 0;
                }
            </style>

            <script type="text/javascript">
                jQuery(document).ready(function($){
                    'use strict';
                    $(document).on('click', '.install-xwoo-button', function(e){
                        e.preventDefault();
                        var $btn = $(this);
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {install_plugin: 'woocommerce', action: 'install_woocommerce_plugin'},
                            beforeSend: function(){
                                $btn.addClass('updating-message');
                            },
                            success: function (data) {
                                $('.install-xwoo-button').remove();
                                $('#XWOO_install_msg').html(data);
                            },
                            complete: function () {
                                $btn.removeClass('updating-message');
                            }
                        });
                    });
                });
            </script>
            <?php
        }
        /**
         * Show notice if there is no woocommerce
         */
        public function free_plugin_installed_but_inactive_notice(){
            $this->activation_css();
            ?>
            <div class="notice notice-error xwoo-install-notice">
                <div class="xwoo-install-notice-inner">
                    <div class="xwoo-install-notice-icon">
                        <img src="<?php echo XWOO_DIR_URL.'assets/images/woocommerce-icon.png'; ?>" alt="logo" />
                    </div>
                    <div class="xwoo-install-notice-content">
                        <h2><?php _e('Thanks for using WP Xwoo', 'xwoo'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','xwoo'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','xwoo'), 
                                __('installed and activated on this website in order to use WP Xwoo.','xwoo')
                            );
                        ?>
                        <a href="https://docs.xwoo.com/wp-xwoo/" target="_blank"><?php _e('Learn more about WP Xwoo', 'xwoo'); ?></a>
                    </div>
                    <div class="xwoo-install-notice-button">
                        <a  class="button button-primary" href="<?php echo add_query_arg(array('action' => 'activate_woocommerce_free'), admin_url()); ?>"><?php _e('Activate WooCommerce', 'xwoo'); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function free_plugin_not_installed(){
            include( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $this->activation_css();
            ?>
            <div class="notice notice-error xwoo-install-notice">
                <div class="xwoo-install-notice-inner">
                    <div class="xwoo-install-notice-icon">
                        <img src="<?php echo XWOO_DIR_URL.'assets/images/woocommerce-icon.png'; ?>" alt="logo" />
                    </div>
                    <div class="xwoo-install-notice-content">
                        <h2><?php _e('Thanks for using XWOO Extensions Plugins', 'xwoo'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','xwoo'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','xwoo'), 
                                __('installed and activated on this website in order to use XWOO.','xwoo')
                            );
                        ?>
                        <a href="https://docs.xwoo.com/xwoo/" target="_blank"><?php _e('Learn more about XWOO', 'xwoo'); ?></a>
                    </div>
                    <div class="xwoo-install-notice-button">
                        <a class="install-xwoo-button button button-primary" data-slug="woocommerce" href="<?php echo add_query_arg(array('action' => 'install_woocommerce_free'), admin_url()); ?>"><?php _e('Install WooCommerce', 'xwoo'); ?></a>
                    </div>
                </div>
                <div id="XWOO_install_msg"></div>
            </div>
            <?php
        }

        public function activate_woocommerce_free() {
            activate_plugin('woocommerce/woocommerce.php' );
            wp_redirect(admin_url('admin.php?page=xwoo'));
		    exit();
        }

        public function install_woocommerce_plugin(){
            include(ABSPATH . 'wp-admin/includes/plugin-install.php');
            include(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    
            if ( ! class_exists('Plugin_Upgrader')){
                include(ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');
            }
            if ( ! class_exists('Plugin_Installer_Skin')) {
                include( ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php' );
            }
    
            $plugin = 'woocommerce';
    
            $api = plugins_api( 'plugin_information', array(
                'slug' => $plugin,
                'fields' => array(
                    'short_description' => false,
                    'sections' => false,
                    'requires' => false,
                    'rating' => false,
                    'ratings' => false,
                    'downloaded' => false,
                    'last_updated' => false,
                    'added' => false,
                    'tags' => false,
                    'compatibility' => false,
                    'homepage' => false,
                    'donate_link' => false,
                ),
            ) );
    
            if ( is_wp_error( $api ) ) {
                wp_die( $api );
            }
    
            $title = sprintf( __('Installing Plugin: %s'), $api->name . ' ' . $api->version );
            $nonce = 'install-plugin_' . $plugin;
            $url = 'update.php?action=install-plugin&plugin=' . urlencode( $plugin );
    
            $upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
            $upgrader->install($api->download_link);
            die();
        }
        
        public static function wc_low_version(){
            printf(
                '<div class="notice notice-error is-dismissible"><p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p></div>', 
                __('Your','xwoo'), 
                'https://wordpress.org/plugins/woocommerce/', 
                __('WooCommerce','xwoo'), 
                __('version is below then 3.0, please update.','xwoo') 
            );
        }
    }
}
