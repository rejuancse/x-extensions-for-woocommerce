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
    'posts_per_page'    => $posts_per_page,
    'paged'             => $page_numb
);

$current_page = get_permalink();
$the_query = new WP_Query( $args );
?>

<div class="xwoo-content">
<div class="xwoo-form campaign-listing-page">


<?php if ( $the_query->have_posts() ) : global $post; $i = 1;
    while ( $the_query->have_posts() ) : $the_query->the_post();
        ob_start();
?>
        <div class="xwoo-listings-dashboard xwoo-shadow xwoo-padding15 xwoo-clearfix">
            
            <div class="xwoo-listing-img">
                <a href="<?php echo get_permalink(); ?>" title="<?php  echo get_the_title(); ?>"> <?php echo woocommerce_get_product_thumbnail(); ?></a>
                <div class="overlay">
                    <div>
                        <div>
                            <a class="wp-crowd-btn wp-crowd-btn-primary" href="<?php echo get_permalink(); ?>"><?php _e('View','xwoo'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xwoo-listing-content clearfix">

                <div class="xwoo-admin-title float-left">
                    <!-- title -->
                    <h4><a href="<?php  echo get_permalink(); ?> "><?php echo get_the_title(); ?></a></h4>
                    
                    <!-- author -->
                    <p class="xwoo-author"><?php _e('by','xwoo'); ?> 
                        <a href="<?php echo xwoo_function()->get_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo xwoo_function()->get_author_name(); ?></a>
                    </p>

                    <!-- location -->
                    <?php $location = xwoo_function()->campaign_location(); ?>
                    <?php if($location) { ?>
                        <div class="xwoo-location">
                            <i class="xwoo-icon xwoo-icon-location"></i>
                            <div class="xwoo-meta-desc"><?php echo $location; ?></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="xwoo-admin-location float-right">
                    <?php
                    $operation_btn = '';
                    $operation_btn .= '<div class="xwoo-fields-action">';
                        $page_id = get_option('wp_form_page_id');
                        if ($page_id != '') {
                            $permalink_edit     = add_query_arg( array( 'action' => 'edit', 'postid' => get_the_ID() ) , get_permalink($page_id) );
                            $permalink_update   = add_query_arg( array( 'page_type' => 'update', 'postid' => get_the_ID() ) , $current_page );
                            $operation_btn .= '<span><a href="'.$permalink_update.'">'.__("Update", "wp-xwoo").'</a></span>';
                            $operation_btn .= '<span><a href="' . $permalink_edit . '" class="wp-crowd-btn wp-crowd-btn-primary">' . __("Edit", "wp-xwoo") . '</a></span>';
                        }
                        
                    if (get_post_status() == 'draft'){
	                    $operation_btn .='<span class="wp-crowd-btn xwoo-campaign-status">['.__("Draft", "wp-xwoo").']</span>';
                    }
                    $operation_btn .= '</div>';
                    echo $operation_btn;
                    ?>
                </div>
                <div class="xwoo-clearfix"></div>
                <div class="xwoo-percent-rund-wrap">
                    
                    <!-- percent -->
                    <?php $raised_percent = xwoo_function()->get_fund_raised_percent_format(); ?>
                    <div class="xwoo-pie-chart" data-size="60" data-percent="<?php echo $raised_percent; ?>">
                        <div class="sppb-chart-percent"><span><?php echo $raised_percent; ?></span></div>
                    </div>

                    <!-- fund-raised -->
                    <?php 
                    $raised_percent = xwoo_function()->get_fund_raised_percent_format();
                    $raised = 0;
                    $total_raised = xwoo_function()->get_total_fund();
                    if ($total_raised){
                        $raised = $total_raised;
                    }
                    ?>
                    <div class="xwoo-fund-raised">
                        <div class="xwoo-meta-desc"><?php echo wc_price($raised); ?></div>
                        <div class="xwoo-meta-name"><?php _e('Fund Raised', 'xwoo'); ?></div>
                    </div>

                    <!-- Funding Goal -->
                    <?php $funding_goal = get_post_meta($post->ID, '_nf_funding_goal', true); ?>
                    <div class="xwoo-funding-goal">
                        <div class="xwoo-meta-desc"><?php echo wc_price( $funding_goal ); ?></div>
                        <div class="xwoo-meta-name"><?php _e('Funding Goal', 'xwoo'); ?></div>
                    </div>

                    <!--  Days to go -->
                    <?php $days_remaining = apply_filters('date_expired_msg', __('0', 'xwoo'));
                    if (xwoo_function()->get_date_remaining()){
                        $days_remaining = apply_filters('date_remaining_msg', __(xwoo_function()->get_date_remaining(), 'xwoo'));
                    }

                    $end_method = get_post_meta(get_the_ID(), 'wp_campaign_end_method', true);

                    if ($end_method != 'never_end'){ ?>
                        <div class="xwoo-time-remaining">
                            <?php if (xwoo_function()->is_campaign_started()){ ?>
                                <div class="xwoo-meta-desc"><?php echo xwoo_function()->get_date_remaining(); ?></div>
                                <div class="xwoo-meta-name"><?php _e( 'Days to go','xwoo' ); ?></div>
                            <?php } else { ?>
                                <div class="xwoo-meta-desc"><?php echo xwoo_function()->days_until_launch(); ?></div>
                                <div class="xwoo-meta-name"><?php _e( 'Days Until Launch','xwoo' ); ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div><!-- xwoo-percent-rund-wrap -->
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

$html .= xwoo_function()->get_pagination( $page_numb , $the_query->max_num_pages );

$html .= '<div style="clear: both;"></div>';
$html .= '</div>';
$html .= '</div>';