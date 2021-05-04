<?php
namespace XWOO\woocommerce;

defined( 'ABSPATH' ) || exit;

class Woocommerce {

    protected static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        add_action( 'plugins_loaded',                                   array($this, 'includes')); //Include all of resource to the plugin 
        add_filter( 'product_type_selector',                            array($this, 'product_type_selector')); //Added one more product type in woocommerce product
        add_action( 'wp_loaded',                                        array($this, 'register_product_type') ); //Initialized the product type class
        add_action( 'woocommerce_product_options_general_product_data', array($this, 'add_meta_info')); //Additional Meta form for xwoo campaign
        add_action( 'add_meta_boxes',                                   array($this, 'add_campaign_update' ), 30 );
        add_action( 'woocommerce_process_product_meta',                 array($this, 'update_status_save')  ); //Save update status for this campaign with product
        add_action( 'woocommerce_process_product_meta',                 array($this, 'custom_field_save')); //Additional meta action, save right this way
        add_filter( 'woocommerce_add_cart_item',                        array($this, 'save_user_donation_to_cookie'), 10, 3 ); //Filter cart item and save donation amount into cookir if product type xwoo
        add_action( 'woocommerce_before_calculate_totals',              array($this, 'add_user_donation')); //Save user input as there preferable amount with cart
        add_filter( 'woocommerce_add_to_cart_redirect',                 array($this, 'redirect_to_checkout')); //Skip cart page after click Donate button, going directly on checkout page
        add_filter( 'woocommerce_get_price_html',                       array($this, 'wc_price_remove'), 10, 2 ); //Hide default price details
        add_filter( 'woocommerce_is_purchasable',                       array($this, 'return_true_woocommerce_is_purchasable'), 10, 2 ); // Return true is purchasable
        add_filter( 'woocommerce_paypal_args',                          array($this, 'custom_override_paypal_email'), 100, 1); // Override paypal reciever email address with campaign creator email
        add_action( 'woocommerce_add_to_cart_validation',               array($this, 'remove_xwoo_item_from_cart'), 10, 5); // Remove xwoo item from cart
        add_action( 'woocommerce_new_order',                            array($this, 'xwoo_order_type')); // Track is this product xwoo.
        add_filter( 'woocommerce_checkout_fields' ,                     array($this, 'override_checkout_fields') ); // Remove billing address from the checkout page
        add_action( 'woocommerce_review_order_before_payment',          array($this, 'check_anonymous_backer'));
        add_action( 'woocommerce_checkout_order_processed',             array($this, 'check_anonymous_backer_post'));
        add_action( 'woocommerce_new_order_item',                       array($this, 'xwoo_new_order_item'), 10, 3);
        add_filter( 'wc_tax_enabled',                                   array($this, 'is_tax_enable_for_xwoo_product'));
        add_action( 'product_cat_edit_form_fields',                     array($this, 'edit_product_taxonomy_field'), 10, 1);
        add_action( 'product_cat_add_form_fields',                      array($this, 'add_checked_xwoo_categories'), 10, 1);
        add_action( 'create_product_cat',                               array($this, 'mark_category_as_xwoo'), 10, 2);
        add_action( 'edit_product_cat',                                 array($this, 'edit_mark_category_as_xwoo'), 10, 2);
        add_filter( "manage_product_cat_custom_column",                 array($this, 'filter_description_col_product_taxomony'), 10, 3);
        add_filter( 'manage_edit-product_cat_columns' ,                 array($this, 'product_taxonomy_is_xwoo_columns'), 10, 1);
    
        //template hooks
        add_action( 'woocommerce_after_shop_loop_item',                 array($this, 'after_item_title_data')); // Woocommerce Backed User
        add_filter( 'woocommerce_product_tabs',                         array($this, 'product_backed_user_tab') );
        add_filter( 'woocommerce_is_sold_individually',                 array($this, 'remove_xwoo_quantity_fields'), 10, 2 ); //Remove quantity and force item 1 cart per checkout if product is xwoo
        if ( 'true' == get_option('hide_xwoo_campaign_from_shop_page' )) {
            add_action('woocommerce_product_query',                     array($this, 'limit_show_xwoo_campaign_in_shop')); //Filter product query
        }
        add_action('woocommerce_product_thumbnails',                    array($this, 'XWOO_campaign_single_love_this') );
        !is_admin() and add_filter( 'woocommerce_coupons_enabled',      array($this, 'wc_coupon_disable') ); //Hide coupon form on checkout page

        add_action( 'wp_logout', array( $this, 'wc_empty_cart' ) );

        $this->filter_product_in_shop_page();
    }


    /**
     * Filter product in shop page
     * @since v.1.4.9
     */
    public function filter_product_in_shop_page(){
        $hide_xwoo_campaign_from_shop_page = (bool) get_option('hide_xwoo_campaign_from_shop_page');
        if(!$hide_xwoo_campaign_from_shop_page){
            return;
        }
        add_action('woocommerce_product_query', array($this, 'filter_woocommerce_product_query'));
    }

    public function filter_woocommerce_product_query($wp_query){
        $wp_query->set('meta_query', array($this->xwoo_product_meta_query()));
        return $wp_query;
    }

    public function xwoo_product_meta_query(){
        $meta_query = array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => array( 'xwoo' ),
            'operator' => 'NOT IN'
        );
        return $meta_query;
    }

    /**
     * @include()
     *
     * Include if necessary resources
     */
    public function includes(){
        include_once XWOO_DIR_PATH .'includes/woocommerce/Reward.php'; 
        new \XWOO\woocommerce\Reward();
    }

    /**
     * Remove billing address from the checkout page
     */
    function override_checkout_fields( $fields ) {
        $xwoo_found = '';
        $items = WC()->cart->get_cart();
        if( $items ){
            foreach($items as $item => $values) {
                $product = wc_get_product( $values['product_id'] );
                if( $product->get_type() == 'xwoo' ){
                    if( 'true' == get_option('hide_xwoo_address_from_checkout','') ) {
                        unset($fields['billing']['billing_first_name']);
                        unset($fields['billing']['billing_last_name']);
                        unset($fields['billing']['billing_company']);
                        unset($fields['billing']['billing_address_1']);
                        unset($fields['billing']['billing_address_2']);
                        unset($fields['billing']['billing_city']);
                        unset($fields['billing']['billing_postcode']);
                        unset($fields['billing']['billing_country']);
                        unset($fields['billing']['billing_state']);
                        unset($fields['billing']['billing_phone']);
                        unset($fields['order']['order_comments']);
                        unset($fields['billing']['billing_address_2']);
                        unset($fields['billing']['billing_postcode']);
                        unset($fields['billing']['billing_company']);
                        unset($fields['billing']['billing_last_name']);
                        unset($fields['billing']['billing_email']);
                        unset($fields['billing']['billing_city']);
                    }
                }
            }
        }
        return $fields;
    }

    /**
     * @param $product_type
     * @return mixed
     *
     * Added a product type in woocommerce
     */
    function product_type_selector($product_type){
        $product_type['xwoo'] = __( 'Xwoo', 'xwoo' );
        return $product_type;
    }

    /**
     * Registering Xwoo product type in product post woocommerce
     */
    public function register_product_type() {
        require_once XWOO_DIR_PATH.'includes/woocommerce/WC_Product_Type.php';
    }

    /**
     * Additional Meta form for Xwoo plugin
     */
    public static function wp_check_settings($arg){
        $var = get_option($arg,true);
        if( $var == '' || $var == 'false' ){
            return false;
        }else{
            return true;
        }
    }


    function add_meta_info(){
        echo '<div class="options_group show_if_neo_xwoo_options">';

        // Expirey
        woocommerce_wp_text_input( 
            array( 
                'id'            => 'wp_funding_video', 
                'label'         => __( 'Video Url', 'xwoo' ),
                'placeholder'   => __( 'Video url', 'xwoo' ), 
                'description'   => __( 'Enter a video url to show your video in campaign details page', 'xwoo' ) 
                ) 
        );

        // Expirey
        woocommerce_wp_text_input( 
            array( 
                'id'            => '_nf_duration_start', 
                'label'         => __( 'Start date', 'xwoo' ),
                'placeholder'   => __( 'Start time of this campaign', 'xwoo' ), 
                'description'   => __( 'Enter start of this campaign', 'xwoo' ) 
                ) 
        );

        woocommerce_wp_text_input( 
            array( 
                'id'            => '_nf_duration_end', 
                'label'         => __( 'End date', 'xwoo' ),
                'placeholder'   => __( 'End time of this campaign', 'xwoo' ), 
                'description'   => __( 'Enter end time of this campaign', 'xwoo' ) 
                ) 
        );

        echo '<div class="options_group"></div>';

        if (get_option('wp_show_min_price')) {
            woocommerce_wp_text_input(
                array(
                    'id'            => 'wp_funding_minimum_price', 
                    'label'         => __('Minimum Price', 'xwoo').' ('. get_woocommerce_currency_symbol().')', 
                    'placeholder'   => __('Minimum Price','xwoo'), 
                    'description'   => __('Enter the minimum price', 'xwoo'), 
                    'class'         => 'wc_input_price'
                    )
            );
        }

        if (get_option('wp_show_max_price')) {
            woocommerce_wp_text_input(
                array(
                    'id'            => 'wp_funding_maximum_price', 
                    'label'         => __('Maximum Price', 'xwoo').' ('. get_woocommerce_currency_symbol() . ')', 
                    'placeholder'   => __('Maximum Price','xwoo'), 
                    'description'   => __('Enter the maximum price', 'xwoo'), 
                    'class'         =>'wc_input_price'
                    )
            );
        }

        if (get_option('wp_show_recommended_price')) {
            woocommerce_wp_text_input(
                array(
                    'id'            => 'wp_funding_recommended_price', 
                    'label'         => __('Recommended Price', 'xwoo').' (' . get_woocommerce_currency_symbol() . ')', 
                    'placeholder'   => __('Recommended Price', 'xwoo'), 
                    'description'   => __('Enter the recommended price', 'xwoo'),
                    'class'         => 'wc_input_price'
                    )
            );
        }
        echo '<div class="options_group"></div>';

        woocommerce_wp_text_input(
            array(
                'id'            => 'XWOO_predefined_pledge_amount',
                'label'         => __( 'Predefined Pledge Amount', 'xwoo' ),
                'placeholder'   => __( '10,20,30,40', 'xwoo' ),
                'description'   => __( 'Predefined amount allow you to place the amount in donate box by click, example: <code>10,20,30,40</code>', 'xwoo' )
            )
        );

        echo '<div class="options_group"></div>';

        // Funding goal/ target
        woocommerce_wp_text_input( 
            array( 
                'id'            => '_nf_funding_goal', 
                'label'         => __( 'Funding Goal', 'xwoo' ).' ('.get_woocommerce_currency_symbol().')', 
                'placeholder'   => __( 'Funding goal','xwoo' ), 
                'description'   => __('Enter the funding goal', 'xwoo' ), 
                'class'         => 'wc_input_price' 
                )
        );


        $options = array();
        if (get_option('wp_show_target_goal') == 'true'){
            $options['target_goal'] = __( 'Target Goal','xwoo' );
        }
        if (get_option('wp_show_target_date') == 'true'){
            $options['target_date'] = __( 'Target Date','xwoo' );
        }
        if (get_option('wp_show_target_goal_and_date') == 'true'){
            $options['target_goal_and_date'] = __( 'Target Goal & Date','xwoo' );
        }
        if (get_option('wp_show_campaign_never_end') == 'true'){
            $options['never_end'] = __( 'Campaign Never Ends','xwoo' );
        }

        //Campaign end method
        woocommerce_wp_select(
            array(
                'id'            => 'wp_campaign_end_method',
                'label'         => __('Campaign End Method', 'xwoo'),
                'placeholder'   => __('Country', 'xwoo'),
                'class'         => 'wp_campaign_end_method',
                'options'       => $options
            )
        );
    

        //Show contributor table
        woocommerce_wp_checkbox(
            array(
                'id'            => 'wp_show_contributor_table',
                'label'         => __( 'Show Contributor Table', 'xwoo' ),
                'cbvalue'       => 1,
                'description'   => __( 'Enable this option to display the contributors for this Campaign', 'xwoo' ),
            )
        );

        //Mark contributors as anonymous
        woocommerce_wp_checkbox(
            array(
                'id'            => 'wp_mark_contributors_as_anonymous',
                'label'         => __( 'Mark Contributors as Anonymous', 'xwoo' ),
                'cbvalue'       => 1,
                'description'   => __( 'Enable this option to display the contributors Name as Anonymous for this Campaign', 'xwoo' ),
            )
        );
        echo '<div class="options_group"></div>';


        //Get country select
        $countries_obj      = new \WC_Countries();
        $countries          = $countries_obj->__get('countries');
        array_unshift($countries, 'Select a country');

        //Country list
        woocommerce_wp_select(
            array(
                'id'            => 'wp_country',
                'label'         => __( 'Country', 'xwoo' ),
                'placeholder'   => __( 'Country', 'xwoo' ),
                'class'         => 'select2 wp_country',
                'options'       => $countries
            )
        );

        // Location of this campaign
        woocommerce_wp_text_input( 
            array( 
                'id'            => '_nf_location', 
                'label'         => __( 'Location', 'xwoo' ),
                'placeholder'   => __( 'Location', 'xwoo' ), 
                'description'   => __( 'Location of this campaign','xwoo' ), 
                'type'          => 'text'
            ) 
        );
        do_action( 'new_crowd_funding_campaign_option' );
        echo '</div>';
    }


    public function add_campaign_update(){
        add_meta_box( 'campaign-update-status-meta', __( 'Campaign Update Status', 'xwoo' ), array($this, 'campaign_status_metabox'), 'product', 'normal' );
    }


    public function campaign_status_metabox() {
        global $post;
        $saved_campaign_update = get_post_meta($post->ID, 'wp_campaign_updates', true);
        $saved_campaign_update_a = ( !empty($saved_campaign_update) ) ? json_decode($saved_campaign_update, true) : array();

        $total_campaign_update = count($saved_campaign_update_a);

        $display ='block;';
        if (is_array($saved_campaign_update_a) && $total_campaign_update > 0) {
            $display ='none;';
        }

        echo "<div id='campaign_status' class='panel woocommerce_options_panel update_status'>";

        echo "<div id='campaign_update_field' style='display: $display'>";
            echo "<div class='campaign_update_field_copy'>";

            woocommerce_wp_text_input(
                array(
                    'id'            => 'wp_prject_update_date_field[]',
                    'label'         => __( 'Date', 'xwoo' ),
                    'desc_tip'      => 'true',
                    'type'          => 'text',
                    'class'         => 'datepicker',
                    'placeholder'   => __( date('d-m-Y'), 'xwoo' ),
                    'value'         => ''
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id'            => 'wp_prject_update_title_field[]',
                    'label'         => __( 'Update Title', 'xwoo' ),
                    'desc_tip'      => 'true',
                    'type'          => 'text',
                    'placeholder'   => __( 'Update title', 'xwoo' ),
                    'value'         => ''
                )
            );
            woocommerce_wp_textarea_input(
                array(
                    'id'            => 'wp_prject_update_details_field[]',
                    'label'         => __( 'Update Details', 'xwoo' ),
                    'desc_tip'      => 'true',
                    'type'          => 'text',
                    'placeholder'   => __( 'Update details', 'xwoo' ),
                    'value'         => ''
                )
            );
        echo '<input name="remove_udpate" type="button" class="button tagadd removecampaignupdate" value="'.__('Remove', 'xwoo').'" />';
        echo '<div style="border-bottom: 1px solid #eee"></div>';
        echo "</div>";
        echo "</div>";

        echo "<div id='campaign_update_addon_field'>";
            if (is_array($saved_campaign_update_a) && $total_campaign_update > 0){
                foreach($saved_campaign_update_a as $key => $value) {
                    echo "<div class='campaign_update_field_copy'>";
                    woocommerce_wp_text_input(
                        array(
                            'id'            => 'wp_prject_update_date_field[]',
                            'label'         => __( 'Date', 'xwoo' ),
                            'desc_tip'      => 'true',
                            'type'          => 'text',
                            'class'         => 'datepicker',
                            'placeholder'   => __( date('d-m-Y'), 'xwoo' ),
                            'value'         => stripslashes($value['date'])
                        )
                    );
                    woocommerce_wp_text_input(
                        array(
                            'id'        => 'wp_prject_update_title_field[]',
                            'label'     => __('Update Title', 'xwoo'),
                            'desc_tip'  => 'true',
                            'type'      => 'text',
                            'placeholder' => __('Update title', 'xwoo'),
                            'value'     => stripslashes($value['title'])
                        )
                    );

                    // woocommerce_wp_textarea_input(
                    //     array(
                    //         'id'        => 'wp_prject_update_details_field[]',
                    //         'label'     => __('Update Title', 'xwoo'),
                    //         'desc_tip'  => 'true',
                    //         'placeholder' => __('Update Details', 'xwoo'),
                    //         'value'     => stripslashes($value['details'])
                    //     )
                    // );

                    wp_editor(stripslashes($value['details']), 'wp_prject_update_details_field'.$key, array('textarea_name' => 'wp_prject_update_details_field[]'));

                    echo '<div class="xwoo-campaign-update-btn-wrap"><input name="remove_udpate" type="button" class="button tagadd removecampaignupdate" value="'.__('Remove', 'xwoo').'" /></div>';
                    echo '<div style="border-bottom: 1px solid #eee"></div>';
                    echo "</div>";
                }
            }
        echo "</div>";


        echo '<input name="save_update" type="button" class="button tagadd" id="addcampaignupdate" value="'.__('+ Add Update', 'xwoo').'" />';
        echo '<div style="clear: both;"></div>';
        echo "</div>";
    }

    /**
     * @param $post_id
     *
     * Save Update at Meta Data
     */
    public function update_status_save($post_id){
        if ( ! empty($_POST['wp_prject_update_title_field'])){
            $data               = array();
            $title_field        = $_POST['wp_prject_update_title_field'];
            $date_field         = $_POST['wp_prject_update_date_field'];
            $details_field      = $_POST['wp_prject_update_details_field'];
            $total_update_field = count( $title_field );
            for ($i=0; $i<$total_update_field; $i++){
                if (! empty($title_field[$i])) {
                    $data[] = array(
                        'date'      => sanitize_text_field($date_field[$i]),
                        'title'     => sanitize_text_field($title_field[$i]),
                        'details'   => $details_field[$i]
                    );
                }
            }
            $data_json = json_encode($data,JSON_UNESCAPED_UNICODE);
            xwoo_function()->update_meta($post_id, 'wp_campaign_updates', wp_slash($data_json));
        }
    }


    /**
     * @param $post_id
     * Saving meta information over this method
     */
    function custom_field_save($post_id ){
        $product_type = sanitize_text_field(xwoo_function()->post('_neo_xwoo_product_type'));
        if( !empty( $product_type) ) {
            xwoo_function()->update_meta($post_id, '_neo_xwoo_product_type', 'yes');
        } else {
            xwoo_function()->update_meta($post_id, '_neo_xwoo_product_type', 'no');
        }

        $location = sanitize_text_field( $_POST['_nf_location'] );
        xwoo_function()->update_meta($post_id, '_nf_location', $location);

        $funding_video = sanitize_text_field( $_POST['wp_funding_video'] );
        xwoo_function()->update_meta($post_id, 'wp_funding_video', $funding_video);

        $duration_start = sanitize_text_field( $_POST['_nf_duration_start'] );
        xwoo_function()->update_meta($post_id, '_nf_duration_start', $duration_start);

        $duration_end = sanitize_text_field( $_POST['_nf_duration_end'] );
        xwoo_function()->update_meta($post_id, '_nf_duration_end', $duration_end);

        $funding_goal = sanitize_text_field($_POST['_nf_funding_goal']);
        xwoo_function()->update_meta($post_id, '_nf_funding_goal', $funding_goal);

        $minimum_price = sanitize_text_field($_POST['wp_funding_minimum_price']);
        xwoo_function()->update_meta($post_id, 'wp_funding_minimum_price', $minimum_price);

        $maximum_price = sanitize_text_field($_POST['wp_funding_maximum_price']);
        xwoo_function()->update_meta($post_id, 'wp_funding_maximum_price', $maximum_price);

        $recommended_price = sanitize_text_field($_POST['wp_funding_recommended_price']);
        xwoo_function()->update_meta($post_id, 'wp_funding_recommended_price', $recommended_price);

        $pledge_amount = sanitize_text_field($_POST['XWOO_predefined_pledge_amount']);
        xwoo_function()->update_meta($post_id, 'XWOO_predefined_pledge_amount', $pledge_amount);

        $end_method = sanitize_text_field( $_POST['wp_campaign_end_method'] );
        xwoo_function()->update_meta($post_id, 'wp_campaign_end_method', $end_method);

        $contributor_table = sanitize_text_field( $_POST['wp_show_contributor_table'] );
        xwoo_function()->update_meta($post_id, 'wp_show_contributor_table', $contributor_table);

        $contributors_as_anonymous = sanitize_text_field( $_POST['wp_mark_contributors_as_anonymous'] );
        xwoo_function()->update_meta($post_id, 'wp_mark_contributors_as_anonymous', $contributors_as_anonymous);

        $campaigner_paypal_id = sanitize_text_field( $_POST['wp_campaigner_paypal_id'] );
        xwoo_function()->update_meta($post_id, 'wp_campaigner_paypal_id', $campaigner_paypal_id);

        $country = sanitize_text_field( $_POST['wp_country'] );
        xwoo_function()->update_meta($post_id, 'wp_country', $country);
    }


    /**
     * donate_input_field();
     */
    function donate_input_field()
    {
        global $post;
        $product = wc_get_product( $post->ID );

        //wp_die(var_dump($product));

        $html = '';
        if ($product->get_type() == 'xwoo') {
            $html .= '<div class="donate_field wp_neo">';

            if (xwoo_function()->is_campaign_valid()) {

                $html .= '<form class="cart" method="post" enctype="multipart/form-data">';
                $html .= do_action('before_wp_donate_field');
                $recomanded_price = get_post_meta($post->ID, 'wp_funding_recommended_price', true);
                $html .= get_woocommerce_currency_symbol();
                $html .= apply_filters('neo_donate_field', '<input type ="number" step="any" class="input-text amount wp_donation_input text" name="wp_donate_amount_field" min="0" value="'.esc_attr($recomanded_price).'" />');
                $html .= do_action('after_wp_donate_field');
                $html .= '<input type="hidden" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" />';
                $btn_text = get_option('wp_donation_btn_text');
                $html .= '<button type="submit" class="'.apply_filters('add_to_donate_button_class', 'single_add_to_cart_button button alt').'">' . __(apply_filters('add_to_donate_button_text', esc_html($btn_text) ? esc_html($btn_text) : 'Donate now'), 'woocommerce').'</button>';
                $html .= '</form>';
            } else {
                $html .= apply_filters('end_campaign_message', __('This campaign has been end', 'xwoo'));
            }
            $html .= '</div>';
        }
        echo $html;
    }


    /**
     * Remove Xwoo item form cart
     */
    public function remove_xwoo_item_from_cart($passed, $product_id, $quantity, $variation_id = '', $variations= '') {
        $product = wc_get_product($product_id);

        if($product->get_type() == 'xwoo') {
            foreach (WC()->cart->cart_contents as $item_cart_key => $prod_in_cart) {
                WC()->cart->remove_cart_item( $item_cart_key );
            }
        }
        foreach (WC()->cart->cart_contents as $item_cart_key => $prod_in_cart) {
            if ($prod_in_cart['data']->get_type() == 'xwoo') {
                WC()->cart->remove_cart_item( $item_cart_key );
            }
        }
        return $passed;
    }

    /**
     * @param $array
     * @param $int
     * @return mixed
     *
     * Save user input donation into cookie
     */
    function save_user_donation_to_cookie( $array, $int ) {
        if ($array['data']->get_type() == 'xwoo'){
            if ( !empty($_POST['wp_donate_amount_field']) ) {
                if (is_user_logged_in()){
                    $user_id = get_current_user_id();
                    delete_user_meta($user_id,'wp_wallet_info');
                }

                //setcookie("wp_user_donation", esc_attr($_POST['wp_donate_amount_field']), 0, "/");
                $donate_amount = xwoo_function()->post('wp_donate_amount_field');
                WC()->session->set('wp_donate_amount', $donate_amount);

                if ( isset($_POST['wp_rewards_index'])) {
                    if ( !$_POST['wp_rewards_index']) {
                        return;
                    }

                    $selected_reward    = stripslashes_deep( xwoo_function()->post('wp_selected_rewards_checkout') );
                    $selected_reward    = json_decode($selected_reward, TRUE);
                    $reward_index       = (int) xwoo_function()->post('wp_rewards_index');
                    $rewards_index      = (int) xwoo_function()->post('wp_rewards_index') -1;
                    $product_author_id  = (int) xwoo_function()->post('_xwoo_product_author_id');
                    $product_id         = (int) xwoo_function()->post('add-to-cart');

                    WC()->session->set('wp_rewards_data',
                        array(
                            'wp_selected_rewards_checkout' => $selected_reward,
                            'rewards_index' => $rewards_index,
                            'product_id' => $product_id,
                            '_xwoo_product_author_id' => $product_author_id
                        )
                    );
                }else{
                    WC()->session->__unset('wp_rewards_data');
                }
            }
        }
        return $array;
    }

    /**
     * Get donation amount from cookie. Add user input base donation amount to cart
     */

    function add_user_donation() {
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            if ($cart_item['data']->get_type() == 'xwoo') {
                $donate_cart_amount = WC()->session->get('wp_donate_amount');
                if ( !empty($donate_cart_amount) ) {
                    $cart_item['data']->set_price($donate_cart_amount);
                }
            }
        }
    }

    /**
     * Redirect to checkout after cart
     */
    function redirect_to_checkout($url) {
        global $product;

        if (! empty($_REQUEST['add-to-cart'])){
            $product_id = absint( $_REQUEST['add-to-cart'] );
            $product = wc_get_product( $product_id );

            if($product && $product->is_type( 'xwoo' ) ){

                $checkout_url   = wc_get_checkout_url();
                $preferance     = get_option('wp_xwoo_add_to_cart_redirect');

                if ($preferance == 'checkout_page'){
                    $checkout_url = wc_get_checkout_url();
                }elseif ($preferance == 'cart_page'){
                    $checkout_url = wc_get_cart_url();
                }else{
                    $checkout_url = get_permalink();
                }

                wc_clear_notices();
                return $checkout_url;
            }
        }
        return $url;
    }

    /**
     * Disabled coupon system from system
     */
    function wc_coupon_disable( $coupons_enabled ) {
        $items = WC()->cart->get_cart();
        $type = true;
        if( $items ){
            foreach($items as $item => $values) {
                $product = wc_get_product( $values['product_id'] );
                if( $product->get_type() == 'xwoo' ){
                    $type = false;
                }
            }
        }
        return $type;
    }

    /**
     * @param $price
     * @param $product
     * @return string
     *
     * reove price html for xwoo campaign
     */

    function wc_price_remove( $price, $product ) {
        $target_product_types = array( 'xwoo' );
        if ( in_array ( $product->get_type(), $target_product_types ) ) {
            // if variable product return and empty string
            return '';
        }
        // return normal price
        return $price;
    }


    /**
     * @param $purchasable
     * @param $product
     * @return bool
     *
     * Return true is purchasable if not found price
     */

    function return_true_woocommerce_is_purchasable( $purchasable, $product ){
        if( $product->get_price() == 0 ||  $product->get_price() == ''){
            $purchasable = true;
        }
        return $purchasable;
    }


    /**
     * @return mixed
     *
     * get PayPal email address from campaign
     */
    public function get_paypal_reciever_email_address() {
        foreach (WC()->cart->cart_contents as $item) {
            $emailid = get_post_meta($item['product_id'], 'wp_campaigner_paypal_id', true);
            $enable_paypal_per_campaign = get_option('wp_enable_paypal_per_campaign_email');

            if ($enable_paypal_per_campaign == 'true') {
                if (!empty($emailid)) {
                    return $emailid;
                } else {
                    $paypalsettings = get_option('woocommerce_paypal_settings');
                    return $paypalsettings['email'];
                }
            } else {
                $paypalsettings = get_option('woocommerce_paypal_settings');
                return $paypalsettings['email'];
            }
        }
    }

    public function custom_override_paypal_email($paypal_args) {
        $paypal_args['business'] = $this->get_paypal_reciever_email_address();
        return $paypal_args;
    }

    /**
     * @param $order_id
     * 
     * Save order reward if any with order meta
     */
    public function xwoo_order_type($order_id){
        if( WC()->session != null ) {
            $rewards_data = WC()->session->get( 'wp_rewards_data' );
            if ( ! empty( $rewards_data ) ) {
                $reward = $rewards_data['wp_selected_rewards_checkout'];
                $reward_json = json_encode($reward,JSON_UNESCAPED_UNICODE);
                // xwoo_function()->update_meta( $order_id, 'wp_selected_reward', $reward );
                xwoo_function()->update_meta( $order_id, 'wp_selected_reward', wp_slash($reward_json) );
                xwoo_function()->update_meta( $order_id, '_xwoo_product_author_id', $rewards_data['_xwoo_product_author_id'] );
                WC()->session->__unset( 'wp_rewards_data' );
            }
        }
    }

    public function xwoo_new_order_item( $item_id, $item, $order_id){
        $product_id = wc_get_order_item_meta($item_id, '_product_id', true);
        if( ! $product_id ){
            return;
        }
        $get_product = wc_get_product($product_id);
        $product_type = $get_product->get_type();
        if ($product_type === 'xwoo'){
            xwoo_function()->update_meta($order_id, 'is_xwoo_order','1');
        }
    }

    public function check_anonymous_backer(){
        $items = WC()->cart->get_cart();
        if( $items ){
            foreach($items as $item => $values) {
                $product = wc_get_product( $values['product_id'] );
                if( $product->get_type() == 'xwoo' ){
                    echo '<div id="mark_name_anonymous" class="mark_name_anonymous_wrap">';
                    echo '<label><input type="checkbox" value="true" name="mark_name_anonymous" /> '.__('Make me anonymous', 'xwoo').' </label>';
                    echo '</div>';
                }
            }
        }
    }

    /**
     * @param $order_id
     */
    public function check_anonymous_backer_post($order_id){
        if (! empty($_POST['mark_name_anonymous'])){
            if ($_POST['mark_name_anonymous'] === 'true'){
                xwoo_function()->update_meta($order_id, 'mark_name_anonymous', 'true');
            }
        }
    }


    public function is_tax_enable_for_xwoo_product($bool){
        if( ! $bool){
            return false;
        }

        $is_enabled = get_option('XWOO_enable_tax') === 'true';

        if ($bool && $is_enabled){
            return true;
        }


        $is_xwoo_in_cart = false;
        if ( ! empty(wc()->cart->cart_contents)){
            $cart_content = wc()->cart->cart_contents;
            foreach ($cart_content as $content){
                if ( ! empty($content['data']->product_type) && $content['data']->product_type === 'xwoo'){
                    $is_xwoo_in_cart = true;
                }
            }
        }

        if ($is_xwoo_in_cart && ! $is_enabled){
            return false;
        }

        return $bool;
    }



    public function add_checked_xwoo_categories( $taxonomy){
        ?>

        <div class="form-field term-check-xwoo-category-wrap">
            <label for="tag-check-xwoo-category">
                <input type="checkbox" name="tag_check_xwoo_category" id="tag-check-xwoo-category" value="1">
                <?php _e( 'Mark as Xwoo Category' ); ?>
            </label>

            <p><?php _e('This check mark allow you to detect xwoo specific category,'); ?></p>
        </div>

        <?php
    }

    public function edit_product_taxonomy_field($term){
        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Is Xwoo Category', 'woocommerce' );
            ?></label></th>
            <td>

                <label for="tag-check-xwoo-category">
                    <?php
                    $is_checked_xwoo = get_term_meta($term->term_id, '_marked_as_xwoo', true);

                    ?>
                    <input type="checkbox" name="tag_check_xwoo_category"
                            id="tag-check-xwoo-category" value="1" <?php checked($is_checked_xwoo, '1' ) ?>>
                    <?php _e( 'Mark as Xwoo Category' ); ?>
                </label>

                <p><?php _e('This check mark allow you to detect xwoo specific category,'); ?></p>

            </td>
        </tr>

        <?php

    }

    /**
     * @param $term_id (int) Term ID.
     * @param $tt_id (int) Term taxonomy ID.
     *
     *
     */
    public function mark_category_as_xwoo( $term_id, $tt_id){
        if (isset($_POST['tag_check_xwoo_category']) && $_POST['tag_check_xwoo_category'] == '1'){
            $term_meta = update_term_meta($term_id, '_marked_as_xwoo', $_POST['tag_check_xwoo_category']);
        }
    }

    public function edit_mark_category_as_xwoo( $term_id, $tt_id){
        if (isset($_POST['tag_check_xwoo_category']) && $_POST['tag_check_xwoo_category'] == '1'){
            $term_meta = update_term_meta($term_id, '_marked_as_xwoo', $_POST['tag_check_xwoo_category']);
        }else{
            delete_term_meta($term_id, '_marked_as_xwoo');
        }
    }

    public function product_taxonomy_is_xwoo_columns($columns){
        $columns['xwoo_col'] = __('Xwoo', 'xwoo');
        return $columns;
    }


    function filter_description_col_product_taxomony($content, $column_name, $term_id ) {
        switch ($column_name) {
            case 'xwoo_col':
                $is_xwoo_col = get_term_meta($term_id, '_marked_as_xwoo', true);
                if ($is_xwoo_col == '1'){
                    $content = __('Yes', 'xwoo');
                }
                break;
            default:
                break;
        }
        return $content;
    }

    public function after_item_title_data() {
        global $post,$wpdb;
        $product = wc_get_product($post->ID);

        if($product->get_type() != 'xwoo'){
            return '';
        }

        $funding_goal   = xwoo_function()->get_total_goal($post->ID);
        $country        = get_post_meta( $post->ID, 'wp_country', true);
        $total_sales    = get_post_meta( $post->ID, 'total_sales', true );
        $enddate        = get_post_meta( $post->ID, '_nf_duration_end', true );

        //Get Country name from WooCommerce
        $countries_obj  = new \WC_Countries();
        $countries      = $countries_obj->__get('countries');

        $country_name = '';
        if ($country){
            $country_name = $countries[$country];
        }

        $raised = 0;
        $total_raised = xwoo_function()->get_total_fund();
        if ($total_raised){
            $raised = $total_raised;
        }

        //Get order sales value by product
        $sales_value_by_product = 0;

        $days_remaining = apply_filters('date_expired_msg', __('Date expired', 'xwoo'));
        if ( xwoo_function()->get_date_remaining() ) {
            $days_remaining = apply_filters('date_remaining_msg', __( xwoo_function()->get_date_remaining().' days remaining', 'xwoo') );
        }

        $html = '';
        $html .= '<div class="xwoo_wrapper">';

        if ($country_name) {
            $html .= '<div class="wp_location">';
            $html .= '<p class="wp_thumb_text">'. __('Location: ', 'xwoo') . $country_name.'</p>';
            $html .= '</div>';
        }

        if ($funding_goal) {
            $html .= '<div class="funding_goal">';
            $html .= '<p class="wp_thumb_text">'.__('Funding Goal: ', 'xwoo') . '<span class="price amount">'.wc_price($funding_goal).'</span>'. '</p>';
            $html .= '</div>';
        }

        if ($total_sales) {
            $html .= '<div class="total_raised">';
            $html .= '<p class="wp_thumb_text">'.__('Raised: ', 'xwoo') . '<span class="price amount">' . wc_price( $raised).'</span>'. '</p>';
            $html .= '</div>';
        }

        if ($total_sales && $funding_goal) {
            $percent = xwoo_function()->get_raised_percent();
            $html .= '<div class="percent_funded">';
            $html .= '<p class="wp_thumb_text">'.__('Funded percent: ', 'xwoo') . '<span class="price amount">' . $percent.' %</span>'. '</p>';
            $html .= '</div>';
        }

        if ($total_sales) {
            $html .= '<div class="days_remaining">';
            $html .= '<p class="wp_thumb_text">'.$days_remaining. '</p>';
            $html .= '</div>';
        }

        $html .= '</div>';
        echo apply_filters('woocommerce_product_xwoo_meta_data',$html);
    }

    /**
     * @param $tabs
     * @return string
     *
     * Return Reward Tab Data
     */
    public function product_backed_user_tab( $tabs ) {
        global $post;
        $product = wc_get_product($post->ID);
        if($product->get_type() =='xwoo'){
            // Adds the new tab
            $tabs['backed_user'] = array(
                'title'     => __( 'Backed User', 'xwoo' ),
                'priority'  => 51,
                'callback'  => array($this, 'product_backed_user_tab_content')
            );
        }
        return $tabs;
    }

    public function product_backed_user_tab_content( $post_id ){

        global $post, $wpdb;
        $html       = '';
        $prefix     = $wpdb->prefix;
        $product_id = $post->ID;
        $data_array = xwoo_function()->get_campaign_orders_id_list();

        $args = array(
            'post_type'     => 'shop_order',
            'post_status'   => array('wc-completed','wc-on-hold'),
            'post__in'      => $data_array
        );
        $the_query = new \WP_Query( $args );

        if ( $the_query->have_posts() ) :

            $html .= '  <table class="shop_table backed_user_table">

                <thead>
                    <tr>
                        <th>'.__('ID', 'xwoo').'</th>
                        <th>'.__('Name', 'xwoo').'</th>
                        <th>'.__('Email', 'xwoo').'</th>
                        <th>'.__('Amount', 'xwoo').'</th>
                    </tr>
                </thead>';
            ?>


            <?php
            while ( $the_query->have_posts() ) : $the_query->the_post();

                $html .= '<tr>';
                $html .= '<td>'.get_the_ID().'</td>';
                $html .= '<td>'. get_post_meta( get_the_ID() , "_billing_first_name",true ).' '.get_post_meta( get_the_ID() , "_billing_last_name",true ).'</td>';
                $html .= '<td>'. get_post_meta( get_the_ID() , "_billing_email",true ).'</td>';
                $post_id = get_the_ID();
                $price = $wpdb->get_results("SELECT order_meta.meta_value FROM `{$prefix}woocommerce_order_itemmeta` AS order_meta, `{$prefix}woocommerce_order_items` AS order_item WHERE order_meta.order_item_id IN (SELECT order_item.order_item_id FROM `{$prefix}woocommerce_order_items` as order_item WHERE order_item.order_id = {$post_id}) AND order_meta.order_item_id IN (SELECT meta.order_item_id FROM `{$prefix}woocommerce_order_itemmeta` AS meta WHERE meta.meta_key='_product_id' AND meta.meta_value={$product_id} ) AND order_meta.meta_key='_line_total' GROUP BY order_meta.meta_id");
                $price = json_decode( json_encode($price), true );
                if(isset($price[0]['meta_value'])){
                    $html .= '<td>'. wc_price($price[0]['meta_value']).'</td>';
                }
                $html .= '</tr>';

            endwhile;
            wp_reset_postdata();

            $html .= '</table>';
            ?>
            <?php
        else :
            $html .= __( 'Sorry, no posts matched your criteria.','xwoo' );
        endif;

        echo $html;
    }

    public function remove_xwoo_quantity_fields( $return, $product ) {
        if ($product->get_type() == 'xwoo'){
            return true;
        }
        return $return;
    }

    function limit_show_xwoo_campaign_in_shop($wp_query){
        $tax_query = array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => array(
                    'xwoo'
                ),
                'operator' => 'NOT IN'
            )
        );
        $wp_query->set( 'tax_query', $tax_query );
        return $wp_query;
    }

    function XWOO_campaign_single_love_this() {
        return xwoo_function()->campaign_single_love_this();
    }

    function wc_empty_cart() {
        if( function_exists('WC') ){
            WC()->cart->empty_cart();
        }
    }


} //End class bracket
