<?php
/**
 * Frontend class
 *
 * @author  XWOO
 * @package XWOO WooCommerce Quick View
 * @version 1.1.1
 */

defined( 'XWOO_QUICK_VIEW' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'XWOO_QUICK_VIEW_Frontend' ) ) {
	/**
	 * Admin class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class XWOO_QUICK_VIEW_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var XWOO_QUICK_VIEW_Frontend
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = XWOO_QUICK_VIEW_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return XWOO_QUICK_VIEW_Frontend
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

			// Enqueue gift card script.
			if ( defined( 'XWOO_YWGC_FILE' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_gift_card_script' ) );
			}

			// Quick view AJAX.
			add_action( 'wp_ajax_xwoo_load_product_quick_view', array( $this, 'xwoo_load_product_quick_view_ajax' ) );
			add_action( 'wp_ajax_nopriv_xwoo_load_product_quick_view', array( $this, 'xwoo_load_product_quick_view_ajax' ) );

			// Load modal template.
			add_action( 'wp_footer', array( $this, 'xwoo_quick_view' ) );

			// Load action for product template.
			$this->xwoo_quick_view_action_template();
			// Add quick view button.
			add_action( 'init', array( $this, 'add_button' ) );

			add_shortcode( 'xwoo_quick_view', array( $this, 'quick_view_shortcode' ) );
			add_filter( 'woocommerce_add_to_cart_form_action', array( $this, 'avoid_redirect_to_single_page' ), 10, 1 );
		}

		/**
		 * Enqueue styles and scripts
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return void
		 */
		public function enqueue_styles_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script( 'xwoo-wcqv-frontend', XWOO_QUICK_VIEW_ASSETS_URL . '/js/frontend' . $suffix . '.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'xwoo-wcqv-frontend' );
			wp_enqueue_style( 'xwoo-quick-view', XWOO_QUICK_VIEW_ASSETS_URL . '/css/xwoo-quick-view.css', array(), $this->version );

			$background_modal  = get_option( 'wp_button_bg_color', '#ffffff' );
			$close_color       = get_option( 'wp_button_text_color', '#cdcdcd' );
			$close_color_hover = get_option( 'wp_button_bg_hover_color', '#ff0000' );

			$inline_style = "
				#xwoo-quick-view-modal .xwoo-wcqv-main{background:{$background_modal};}
				#xwoo-quick-view-close{color:{$close_color};}
				#xwoo-quick-view-close:hover{color:{$close_color_hover};}";

			wp_add_inline_style( 'xwoo-quick-view', $inline_style );
		}


		/**
		 * Enqueue scripts for XWOO WooCommerce Gift Cards
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return void
		 */
		public function enqueue_gift_card_script() {
			if ( ! wp_script_is( 'xwoo-frontend' ) && apply_filters( 'xwoo_load_gift_card_script_pages_for_quick_view', is_shop() ) && version_compare( XWOO_YWGC_VERSION, '3.0.0', '<' ) ) {
				wp_register_script( 'xwoo-frontend', XWOO_YWGC_URL . 'assets/js/' . xwoo_load_js_file( 'xwoo-frontend.js' ), array( 'jquery', 'woocommerce' ), XWOO_YWGC_VERSION, true );
				wp_enqueue_script( 'xwoo-frontend' );
			} elseif ( ! wp_script_is( 'xwoo-frontend' ) && apply_filters( 'xwoo_load_gift_card_script_pages_for_quick_view', is_shop() ) ) {
				wp_register_script( 'xwoo-frontend', XWOO_YWGC_URL . 'assets/js/' . xwoo_load_js_file( 'xwoo-frontend.js' ), array( 'jquery', 'woocommerce', 'jquery-ui-datepicker', 'accounting' ), XWOO_YWGC_VERSION, true );

				wp_localize_script(
					'xwoo-frontend',
					'xwoo_data',
					array(
						'loader'        => apply_filters( 'xwoo_gift_cards_loader', XWOO_YWGC_ASSETS_URL . '/images/loading.gif' ),
						'ajax_url'      => admin_url( 'admin-ajax.php' ),
						'wc_ajax_url'   => WC_AJAX::get_endpoint( '%%endpoint%%' ),
						'notice_target' => apply_filters( 'xwoo_xwoo_gift_card_notice_target', 'div.woocommerce' ),
					)
				);

				wp_enqueue_script( 'xwoo-frontend' );
			}
		}

		/**
		 * Add quick view button hooks
		 *
		 * @since 1.5.0
		 * @author Francesco Licandro
		 * @return void
		 */
		public function add_button() {
			if ( $this->is_proteo_add_to_cart_hover() ) {
				add_action( 'xwoo_proteo_products_loop_add_to_cart_actions', array( $this, 'xwoo_add_quick_view_button' ), 55 );
			} else {
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'xwoo_add_quick_view_button' ), 15 );
			}

			add_action( 'xwoo_wcwl_table_after_product_name', array( $this, 'add_quick_view_button_wishlist' ), 15 );
		}


		/**
		 * Check if current theme is XWOO Proteo and if the add to cart button is visible on image hover
		 *
		 * @since 1.6.7
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_proteo_add_to_cart_hover() {
			return defined( 'XWOO_PROTEO_VERSION' ) && 'hover' === get_theme_mod( 'xwoo_proteo_products_loop_add_to_cart_position', 'classic' );
		}

		/**
		 * Add quick view button in wc product loop
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @param integer|string $product_id The product id.
		 * @param string         $label      The button label.
		 * @param boolean        $return     True to return, false to echo.
		 * @return string|void
		 */
		public function xwoo_add_quick_view_button( $product_id = 0, $label = '', $return = false ) {

			global $product;

			if ( ! $product_id && $product instanceof WC_Product ) {
				$product_id = $product->get_id();
			}

			if ( ! apply_filters( 'xwoo_quickview_show_quick_view_button', true, $product_id ) ) {
				return;
			}

			$button = '';
			if ( $product_id ) {
				if ( ! $label ) {
					$label = $this->get_button_label();
				}

				$button = '<a href="#" class="A button xwoo-wcqv-button" data-product_id="' . esc_attr( $product_id ) . '">' . $label . '</a>';
				$button = apply_filters( 'xwoo_add_quick_view_button_html', $button, $label, $product );
			}

			if ( $return ) {
				return $button;
			}

			echo $button;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Add quick view button in wishlist
		 *
		 * @since 1.5.1
		 * @author Francesco Licandro
		 * @param XWOO_WCWL_Wishlist_Item $item THe wishlist item.
		 * @return string|void
		 */
		public function add_quick_view_button_wishlist( $item ) {
			if ( $item instanceof XWOO_WCWL_Wishlist_Item ) {
				$this->xwoo_add_quick_view_button( $item->get_product_id() );
			}
		}

		/**
		 * Enqueue scripts and pass variable to js used in quick view
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return bool
		 */
		public function xwoo_woocommerce_quick_view() {

			wp_enqueue_script( 'wc-add-to-cart-variation' );
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
					wp_enqueue_script( 'zoom' );
				}
				if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
					wp_enqueue_script( 'photoswipe-ui-default' );
					wp_enqueue_style( 'photoswipe-default-skin' );
					if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
						add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
					}
				}
				wp_enqueue_script( 'wc-single-product' );
			}

			// Enqueue WC Color and Label Variations style and script.
			wp_enqueue_script( 'xwoo_wccl_frontend' );
			wp_enqueue_style( 'xwoo_wccl_frontend' );

			// Allow user to load custom style and scripts!
			do_action( 'xwoo_quick_view_custom_style_scripts' );

			wp_localize_script(
				'xwoo-wcqv-frontend',
				'xwoo_qv',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
					'loader'  => apply_filters( 'xwoo_quick_view_loader_gif', XWOO_QUICK_VIEW_ASSETS_URL . '/image/qv-loader.gif' ),
					'lang'    => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '',
				)
			);

			return true;
		}

		/**
		 * Ajax action to load product in quick view
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return void
		 */
		public function xwoo_load_product_quick_view_ajax() {
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_REQUEST['product_id'] ) ) {
				die();
			}

			global $sitepress;

			$product_id = intval( $_REQUEST['product_id'] );
			$attributes = array();

			/**
			 * WPML Suppot:  Localize Ajax Call
			 */
			$lang = isset( $_REQUEST['lang'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lang'] ) ) : '';
			if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
				$sitepress->switch_lang( $lang, true );
			}

			// Set the main wp query for the product.
			wp( 'p=' . $product_id . '&post_type=product' );

			// Remove product thumbnails gallery.
			remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			// Change template for variable products.
			if ( isset( $GLOBALS['xwoo_wccl'] ) ) {
				$GLOBALS['xwoo_wccl']->obj = new XWOO_WCCL_Frontend();
				$GLOBALS['xwoo_wccl']->obj->override();
			}elseif( defined( 'XWOO_WCCL_PREMIUM' ) && XWOO_WCCL_PREMIUM && class_exists( 'XWOO_WCCL_Frontend' ) ) {
				$attributes = XWOO_WCCL_Frontend()->create_attributes_json( $product_id, true );
			}
						ob_start();
			wc_get_template( 'xwoo-quick-view-content.php', array(), '', XWOO_QUICK_VIEW_DIR_PATH . 'templates/' );
			$html = ob_get_contents();  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			ob_end_clean();

			wp_send_json(
				array(
					'html'                 => $html,
					'prod_attr'            => $attributes,
				)
			);

			die();
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Load quick view template
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return void
		 */
		public function xwoo_quick_view() {
			$this->xwoo_woocommerce_quick_view();
			wc_get_template( 'xwoo-quick-view.php', array(), '', XWOO_QUICK_VIEW_DIR_PATH . 'templates/' );
		}

		/**
		 * Load wc action for quick view product template
		 *
		 * @access public
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return void
		 */
		public function xwoo_quick_view_action_template() {

			// Image.
			add_action( 'xwoo_quickview_product_image', 'woocommerce_show_product_sale_flash', 10 );
			add_action( 'xwoo_quickview_product_image', 'woocommerce_show_product_images', 20 );

			// Summary.
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_title', 5 );
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_rating', 10 );
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_price', 15 );
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_excerpt', 20 );
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
			add_action( 'xwoo_quickview_product_summary', 'woocommerce_template_single_meta', 30 );
		}

		/**
		 * Get Quick View button label
		 *
		 * @since  1.2.0
		 * @author Francesco Licandro
		 * @return string
		 */
		public function get_button_label() {
			$label = get_option( 'btn_quick_view' );
			$label = call_user_func( '__', $label, 'xwoo' );

			return apply_filters( 'xwoo_quickview_button_label', esc_html( $label ) );
		}

		/**
		 * Quick View shortcode button
		 *
		 * @access public
		 * @since  1.0.7
		 * @author Francesco Licandro
		 * @param array $atts An array of shortcode attributes.
		 * @return string
		 */
		public function quick_view_shortcode( $atts ) {

			$atts = shortcode_atts(
				array(
					'product_id' => 0,
					'label'      => '',
				),
				$atts
			);

			extract( $atts ); // phpcs:ignore

			return $this->xwoo_add_quick_view_button( intval( $product_id ), $label, true );
		}

		/**
		 * Check if is quick view
		 *
		 * @access public
		 * @since  1.3.1
		 * @author Francesco Licandro <francesco.licandro@xwooemes.com>
		 * @return bool
		 */
		public function xwoo_is_quick_view() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'xwoo_load_product_quick_view' === $_REQUEST['action'] );
		}

		/**
		 * Avoid redirect to single product page on add to cart action in quick view
		 *
		 * @since  1.3.1
		 * @author Francesco Licandro
		 * @param string $value The redirect url value.
		 * @return string
		 */
		public function avoid_redirect_to_single_page( $value ) {
			if ( $this->xwoo_is_quick_view() ) {
				return '';
			}
			return $value;
		}
	}
}
/**
 * Unique access to instance of XWOO_QUICK_VIEW_Frontend class
 *
 * @since 1.0.0
 * @return XWOO_QUICK_VIEW_Frontend
 */
function XWOO_QUICK_VIEW_Frontend() { // phpcs:ignore
	return XWOO_QUICK_VIEW_Frontend::get_instance();
}