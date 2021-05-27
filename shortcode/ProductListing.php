<?php
namespace XWOO\shortcode;

defined( 'ABSPATH' ) || exit;

class Product_Listing {

    function __construct() {
        add_shortcode( 'product_listing', array( $this, 'products_callback_function' ) );
    }

    function products_callback_function( $atts, $shortcode ) {

        $post_limit = get_option( 'product_listing_post_number', 10 );
		
        $atts = shortcode_atts( array(
            'number' => '9',
            'column' => '4',
        ), $atts );

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['number'],
        );
		ob_start();

        $query = new \WP_Query($args); ?>

        <div class="woocommerce-page">
			<ul class="products columns-<?php echo $atts['column']; ?>">
				<?php if ( $query->have_posts() ) : ?>
					<?php while ( $query->have_posts() ) : $query->the_post(); 
						$product = new \WC_Product(get_the_ID());
						$price_html = $product->get_price_html();
						$cats = get_the_term_list( get_the_ID(), 'product_cat' );
						?>

						<li class="entry product"> 
							<div class="inner">
								<div class="product-img">
									<a href="<?php the_permalink(); ?>">
										<!-- <span class="onsale">Sale!</span> -->
										<?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
									</a>
								</div>
								<h4><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
								<span class="category"><?php echo $cats; ?></span>
								<div class="price-box">
									<span class="product-price"><?php echo $price_html; ?></span>
								</div>
								<div class="content">
									<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
								</div>
							</div>
						</li>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				<?php endif; ?>
			</ul>
        </div>
        <?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
    }
}
