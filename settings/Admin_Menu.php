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
     * Crowdfunding Custom Styling Option
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
                $style .= '.wp_donate_button, 
                            #xwoo-tab-reviews .submit,
                            .xwoo-edit-btn,
                            .xwoo-image-upload.float-right,
                            .xwoo-image-upload-btn,
                            .xwoo-save-btn,
                            #wp_active_edit_form,
                            .removeCampaignRewards,
                            #addreward,
                            .btn-style1,
                            #addcampaignupdate,
                            .xwoo-profile-button,
                            .dashboard-btn-link,
                            .wp_login_form_div #wp-submit,
                            .xwoo-submit-campaign,
                            input[type="button"].xwoo-image-upload,
                            input[type="button"]#search-submit,
                            #addreward,input[type="submit"].xwoo-submit-campaign,
                            .dashboard-btn-link,.label-primary,
                            #xwoofrontenddata input[type="button"],
                            #xwoofrontenddata .xwoo-form-action input[type="submit"].xwoo-submit-campaign,
                            .btn-style1,#xwoo-tab-reviews .submit,.dashboard-head-date input[type="submit"],
                            .wp-crowd-btn-primary, .wp_withdraw_button,.xwoo-dashboard-head-left ul li.active,
                            .xwoo-pagination ul li a:hover, .xwoo-pagination ul li span.current{ background-color:'.$button_bg.'; color:'.$button_text_color.'; }';
    
                $style .= '.wp_donate_button:hover, 
                            #xwoo-tab-reviews .submit:hover,
                            .xwoo-edit-btn:hover,
                            .xwoo-image-upload.float-right:hover,
                            .xwoo-image-upload-btn:hover,
                            .xwoo-save-btn:hover,
                            .removeCampaignRewards:hover,
                            #addreward:hover,
                            .removecampaignupdate:hover,
                            .btn-style1:hover,
                            #addcampaignupdate:hover,
                            #wp_active_edit_form:hover,
                            .removecampaignupdate:hover,
                            .xwoo-profile-button:hover,
                            .dashboard-btn-link:hover,
                            #xwoofrontenddata input[type="button"]:hover,
                            #xwoofrontenddata .xwoo-form-action input[type="submit"].xwoo-submit-campaign:hover,
                            .wp_login_form_div #wp-submit:hover,
                            .xwoo-submit-campaign:hover,
                            .wp_donate_button:hover,.dashboard-head-date input[type="submit"]:hover,
                            .wp-crowd-btn-primary:hover,
                            .wp_withdraw_button:hover{ background-color:'.$button_bg_hover.'; color:'.$text_hover_color.'; }';
            }
    
            if( $color_scheme ){
                $style .=  '#neo-progressbar > div,
                            ul.xwoo-update li:hover span.round-circle,
                            .xwoo-links li a:hover, .xwoo-links li.active a,#neo-progressbar > div {
                                background-color: '.$color_scheme.';
                            }
                            .xwoo-dashboard-summary ul li.active {
                                background: '.$color_scheme.';
                            }
                            .xwoo-tabs-menu li.xwoo-current {
                                border-bottom: 3px solid '.$color_scheme.';
                            }
                            .xwoo-pagination ul li a:hover,
                            .xwoo-pagination ul li span.current {
                                border: 2px solid '.$color_scheme.';
                            }
                            .xwoo-dashboard-summary ul li.active:after {
                                border-color: '.$color_scheme.' rgba(0, 128, 0, 0) rgba(255, 255, 0, 0) rgba(0, 0, 0, 0);
                            }
                            .xwoo-fields input[type="email"]:focus,
                            .xwoo-fields input[type="text"]:focus,
                            .xwoo-fields select:focus,
                            .xwoo-fields textarea {
                                border-color: '.$color_scheme.';
                            }
                            .xwoo-link-style1,
                            ul.xwoo-update li .xwoo-update-title,
                            .xwoo-fields-action span a:hover,.xwoo-name > p,
                            .xwoo-listings-dashboard .xwoo-listing-content h4 a,
                            .xwoo-listings-dashboard .xwoo-listing-content .xwoo-author a,
                            .XWOO-order-view,#wp_xwoo_modal_message td a,
                            .dashboard-price-number,.wpcrowd-listing-content .wpcrowd-admin-title h3 a,
                            .campaign-listing-page .stripe-table a,.stripe-table  a.label-default:hover,
                            a.xwoo-fund-modal-btn.xwoo-link-style1,.xwoo-tabs-menu li.xwoo-current a,
                            .xwoo-links div a:hover, .xwoo-links div.active a{
                                color: '.$color_scheme.';
                            }
                            .xwoo-links div a:hover .wpcrowd-arrow-down, .xwoo-links div.active a .wpcrowd-arrow-down {
                                border: solid '.$color_scheme.';
                                border-width: 0 2px 2px 0;
                            }
                            .xwoo-listings-dashboard .xwoo-listing-content h4 a:hover,
                            .xwoo-listings-dashboard .xwoo-listing-content .xwoo-author a:hover,
                            #wp_xwoo_modal_message td a:hover{
                                color: rgba('.$color_scheme.','.$color_scheme.','.$color_scheme.',0.95);
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
            'dashicons-rest-api', 
            10 
        );

        $addon_pro =  __('Add-ons', 'xwoo');
        if( !defined('XWOO_PRO_FILE') ){
            $addon_pro = __('Add-ons <span class="dashicons dashicons-star-filled" style="color:#ef450b"/>', 'xwoo');
        }
        add_submenu_page(
            'xwoo',
            __('Add-ons', 'xwoo'),
            $addon_pro,
            'manage_options',
            'xwoo',
            array( $this, 'XWOO_manage_addons' )
        );
        add_submenu_page(
            'xwoo',
            __( 'Settings', 'xwoo' ),
            __( 'Settings', 'xwoo' ),
            'manage_options',
            'XWOO-settings',
            array( $this, 'XWOO_menu_page' )
        );
    }

    // Addon Listing
    public function XWOO_manage_addons() {
        include XWOO_DIR_PATH.'settings/view/Addons.php';
    }


    /**
     * Display a custom menu page
     */
    public function XWOO_menu_page(){
        // Settings Tab With slug and Display name
        $tabs = apply_filters('XWOO_settings_panel_tabs', array(
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

        if (XWOO_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.__( "Settings have been Saved.", "wp-xwoo" ).'</p>';
            echo '</div>';
        }

        // Print the Tab Title
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=XWOO-settings&tab=$tab'>{$name['tab_name']}</a>";
        }
        echo '</h2>';
        ?>

        <form id="XWOO-xwoo" role="form" method="post" action="">
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
        
        if (XWOO_function()->post('wp_settings_page_nonce_field') && wp_verify_nonce( sanitize_text_field(XWOO_function()->post('wp_settings_page_nonce_field')), 'wp_settings_page_action' ) ){

            $current_tab = sanitize_text_field(XWOO_function()->post('wp_xwoo_admin_tab'));
            if( ! empty($current_tab) ){
                if ( $current_tab == 'tab_fields' ){

                    $description = sanitize_text_field(XWOO_function()->post('XWOO_show_description'));
                    XWOO_function()->update_checkbox('XWOO_show_description', $description);

                    $short_description = sanitize_text_field(XWOO_function()->post('XWOO_show_short_description'));
                    XWOO_function()->update_checkbox('XWOO_show_short_description', $short_description);

                    $category = sanitize_text_field(XWOO_function()->post('XWOO_show_category'));
                    XWOO_function()->update_checkbox('XWOO_show_category', $category);

                    $tag = sanitize_text_field(XWOO_function()->post('XWOO_show_tag'));
                    XWOO_function()->update_checkbox('XWOO_show_tag', $tag);

                    $feature = sanitize_text_field(XWOO_function()->post('XWOO_show_feature'));
                    XWOO_function()->update_checkbox('XWOO_show_feature', $feature);

                    $video = sanitize_text_field(XWOO_function()->post('XWOO_show_video'));
                    XWOO_function()->update_checkbox('XWOO_show_video', $video);

                    $end_method = sanitize_text_field(XWOO_function()->post('XWOO_show_end_method'));
                    XWOO_function()->update_checkbox('XWOO_show_end_method', $end_method);

                    $target_goal = sanitize_text_field(XWOO_function()->post('wp_show_target_goal'));
                    XWOO_function()->update_checkbox('wp_show_target_goal', $target_goal);

                    $target_date = sanitize_text_field(XWOO_function()->post('wp_show_target_date'));
                    XWOO_function()->update_checkbox('wp_show_target_date', $target_date);

                    $target_goal_and_date = sanitize_text_field(XWOO_function()->post('wp_show_target_goal_and_date'));
                    XWOO_function()->update_checkbox('wp_show_target_goal_and_date', $target_goal_and_date);

                    $campaign_never_end = sanitize_text_field(XWOO_function()->post('wp_show_campaign_never_end'));
                    XWOO_function()->update_checkbox('wp_show_campaign_never_end', $campaign_never_end);

                    $start_date = sanitize_text_field(XWOO_function()->post('XWOO_show_start_date'));
                    XWOO_function()->update_checkbox('XWOO_show_start_date', $start_date);

                    $end_date = sanitize_text_field(XWOO_function()->post('XWOO_show_end_date'));
                    XWOO_function()->update_checkbox('XWOO_show_end_date', $end_date);

                    $min_price = sanitize_text_field(XWOO_function()->post('wp_show_min_price'));
                    XWOO_function()->update_checkbox('wp_show_min_price', $min_price);

                    $max_price = sanitize_text_field(XWOO_function()->post('wp_show_max_price'));
                    XWOO_function()->update_checkbox('wp_show_max_price', $max_price);

                    $recommended_price = sanitize_text_field(XWOO_function()->post('wp_show_recommended_price'));
                    XWOO_function()->update_checkbox('wp_show_recommended_price', $recommended_price);

                    $funding_goal = sanitize_text_field(XWOO_function()->post('XWOO_show_funding_goal'));
                    XWOO_function()->update_checkbox('XWOO_show_funding_goal', $funding_goal);

                    $predefined_amount = sanitize_text_field(XWOO_function()->post('XWOO_show_predefined_amount'));
                    XWOO_function()->update_checkbox('XWOO_show_predefined_amount', $predefined_amount);

                    $contributor_table = sanitize_text_field(XWOO_function()->post('XWOO_show_contributor_table'));
                    XWOO_function()->update_checkbox('XWOO_show_contributor_table', $contributor_table);

                    $contributor_anonymity = sanitize_text_field(XWOO_function()->post('XWOO_show_contributor_anonymity'));
                    XWOO_function()->update_checkbox('XWOO_show_contributor_anonymity', $contributor_anonymity);

                    $country = sanitize_text_field(XWOO_function()->post('XWOO_show_country'));
                    XWOO_function()->update_checkbox('XWOO_show_country', $country);

                    $location = sanitize_text_field(XWOO_function()->post('XWOO_show_location'));
                    XWOO_function()->update_checkbox('XWOO_show_location', $location);

                    $reward_image = sanitize_text_field(XWOO_function()->post('XWOO_show_reward_image'));
                    XWOO_function()->update_checkbox('XWOO_show_reward_image', $reward_image);

                    $reward = sanitize_text_field(XWOO_function()->post('XWOO_show_reward'));
                    XWOO_function()->update_checkbox('XWOO_show_reward', $reward);

                    $estimated_delivery_month = sanitize_text_field(XWOO_function()->post('XWOO_show_estimated_delivery_month'));
                    XWOO_function()->update_checkbox('XWOO_show_estimated_delivery_month', $estimated_delivery_month);

                    $estimated_delivery_year = sanitize_text_field(XWOO_function()->post('XWOO_show_estimated_delivery_year'));
                    XWOO_function()->update_checkbox('XWOO_show_estimated_delivery_year', $estimated_delivery_year);

                    $quantity = sanitize_text_field(XWOO_function()->post('XWOO_show_quantity'));
                    XWOO_function()->update_checkbox('XWOO_show_quantity', $quantity);

                    $terms_and_conditions = sanitize_text_field(XWOO_function()->post('XWOO_show_terms_and_conditions'));
                    XWOO_function()->update_checkbox('XWOO_show_terms_and_conditions', $terms_and_conditions);
                }

                /**
                 * General Settings
                 */
                if ( $current_tab == 'tab_general' ){

                    $vendor_type = sanitize_text_field(XWOO_function()->post('vendor_type'));
                    XWOO_function()->update_text('vendor_type', $vendor_type);

                    $campaign_status = sanitize_text_field(XWOO_function()->post('wp_default_campaign_status'));
                    XWOO_function()->update_text('wp_default_campaign_status', $campaign_status);
                    
                    $edit_status = sanitize_text_field(XWOO_function()->post('wp_campaign_edit_status'));
                    XWOO_function()->update_text('wp_campaign_edit_status', $edit_status);

                    $paypal_per_campaign_email = sanitize_text_field(XWOO_function()->post('wp_enable_paypal_per_campaign_email'));
                    XWOO_function()->update_checkbox('wp_enable_paypal_per_campaign_email', $paypal_per_campaign_email);

                    $role_selector = XWOO_function()->post('wp_user_role_selector');
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

                    $form_page_id = intval(XWOO_function()->post('wp_form_page_id'));
                    if (!empty($form_page_id)) {
                        global $wpdb;
                        $page_id = $form_page_id;
                        update_option( 'wp_form_page_id', $page_id );

                        //Update That Page with new crowdFunding [wp_xwoo_form]
                        $previous_content = str_replace( array( '[XWOO_form]', '[wp_xwoo_form]' ), array( '', '' ), get_post_field('post_content', $page_id));
                        $new_content = $previous_content . '[XWOO_form]';
                        //Update Post
                        $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
                    }

                    $dashboard_page_id = intval(XWOO_function()->post('wp_xwoo_dashboard_page_id'));
                    if (!empty($dashboard_page_id)) {
                        $page_id = $dashboard_page_id;
                        update_option('wp_xwoo_dashboard_page_id', $page_id);

                        //Update That Page with new crowdFunding [XWOO_dashboard]
                        $previous_content = str_replace( array( '[XWOO_dashboard]', '[wp_xwoo_dashboard]' ), array( '', '' ), get_post_field('post_content', $page_id));
                        $new_content = $previous_content . '[XWOO_dashboard]';
                        //Update Post
                        $wpdb->update($wpdb->posts, array('post_content' => $new_content), array('ID'=> $page_id));
                    }

                    $XWOO_user_reg_success_redirect_uri = sanitize_text_field(XWOO_function()->post('XWOO_user_reg_success_redirect_uri'));
                    update_option('XWOO_user_reg_success_redirect_uri', $XWOO_user_reg_success_redirect_uri);
                }


                // Listing Page Settings
                if ( $current_tab == 'tab_listing_page' ){
                    $columns  = intval(XWOO_function()->post('number_of_collumn_in_row'));
                    XWOO_function()->update_text('number_of_collumn_in_row', $columns );

                    $description_limits = intval(XWOO_function()->post('number_of_words_show_in_listing_description'));
                    XWOO_function()->update_text('number_of_words_show_in_listing_description', $description_limits );
                    

                    $product_limits = intval(XWOO_function()->post('XWOO_listing_post_number'));
                    XWOO_function()->update_text('XWOO_listing_post_number', $product_limits );




                    $show_rating = sanitize_text_field(XWOO_function()->post('wp_show_rating'));
                    XWOO_function()->update_checkbox('wp_show_rating', $show_rating);
                }


                // Single Page Settings
                if ( $current_tab == 'tab_single_page' ){
                    $reward_design = intval(XWOO_function()->post('wp_single_page_reward_design'));
                    XWOO_function()->update_text('wp_single_page_reward_design', $reward_design);

                    $fixed_price = sanitize_text_field(XWOO_function()->post('wp_reward_fixed_price'));
                    XWOO_function()->update_checkbox('wp_reward_fixed_price', $fixed_price);
                }


                // WooCommerce Settings
                if ( $current_tab == 'tab_woocommerce' ){
                    $hide_shop_page = sanitize_text_field(XWOO_function()->post('hide_cf_campaign_from_shop_page'));
                    XWOO_function()->update_checkbox('hide_cf_campaign_from_shop_page', $hide_shop_page );

                    $single = sanitize_text_field(XWOO_function()->post('wp_single_page_id'));
                    XWOO_function()->update_checkbox('wp_single_page_id', $single );

                    $from_checkout = sanitize_text_field(XWOO_function()->post('hide_cf_address_from_checkout'));
                    XWOO_function()->update_checkbox('hide_cf_address_from_checkout', $from_checkout );

                    $listing = intval(sanitize_text_field(XWOO_function()->post('wp_listing_page_id')));
                    XWOO_function()->update_text('wp_listing_page_id', $listing );

                    $form_page = intval(sanitize_text_field(XWOO_function()->post('wp_form_page_id')));
                    XWOO_function()->update_text('wp_form_page_id', $form_page );

                    $registration = intval(sanitize_text_field(XWOO_function()->post('wp_registration_page_id')));
                    XWOO_function()->update_text('wp_registration_page_id', $registration );

                    $categories = sanitize_text_field(XWOO_function()->post('seperate_xwoo_categories'));
                    XWOO_function()->update_checkbox('seperate_xwoo_categories', $categories );

                    $selected_theme = sanitize_text_field(XWOO_function()->post('wp_cf_selected_theme'));
                    XWOO_function()->update_text('wp_cf_selected_theme', $selected_theme );

                    $requirement_title = sanitize_text_field(XWOO_function()->post('wp_requirement_title'));
                    XWOO_function()->update_text('wp_requirement_title', $requirement_title);

                    $requirement = sanitize_text_field(XWOO_function()->post('wp_requirement_text'));
                    XWOO_function()->update_text('wp_requirement_text', $requirement );

                    $agree_title = sanitize_text_field(XWOO_function()->post('wp_requirement_agree_title'));
                    XWOO_function()->update_text('wp_requirement_agree_title', $agree_title);

                    $cart_redirect = sanitize_text_field(XWOO_function()->post('wp_xwoo_add_to_cart_redirect'));
                    XWOO_function()->update_text('wp_xwoo_add_to_cart_redirect', $cart_redirect);

                    $collumns  = intval(XWOO_function()->post('number_of_collumn_in_row'));
                    XWOO_function()->update_text('number_of_collumn_in_row', $collumns );

                    $number_of_words_show_in_listing_description = intval(XWOO_function()->post('number_of_words_show_in_listing_description'));
                    XWOO_function()->update_text('number_of_words_show_in_listing_description', $number_of_words_show_in_listing_description);

                    # Product number.
                    $XWOO_listing_post_number = intval(XWOO_function()->post('XWOO_listing_post_number'));
                    XWOO_function()->update_text('XWOO_listing_post_number', $XWOO_listing_post_number);


                    $show_rating = sanitize_text_field(XWOO_function()->post('wp_show_rating'));
                    XWOO_function()->update_checkbox('wp_show_rating', $show_rating);

                    //Load single campaign to WooCommerce or not
                    $page_template = sanitize_text_field(XWOO_function()->post('wp_single_page_template'));
                    XWOO_function()->update_checkbox('wp_single_page_template', $page_template);

                    $reward_design = intval(XWOO_function()->post('wp_single_page_reward_design'));
                    XWOO_function()->update_text('wp_single_page_reward_design', $reward_design);

                    $fixed_price = sanitize_text_field(XWOO_function()->post('wp_reward_fixed_price'));
                    XWOO_function()->update_checkbox('wp_reward_fixed_price', $fixed_price);

                    $enable_tax = sanitize_text_field(XWOO_function()->post('XWOO_enable_tax'));
                    XWOO_function()->update_checkbox('XWOO_enable_tax', $enable_tax);
                }

                // Style Settings
                if ( $current_tab == 'tab_style' ){

                    $styling = sanitize_text_field(XWOO_function()->post('wp_enable_color_styling'));
                    XWOO_function()->update_checkbox( 'wp_enable_color_styling', $styling);

                    $scheme = sanitize_text_field(XWOO_function()->post('wp_color_scheme'));
                    XWOO_function()->update_text('wp_color_scheme', $scheme);

                    $button_bg_color = sanitize_text_field(XWOO_function()->post('wp_button_bg_color'));
                    XWOO_function()->update_text('wp_button_bg_color', $button_bg_color);

                    $button_bg_hover_color = sanitize_text_field(XWOO_function()->post('wp_button_bg_hover_color'));
                    XWOO_function()->update_text('wp_button_bg_hover_color', $button_bg_hover_color);

                    $button_text_color = sanitize_text_field(XWOO_function()->post('wp_button_text_color'));
                    XWOO_function()->update_text('wp_button_text_color', $button_text_color);

                    $button_text_hover_color = sanitize_text_field(XWOO_function()->post('wp_button_text_hover_color'));
                    XWOO_function()->update_text('wp_button_text_hover_color', $button_text_hover_color);

                    $custom_css = XWOO_function()->post( 'wp_custom_css' );
                    XWOO_function()->update_text( 'wp_custom_css', $custom_css );
                }
            }
        }
    }

}