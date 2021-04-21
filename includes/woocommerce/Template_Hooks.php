<?php
namespace XWOO\woocommerce;

defined( 'ABSPATH' ) || exit;

class Template_Hooks {

    public function __construct() {
		add_action('XWOO_before_single_campaign_summary', 				array($this, 'campaign_single_feature_image'));
		add_action('XWOO_after_feature_img',               				array($this, 'campaign_single_description'));
        
        // Single campaign Template hook
        add_action('XWOO_single_campaign_summary',        				array($this, 'single_campaign_summary'));
        add_filter('XWOO_default_single_campaign_tabs',   				array($this, 'single_campaign_tabs'), 10);
        add_action('XWOO_after_single_campaign_summary',  				array($this, 'campaign_single_tab'));
        //Campaign Story Right Sidebar
        add_action('XWOO_campaign_story_right_sidebar',                	array($this, 'story_right_sidebar'));
        //Listing Loop
		add_action('XWOO_campaign_loop_item_before_content',           	array($this, 'loop_item_thumbnail'));
		add_action('XWOO_campaign_loop_item_content',                  	array($this, 'campaign_loop_item_content'));
        //Dashboard Campaigns
		add_action('XWOO_dashboard_campaign_loop_item_content',        	array($this, 'dashboard_campaign_loop_item_content'));
        add_action('XWOO_dashboard_campaign_loop_item_before_content', 	array($this, 'loop_item_thumbnail'));
        // Filter Search for Crowdfunding campaign
        add_filter('pre_get_posts' ,                                    array($this, 'search_shortcode_filter'));
        add_action('get_the_generator_html',                            array($this, 'tag_generator'), 10, 2 ); // Single Page Html
        add_action('get_the_generator_xhtml',                           array($this, 'tag_generator'), 10, 2 );
        add_action('wp',                                                array($this, 'woocommerce_single_page' ));
    }


    public function woocommerce_single_page(){
        if (is_product()){
            global $post;
            $product = wc_get_product($post->ID);
            if ($product->get_type() == 'xwoo'){
                add_action('woocommerce_single_product_summary',        array($this, 'single_fund_raised'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'loop_item_fund_raised_percent'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'single_fund_this_campaign_btn'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'campaign_location'), 20);
                add_action('woocommerce_single_product_summary',        array($this, 'creator_info'), 20);
                add_filter('woocommerce_single_product_image_html',     array($this, 'overwrite_product_feature_image'), 20);
            }
        }
    }


	public function search_shortcode_filter($query){
		if (!empty($_GET['product_type'])) {
			$product_type = $_GET['product_type'];
			if ($product_type == 'croudfunding') {
				if ($query->is_search) {
					$query->set('post_type', 'product');
					$taxquery = array(
						array(
							'taxonomy' => 'product_type',
							'field' => 'slug',
							'terms' => 'xwoo',
						)
					);
					if( XWOO_function()->wc_version() ){
						$taxquery['relation'] = 'AND';
						$taxquery[] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'exclude-from-search',
							'operator' => 'NOT IN',
						);
					}
					$query->set('tax_query', $taxquery);
				}
			}
		}
		return $query;
	}

	public function single_campaign_summary() {
		XWOO_function()->template('include/campaign-title');
		XWOO_function()->template('include/author');
		$this->loop_item_rating();
		$this->single_fund_raised();
		XWOO_function()->template('include/fund_raised_percent');
		$this->single_fund_this_campaign_btn();
		$this->campaign_location();
		$this->creator_info();
	}

	public function campaign_loop_item_content() {
		$this->loop_item_rating();
		$this->loop_item_title();
		$this->loop_item_author();
		$this->loop_item_location();
		XWOO_function()->template('include/loop/description');
		$this->loop_item_fund_raised_percent();
		$this->loop_item_funding_goal();
		$this->loop_item_time_remaining();
		$this->loop_item_fund_raised();
		$this->loop_item_button();
	}

	public function dashboard_campaign_loop_item_content() {
		$this->loop_item_title();
		$this->loop_item_author();
		$this->loop_item_location();
		$this->loop_item_fund_raised_percent();
		$this->loop_item_funding_goal();
		$this->loop_item_time_remaining();
		$this->loop_item_fund_raised();
		$this->loop_item_button();
	}


	public function campaign_location() {
		XWOO_function()->template('include/location');
	}

	public function campaign_single_tab() {
		XWOO_function()->template('include/campaign-tab');
    }
    
	public function campaign_single_feature_image() {
		XWOO_function()->template('include/feature-image');
	}

	public function campaign_single_description() {
		XWOO_function()->template('include/description');
	}

	public function single_fund_raised() {
		XWOO_function()->template('include/fund-raised');
	}

	public function single_fund_this_campaign_btn() {
		XWOO_function()->template('include/fund-campaign-btn');
	}

	public function single_campaign_tabs( $tabs = array() ) {
		global $product, $post;

		// Description tab - shows product content
		$tabs['description'] = array(
			'title'     => __( 'Campaign Story', 'xwoo' ),
			'priority'  => 10,
			'callback'  => array($this, 'campaign_story_tab')
		);

		$saved_campaign_update = get_post_meta($post->ID, 'wp_campaign_updates', true);
		$saved_campaign_update = json_decode($saved_campaign_update, true);
		if (is_array($saved_campaign_update) && count($saved_campaign_update) > 0) {
			$tabs['update'] = array(
				'title'     => __('Updates', 'xwoo'),
				'priority'  => 10,
				'callback'  => array($this ,'campaign_update_tab')
			);
		}

		$show_table = get_post_meta($post->ID, 'wp_show_contributor_table', true);
		if($show_table == '1') {
			$baker_list = XWOO_function()->get_customers_product();
			if (count($baker_list) > 0) {
				$tabs['baker_list'] = array(
					'title' => __('Backer List', 'xwoo'),
					'priority' => 10,
					'callback' => array($this, 'campaign_baker_list_tab')
				);
			}
		}

		// Reviews tab - shows comments
		if ( comments_open() ) {
			$tabs['reviews'] = array(
				'title'    => sprintf( __( 'Reviews (%d)', 'xwoo' ), $product->get_review_count() ),
				'priority' => 30,
				'callback' => 'comments_template'
			);
		}

		return $tabs;
	}

	public function campaign_story_tab() {
		XWOO_function()->template('include/tabs/story-tab');
	}

	public function wp_xwoo_campaign_rewards_tab() {
		XWOO_function()->template('include/tabs/rewards-tab');
	}

	public function campaign_update_tab() {
		XWOO_function()->template('include/tabs/update-tab');
	}

	public function campaign_baker_list_tab() {
		XWOO_function()->template('include/tabs/baker-list-tab');
    }
    
	public function creator_info() {
		XWOO_function()->template('include/creator-info');
	}

	public function overwrite_product_feature_image($img_html) {
		global $post;
		$url = trim(get_post_meta($post->ID, 'wp_funding_video', true));
		if ( !empty($url) ) {
			XWOO_function()->get_embeded_video( $url );
		} else {
			return $img_html;
		}
	}

	public function loop_item_thumbnail()  {
		XWOO_function()->template('include/loop/thumbnail');
	}

	public function loop_item_button() {
		XWOO_function()->template('include/loop/details_button');
	}

	public function loop_item_title() {
		XWOO_function()->template('include/loop/title');
	}

	public function loop_item_author() {
		XWOO_function()->template('include/loop/author');
	}

	public function loop_item_rating() {
		XWOO_function()->template('include/loop/rating_html');
	}

	public function loop_item_location() {
		XWOO_function()->template('include/loop/location');
	}

	public function loop_item_funding_goal() {
		XWOO_function()->template('include/loop/funding_goal');
	}

	public function loop_item_fund_raised() {
		XWOO_function()->template('include/loop/fund_raised');
	}

	public function loop_item_fund_raised_percent() {
		XWOO_function()->template('include/loop/fund_raised_percent');
	}

	public function loop_item_time_remaining() {
		XWOO_function()->template('include/loop/time_remaining');
	}

	public function story_right_sidebar() {
		XWOO_function()->template('include/tabs/rewards-sidebar-form');
	}


	public function tag_generator( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( XWOO_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="WP Crowdfunding ' . esc_attr( XWOO_VERSION ) . '" />';
				break;
		}
		return $gen;
	}


}