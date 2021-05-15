<?php
namespace XWOO;

defined( 'ABSPATH' ) || exit;

class Functions {

    public function generator( $arr ){
        require_once XWOO_DIR_PATH . 'settings/Generator.php';
        $generator = new \XWOO\settings\Settings_Generator();
        $generator->generator( $arr );
    }

    public function post($post_item){
        if (!empty($_POST[$post_item])) {
            return $_POST[$post_item];
        }
        return null;
    }
    
    public function is_published($post_id=0){
        global $post;
        if ($post_id == 0){
            $post_id = $post->ID;
        }
        $status = get_post_status($post_id);
        return $status=='publish' ? true : false;
    }

    public function is_free(){
        if (is_plugin_active('wp-xwoo-pro/wp-xwoo-pro.php')) {
            return false;
        } else {
            return true;
        }
    }

    public function update_text($option_name = '', $option_value = null){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }

    public function update_checkbox($option_name = '', $option_value = null, $checked_default_value = 'false'){
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        } else{
            update_option($option_name, $checked_default_value);
        }
    }
    
    public function update_meta($post_id, $meta_name = '', $meta_value = '', $checked_default_value = ''){
        if (!empty($meta_value)) {
            update_post_meta( $post_id, $meta_name, $meta_value);
        }else{
            update_post_meta( $post_id, $meta_name, $checked_default_value);
        }
    }

    public function get_pages(){
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'child_of' => 0,
            'parent' => -1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }

    public function wc_version($version = '3.0'){
        if (class_exists('WooCommerce')) {
            if (version_compare(WC()->version, $version, ">=")) {
                return true;
            }
        }
        return false;
    }
    
    public function is_woocommerce(){
        $vendor = get_option('vendor_type', 'woocommerce');
        if( $vendor == 'woocommerce' ){
            return true;
        }else{
            return false;
        }
    }
    
    public function get_screen_id(){
        $screen_ids = array(
            'toplevel_page_xwoo',
            'xwoo_page_xwoo-reports',
            'xwoo_page_xwoo-withdraw',
        );
        return apply_filters('xwoo_screen_id', $screen_ids);
    }
    
    public function get_addon_config($addon_field = null){
        if ( ! $addon_field){
            return false;
        }
        $extensionsConfig = maybe_unserialize(get_option('xwoo_extensions_config'));
        if (isset($extensionsConfig[$addon_field])){
            return $extensionsConfig[$addon_field];
        }
        return false;
    }


    public function avalue_dot($key = null, $array = array()){
        $array = (array) $array;
        if ( ! $key || ! count($array) ){
            return false;
        }
        $option_key_array = explode('.', $key);
        $value = $array;
        foreach ($option_key_array as $dotKey){
            if (isset($value[$dotKey])){
                $value = $value[$dotKey];
            }else{
                return false;
            }
        }
        return $value;
    }


	public function get_order_ids_by_product_ids( $product_ids , $order_status = array( 'wc-completed' ) ){
		global $wpdb;
		$results = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value IN ( '" . implode( "','", $product_ids ) . "' )
        ");
		return $results;
    }

    function get_author_url($user_login) {
        return esc_url(add_query_arg(array('author' => $user_login)));
    }

    // public function template($template = '404'){
	// 	$template_class = new \XWOO\woocommerce\Templating;
	// 	$locate_file = $template_class->_theme_in_themes_path.$template.'.php';
	// 	if (file_exists($locate_file)){
	// 		include $locate_file;
	// 	} 
    //     // else { 
    //     //     include $template_class->_theme_in_plugin_path.$template.'.php';
    //     // }
    // }
    

    public function campaign_loved($echo = true){
		global $post;
		$campaign_id = $post->ID;

		$html = '';
		if (is_user_logged_in()){
			//Get Current user id
			$user_id = get_current_user_id();
			//empty array
			$loved_campaign_ids = array();
			$prev_campaign_ids = get_user_meta($user_id, 'loved_campaign_ids', true);

			if ($prev_campaign_ids){
				$loved_campaign_ids = json_decode($prev_campaign_ids, true);
			}

			//If found previous liked
			if (in_array($campaign_id, $loved_campaign_ids)){
				$html .= '<a href="javascript:;" id="remove_from_love_campaign" data-campaign-id="'.$campaign_id.'"><i class="xwoo-icon xwoo-icon-love-full"></i></a>';
			} else {
				$html .= '<a href="javascript:;" id="love_this_campaign" data-campaign-id="'.$campaign_id.'"><i class="xwoo-icon xwoo-icon-love-empty"></i></a>';
			}
		} else {
			$html .= '<a href="javascript:;" id="love_this_campaign" data-campaign-id="'.$campaign_id.'"><i class="xwoo-icon xwoo-icon-love-empty"></i></a>';
		}

		if ($echo){
			echo $html;
		}else{
			return $html;
		}
    }
    
    public function loved_count($user_id = 0){
		global $post;
		$campaign_id = $post->ID;
		if ($user_id == 0) {
			if (is_user_logged_in()) {
				$user_id = get_current_user_id();
				$loved_campaign_ids = array();
				$prev_campaign_ids = get_user_meta($user_id, 'loved_campaign_ids', true);
				if ($prev_campaign_ids) {
					$loved_campaign_ids = json_decode($prev_campaign_ids, true);
					return count($loved_campaign_ids);
				}
			}
		}
		return 0;
    }

	public function url($url){
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}


    // Pagination
	function get_pagination($page_numb, $max_page) {
		$html = '';
		$big = 999999999; // need an unlikely integer
		$html .= '<div class="xwoo-pagination">';
		$html .= paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'current' => $page_numb,
			'total' => $max_page,
			'type' => 'list',
			'after_page_number' => '',
		));
		$html .= '</div>';
		return $html;
    }
    
    public function campaign_single_love_this() {
		global $post;
		if (is_product()){
			if( function_exists('get_product') ){
				$product = wc_get_product( $post->ID );
				if( $product->is_type( 'xwoo' ) ){
					xwoo_function()->template('include/love_campaign');
				}
			}
		}
	}

    function limit_word_text($text, $limit) {
        if ( $this->mb_str_word_count($text, 0) > $limit ) {
            $words  = $this->mb_str_word_count($text, 2);
            $pos    = array_keys($words);
            $text   = mb_substr($text, 0, $pos[$limit]);
        }
        return $text;
    }

    function mb_str_word_count($string, $format = 0, $charlist = '[]') {
        mb_internal_encoding( 'UTF-8');
        mb_regex_encoding( 'UTF-8');
        $words = mb_split('[^\x{0600}-\x{06FF}]', $string);
        switch ($format) {
            case 0:
                return count($words);
                break;
            case 1:
            case 2:
                return $words;
                break;
            default:
                return $words;
                break;
        }
    }
}
