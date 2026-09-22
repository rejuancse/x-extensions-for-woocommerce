<?php

defined( 'ABSPATH' ) || exit;

class Xwoo_Product_Search_Extensions {
    /**
     * @var null
     *
     * Instance of this class
     */
    protected static $_instance = null;

    /**
     * @return null|XEWC
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'xewc_add_product_search_page'));
        add_action('admin_init', array($this, 'save_product_search_menu_settings' ));
        add_action( 'wp_enqueue_scripts', array( $this, 'xewc_search_enqueue_frontend_script') );
        add_action( 'wp_ajax_xewc_product_search', array( $this, 'ajax_product_search' ) );
        add_action( 'wp_ajax_nopriv_xewc_product_search', array( $this, 'ajax_product_search' ) );
    }

    public function xewc_add_product_search_page(){
        add_submenu_page(
            'xewc', 
            __('Product Search', 'x-extensions-for-woocommerce'),
            __('Product Search', 'x-extensions-for-woocommerce'), 
            'manage_options', 
            'xewc-search', 
            array($this, 'xewc_product_search_func')
        );
    }

    public function xewc_search_enqueue_frontend_script() {
        wp_enqueue_script('xewc-search-front', XEWC_DIR_URL .'extensions/product-search/assets/js/productSearch.js', array('jquery'), XEWC_VERSION, true);
    }

    /**
     * AJAX handler that returns the product search results markup.
     */
    public function ajax_product_search() {
        $search_image = get_option( 'wp_product_search_image', true );
        $raw_data     = isset( $_POST['raw_data'] ) ? sanitize_text_field( wp_unslash( $_POST['raw_data'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Public read-only search endpoint for logged-out visitors; no state is modified.

        $output = '';

        if ( ! empty( $raw_data ) ) {
            $search_data = new \WP_Query(
                array(
                    'post_type'      => 'product',
                    's'              => $raw_data,
                    'posts_per_page' => 10,
                )
            );

            if ( $search_data->have_posts() ) {
                $output .= '<ul class="xewc-productss-search results-list">';
                while ( $search_data->have_posts() ) :
                    $search_data->the_post();
                    $output .= '<li>';
                    $output .= '<div class="pack-thumb">';
                    if ( $search_image == 'true' ) {
                        $output .= get_the_post_thumbnail( $search_data->post->ID, 'thumbnail' );
                    }
                    $output .= '<span><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></span>';
                    $output .= '</div>';
                    $output .= '</li>';
                endwhile;
                $output .= '</ul>';
                wp_reset_postdata();
            }
        }

        echo wp_kses_post( $output );
        wp_die();
    }

    /**
     * Display a custom menu page
     */
    public function xewc_product_search_func(){
        if (xewc_function()->post('wp_settings_page_nonce_field')){
            echo '<div class="notice notice-success is-dismissible">';
                echo '<p>'.esc_html__( "Quick view data have been Saved.", "x-extensions-for-woocommerce" ).'</p>';
            echo '</div>';
        }

        $default_file = XEWC_DIR_PATH.'extensions/product-search/pages/general-settings.php';
        $shortcode_file = XEWC_DIR_PATH.'extensions/product-search/pages/shortcode.php';

        // Settings Tab With slug and Display name
        $tabs = apply_filters('xewc_listing_page_panel_tabs', array(
                'general_settings' 	=>
                    array(
                        'tab_name' => __('General Settings','x-extensions-for-woocommerce'),
                        'load_form_file' => $default_file
                    ),
                'listing_shortcode' 	=>
                    array(
                        'tab_name' => __('Shortcodes','x-extensions-for-woocommerce'),
                        'load_form_file' => $shortcode_file
                    )
            )
        );

        $current_page = 'general_settings';
        if( ! empty($_GET['tab']) ){ // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only navigation parameter used for tab selection.
            $current_page = sanitize_text_field(wp_unslash($_GET['tab'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only navigation parameter used for tab selection.
        }

        // Print the Tab Title
        echo '<h2 class="xewc-setting-title">'.esc_html__( "XEWC Product Search" , "x-extensions-for-woocommerce" ).'</h2>';
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $current_page ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab" . esc_attr( $class ) . "' href='?page=xewc-search&tab=" . esc_attr( $tab ) . "'>" . esc_html( $name['tab_name'] ) . "</a>";
        }
        echo '</h2>'; ?>

        <form id="xewc" role="form" method="post" action="">
            <?php
            //Load tab file
            $request_file = $tabs[$current_page]['load_form_file'];

            if (array_key_exists(trim(esc_attr($current_page)), $tabs)){
                if (file_exists($default_file)){
                    include_once $request_file;
                }else{
                    include_once $default_file;
                }
            } else {
                include_once $default_file;
            }
            wp_nonce_field( 'wp_settings_page_action', 'wp_settings_page_nonce_field' );
            submit_button( null, 'primary', 'wp_admin_settings_submit_btn' );
            ?>
        </form>
        <?php
    }

    /**
     * Add menu settings action
     */
    public function save_product_search_menu_settings() {
        
        if (xewc_function()->post('wp_settings_page_nonce_field') && wp_verify_nonce( sanitize_text_field(xewc_function()->post('wp_settings_page_nonce_field')), 'wp_settings_page_action' ) ){

            $current_tab = sanitize_text_field(xewc_function()->post('wp_xewc_search_admin_tab'));

            if( ! empty($current_tab) ){
                /**
                 * General Settings
                 */
                $search_image = sanitize_text_field(xewc_function()->post('wp_product_search_image'));
                xewc_function()->update_checkbox( 'wp_product_search_image', $search_image);

                $btn_off = sanitize_text_field(xewc_function()->post('wp_product_search_btn_off'));
                xewc_function()->update_checkbox('wp_product_search_btn_off', $btn_off);
            }
        }
    }
}
Xwoo_Product_Search_Extensions::instance();
