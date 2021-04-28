<?php
defined( 'ABSPATH' ) || exit;

$page_numb = max( 1, get_query_var('paged') );
$posts_per_page = get_option( 'posts_per_page',10 );
$args = array(
    'post_type' 		=> 'product',
    'post_status'		=> array('publish', 'draft'),
    'author'    		=> get_current_user_id(),
    'tax_query' 		=> array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => 'xwoo',
        ),
    ),
    'posts_per_page'    => 4,
    'paged'             => $page_numb
);

$html .= '<div class="xwoo-row wp-dashboard-row">';
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) :
    global $post;
    $i = 1;
    while ( $the_query->have_posts() ) : $the_query->the_post();
        ob_start(); 
        $permalink = xwoo_function()->is_published() ? get_permalink() : '#';
        ?>
        <div class="xwoo-col6">
            <div class="wpcrowd-listing">
                <a href="<?php echo $permalink; ?>" title="<?php  echo get_the_title(); ?>"> <?php echo woocommerce_get_product_thumbnail(); ?></a>
            </div>
            <div class="wpcrowd-listing-content">
                <div class="wpcrowd-admin-title">
                    <h3><a href="<?php echo $permalink; ?> "><?php echo get_the_title(); ?></a></h3>
                </div>
                    <div class="wpcrowd-admin-metadata">
                        <div class="wpcrowd-admin-meta-info">
                            <!--  Days to go -->
                            <span class="xwoo-meta-wrap">
                                <?php $days_remaining = apply_filters('date_expired_msg', __('0', 'xwoo'));
                                if (xwoo_function()->get_date_remaining()){
                                    $days_remaining = apply_filters('date_remaining_msg', __(xwoo_function()->get_date_remaining(), 'xwoo'));
                                }
                                $end_method = get_post_meta(get_the_ID(), 'wp_campaign_end_method', true);
                                if ($end_method != 'never_end'){ ?>
                                    <?php if (xwoo_function()->is_campaign_started()){ ?>
                                        <span class="info-text"><?php echo xwoo_function()->get_date_remaining().' '; _e( 'Days to go','xwoo' ); ?></span>
                                    <?php } else { ?>
                                        <span class="info-text"><?php echo xwoo_function()->days_until_launch().' '; _e( 'Days Until Launch','xwoo' ); ?></span>
                                    <?php } ?>
                                <?php } ?>
                            </span>
                            <!-- author -->
                            <span class="xwoo-meta-wrap">
                                <span class="xwoo-meta-name"><?php _e('by','xwoo'); ?> </span>
                                <a href="<?php echo xwoo_function()->get_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo xwoo_function()->get_author_name(); ?></a>
                            </span>

                            <!-- fund-raised -->
                            <?php 
                            $raised_percent = xwoo_function()->get_fund_raised_percent_format();
                            $raised = 0;
                            $total_raised = xwoo_function()->get_total_fund();
                            if ($total_raised){
                                $raised = $total_raised;
                            }
                            ?>
                            <span class="xwoo-meta-wrap">
                                <span class="xwoo-meta-name"><?php _e('Total', 'xwoo'); ?> </span>
                                <?php echo wc_price($raised); ?>
                            </span>
                            <span class="xwoo-meta-wrap">
                                <!-- Funding Goal -->
                                <?php $funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true); ?>
                                <span class="xwoo-meta-name"><?php _e('Goal', 'xwoo'); ?></span>
                                <?php echo wc_price( $funding_goal ); ?>
                            </span>   

                        </div><!--wpcrowd-admin-meta-info -->
                    </div><!-- wpcrowd-admin-metadata -->
            </div><!-- xwoo-listing-content -->
            <?php do_action('XWOO_dashboard_campaign_loop_item_after_content'); ?>
            <div style="clear: both"></div>
        </div>
        <?php $i++;
        $html .= ob_get_clean();
    endwhile;
    wp_reset_postdata();
else :
    $html .= "<p>".__( 'Sorry, no Campaign Found.','xwoo' )."</p>";
endif;
$html .= '</div>';
$html .= xwoo_function()->get_pagination( $page_numb , $the_query->max_num_pages );

