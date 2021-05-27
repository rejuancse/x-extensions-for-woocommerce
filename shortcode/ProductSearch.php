<?php
namespace XWOO\shortcode;

defined( 'ABSPATH' ) || exit;

class Product_Search {

    function __construct() {
        add_shortcode( 'xwoo_product_search', array( $this, 'products_search_callback_function' ) );
    }

    function products_search_callback_function() {

        $output = '';
        
        $output .= '<form class="search_form_shortcode" role="search" action="'.esc_url( home_url( '/' ) ).'" method="get">';
            $output .= '<input class="xwoo-ajax-search" data-url="'.plugin_dir_url('', __FILE__).'xwoo/extensions/product-search/classes/search-data.php'.'" type="text" name="s" value="'.get_search_query().'" placeholder="'.esc_attr__( 'Search products...', 'xwoo' ).'"/>';
            $output .= '<button type="submit">Product Search</button>';
            $output .= '<div class="xwoo-products-search-results"></div>';
        $output .= '</form>';
        
        return $output;
    }
}
