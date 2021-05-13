<?php
namespace XWOO\settings;

defined( 'ABSPATH' ) || exit;

class Admin_Menu {

    public function __construct() {
        add_action('wp_head',      array($this, 'style_custom_css' ));
        add_action('admin_menu',   array($this, 'register_menu_page' ));
        add_action('admin_init',   array($this, 'save_menu_settings' ));
    }

    /**
     * Xwoo Custom Styling Option
     */
    public function style_custom_css(){

        if( 'true' == get_option('wp_enable_color_styling') ){
            $color_scheme       = get_option( 'wp_color_scheme' );
            $button_bg          = get_option( 'wp_button_bg_color' );
            $button_bg_hover    = get_option( 'wp_button_bg_hover_color' );
            $button_text_color  = get_option( 'wp_button_text_color' );
            $text_hover_color   = get_option( 'wp_button_text_hover_color' );
            $custom_css         = get_option( 'wp_custom_css' );
    
            $style = '';
    
            if( $button_bg ){
                $style .= '.xwoo-save-btn{ background-color:'.$button_bg.'; color:'.$button_text_color.'; }';
                $style .= '.xwoo-save-btn:hover{ background-color:'.$button_bg_hover.'; color:'.$text_hover_color.'; }';
            }
    
            if( $color_scheme ){
                $style .=  '#neo-progressbar > div,
                            ul.xwoo-update li:hover span.round-circle,
                            .xwoo-links li a:hover, .xwoo-links li.active a,#neo-progressbar > div {
                                background-color: '.$color_scheme.';
                            }';
    
                list($r, $g, $b) = sscanf( $color_scheme, "#%02x%02x%02x" );
                $style .=  '.tab-rewards-wrapper .overlay { background: rgba('.$r.','.$g.','.$b.',.95); }';
            }
    
            if( $custom_css ){ $style .= $custom_css; }
    
            $output = '<style type="text/css"> '.$style.' </style>';
            echo $output;
        }
    }
    
    /**
     * XWOO Menu Option Page
     */
    public function register_menu_page(){
        add_menu_page( 
            'XWOO Extensions',
            'XWOO Extensions',
            'manage_options',
            'xwoo',
            '',
            'dashicons-xing', 
            null
        );

        $addon_pro =  __('Extensions', 'xwoo');
        if( !defined('XWOO_PRO_FILE') ){
            $addon_pro = __('Extensions <span class="dashicons dashicons-star-filled" style="color:#ef450b"/>', 'xwoo');
        }
        add_submenu_page(
            'xwoo',
            __('Extensions', 'xwoo'),
            $addon_pro,
            'manage_options',
            'xwoo',
            array( $this, 'xwoo_manage_extensions' )
        );
        add_submenu_page(
            'xwoo',
            __( 'Settings', 'xwoo' ),
            __( 'Settings', 'xwoo' ),
            'manage_options',
            'xwoo-settings',
            array( $this, 'xwoo_menu_page' )
        );
    }

    // Addon Listing
    public function xwoo_manage_extensions() {
        include XWOO_DIR_PATH.'settings/view/Addons.php';
    }

    /**
     * Display a custom menu page
     */
    public function xwoo_menu_page(){
        // Settings Tab With slug and Display name
        $tabs = apply_filters('xwoo_settings_panel_tabs', array(
                'general' => array(
                    'tab_name' => __('General Settings','xwoo'),
                    'load_form_file' => XWOO_DIR_PATH.'settings/tabs/Tab_General.php'
                ),
                'style' => array(
                    'tab_name' => __('Style','xwoo'),
                    'load_form_file' => XWOO_DIR_PATH.'settings/tabs/Tab_Style.php'
                ),
                'fields' => array(
                    'tab_name' => __('Field Settings','xwoo'),
                    'load_form_file' => XWOO_DIR_PATH.'settings/tabs/Tab_Fields.php'
                ),
            )
        );

        if( class_exists( 'WooCommerce' ) ){
            $woo_tab = array(
                'tab_name' => __('WooCommerce Settings','xwoo'),
                'load_form_file' => XWOO_DIR_PATH.'settings/tabs/Tab_Woocommerce.php'
            );
            $tabs = array_slice($tabs, 0, 1, true) + array('woocommerce' => $woo_tab) + array_slice($tabs, 1, count($tabs), true);
        }

        $current_page = 'general';
        if( ! empty($_GET['tab']) ){
            $current_page = sanitize_text_field($_GET['tab']);
        }

        if (xwoo_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.__( "Settings have been Saved.", "xwoo" ).'</p>';
            echo '</div>';
        }

        // Print the Tab Title
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=xwoo-settings&tab=$tab'>{$name['tab_name']}</a>";
        }
        echo '</h2>';
        ?>

        <form id="xwoo" role="form" method="post" action="">
            <?php
            //Load tab file
            $default_file = XWOO_DIR_PATH.'settings/tabs/Tab_General.php';

            if( array_key_exists(trim(esc_attr($current_page)), $tabs) ){
                if( file_exists($default_file) ){
                    include_once $tabs[$current_page]['load_form_file'];
                }else{
                    include_once $default_file;
                }
            }else{
                include_once $default_file;
            }
            wp_nonce_field( 'wp_settings_page_action', 'wp_settings_page_nonce_field' );
            submit_button( null, 'primary', 'wp_admin_settings_submit_btn' );
            ?>
            <a href="javascript:;" class="button xwoo-reset-btn"> <i class="dashicons dashicons-image-rotate"></i> <?php _e('Reset Settings', 'xwoo'); ?></a>
        </form>
        <?php
    }


    /**
     * Add menu settings action
     */
    public function save_menu_settings() {
        
        if (xwoo_function()->post('wp_settings_page_nonce_field') && wp_verify_nonce( sanitize_text_field(xwoo_function()->post('wp_settings_page_nonce_field')), 'wp_settings_page_action' ) ){

            $current_tab = sanitize_text_field(xwoo_function()->post('wp_xwoo_admin_tab'));
            if( ! empty($current_tab) ){
                if ( $current_tab == 'tab_fields' ){

                    $description = sanitize_text_field(xwoo_function()->post('xwoo_show_description'));
                    xwoo_function()->update_checkbox('xwoo_show_description', $description);

                }

                /**
                 * General Settings
                 */
                if ( $current_tab == 'tab_general' ){

                    $vendor_type = sanitize_text_field(xwoo_function()->post('vendor_type'));
                    xwoo_function()->update_text('vendor_type', $vendor_type);

                    $campaign_status = sanitize_text_field(xwoo_function()->post('wp_default_campaign_status'));
                    xwoo_function()->update_text('wp_default_campaign_status', $campaign_status);
                    
                    $edit_status = sanitize_text_field(xwoo_function()->post('wp_campaign_edit_status'));
                    xwoo_function()->update_text('wp_campaign_edit_status', $edit_status);

                    $paypal_per_campaign_email = sanitize_text_field(xwoo_function()->post('wp_enable_paypal_per_campaign_email'));
                    xwoo_function()->update_checkbox('wp_enable_paypal_per_campaign_email', $paypal_per_campaign_email);

                    $role_selector = xwoo_function()->post('wp_user_role_selector');
                    update_option( 'wp_user_role_selector', $role_selector );

                    $role_list = maybe_unserialize(get_option( 'wp_user_role_selector' ));
                    $roles  = get_editable_roles();
                    foreach( $roles as $key=>$role ){
                        if( isset( $role['capabilities']['campaign_form_submit'] ) ){
                            $role = get_role( $key );
                            $role->remove_cap( 'campaign_form_submit' );
                        }
                    }

                    if( is_array( $role_list ) ){
                        if( !empty( $role_list ) ){
                            foreach( $role_list as $val ){
                                $role = get_role( $val );
                                $role->add_cap( 'campaign_form_submit' );
                                $role->add_cap( 'upload_files' );
                            }
                        }
                    }

                    $form_page_id = intval(xwoo_function()->post('wp_form_page_id'));
                    if (!empty($form_page_id)) {
                        global $wpdb;
                        $page_id = $form_page_id;
                        update_option( 'wp_form_page_id', $page_id );

                        //Update That Page with new crowdFunding [wp_xwoo_form]
                        $previous_content = str_replace( array( '[xwoo_form]', '[wp_xwoo_form]' ), array( '', '' ), get_post_field('post_content', $page_id));
                        $new_content = $previous_content . '[xwoo_form]';
                        //Update Post
                        $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
                    }

                    $dashboard_page_id = intval(xwoo_function()->post('wp_xwoo_dashboard_page_id'));
                    if (!empty($dashboard_page_id)) {
                        $page_id = $dashboard_page_id;
                        update_option('wp_xwoo_dashboard_page_id', $page_id);

                        //Update That Page with new crowdFunding [xwoo_dashboard]
                        $previous_content = str_replace( array( '[xwoo_dashboard]', '[wp_xwoo_dashboard]' ), array( '', '' ), get_post_field('post_content', $page_id));
                        $new_content = $previous_content . '[xwoo_dashboard]';
                        //Update Post
                        $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
                    }

                    $xwoo_user_reg_success_redirect_uri = sanitize_text_field(xwoo_function()->post('xwoo_user_reg_success_redirect_uri'));
                    update_option('xwoo_user_reg_success_redirect_uri', $xwoo_user_reg_success_redirect_uri);
                }

                // Listing Page Settings
                if ( $current_tab == 'tab_listing_page' ){
                    $columns  = intval(xwoo_function()->post('number_of_collumn_in_row'));
                    xwoo_function()->update_text('number_of_collumn_in_row', $columns );

                    $description_limits = intval(xwoo_function()->post('number_of_words_show_in_listing_description'));
                    xwoo_function()->update_text('number_of_words_show_in_listing_description', $description_limits );
                    
                    $product_limits = intval(xwoo_function()->post('xwoo_listing_post_number'));
                    xwoo_function()->update_text('xwoo_listing_post_number', $product_limits );

                    $show_rating = sanitize_text_field(xwoo_function()->post('wp_show_rating'));
                    xwoo_function()->update_checkbox('wp_show_rating', $show_rating);
                }

                // WooCommerce Settings
                if ( $current_tab == 'tab_woocommerce' ){
                    $single = sanitize_text_field(xwoo_function()->post('wp_single_page_id'));
                    xwoo_function()->update_checkbox('wp_single_page_id', $single );

                    $listing = intval(sanitize_text_field(xwoo_function()->post('wp_listing_page_id')));
                    xwoo_function()->update_text('wp_listing_page_id', $listing );

                    $form_page = intval(sanitize_text_field(xwoo_function()->post('wp_form_page_id')));
                    xwoo_function()->update_text('wp_form_page_id', $form_page );

                    $selected_theme = sanitize_text_field(xwoo_function()->post('wp_xwoo_selected_theme'));
                    xwoo_function()->update_text('wp_xwoo_selected_theme', $selected_theme );

                    $requirement_title = sanitize_text_field(xwoo_function()->post('wp_requirement_title'));
                    xwoo_function()->update_text('wp_requirement_title', $requirement_title);

                    $requirement = sanitize_text_field(xwoo_function()->post('wp_requirement_text'));
                    xwoo_function()->update_text('wp_requirement_text', $requirement );

                    $agree_title = sanitize_text_field(xwoo_function()->post('wp_requirement_agree_title'));
                    xwoo_function()->update_text('wp_requirement_agree_title', $agree_title);

                    $collumns  = intval(xwoo_function()->post('number_of_collumn_in_row'));
                    xwoo_function()->update_text('number_of_collumn_in_row', $collumns );

                    # Product number.
                    $xwoo_listing_post_number = intval(xwoo_function()->post('xwoo_listing_post_number'));
                    xwoo_function()->update_text('xwoo_listing_post_number', $xwoo_listing_post_number);
                }

                // Style Settings
                if ( $current_tab == 'tab_style' ){
                    $styling = sanitize_text_field(xwoo_function()->post('wp_enable_color_styling'));
                    xwoo_function()->update_checkbox( 'wp_enable_color_styling', $styling);

                    $scheme = sanitize_text_field(xwoo_function()->post('wp_color_scheme'));
                    xwoo_function()->update_text('wp_color_scheme', $scheme);

                    $button_bg_color = sanitize_text_field(xwoo_function()->post('wp_button_bg_color'));
                    xwoo_function()->update_text('wp_button_bg_color', $button_bg_color);

                    $button_bg_hover_color = sanitize_text_field(xwoo_function()->post('wp_button_bg_hover_color'));
                    xwoo_function()->update_text('wp_button_bg_hover_color', $button_bg_hover_color);

                    $button_text_color = sanitize_text_field(xwoo_function()->post('wp_button_text_color'));
                    xwoo_function()->update_text('wp_button_text_color', $button_text_color);

                    $button_text_hover_color = sanitize_text_field(xwoo_function()->post('wp_button_text_hover_color'));
                    xwoo_function()->update_text('wp_button_text_hover_color', $button_text_hover_color);

                    $custom_css = xwoo_function()->post( 'wp_custom_css' );
                    xwoo_function()->update_text( 'wp_custom_css', $custom_css );
                }
            }
        }
    }
}
