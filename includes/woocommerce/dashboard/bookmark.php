<?php
defined('ABSPATH') || exit;

$page_numb = max(1, get_query_var('paged'));
$campaign_ids = get_user_meta( get_current_user_id(), 'loved_campaign_ids', true );
$campaign_ids = json_decode( $campaign_ids, true );
if (empty($campaign_ids)) {
    $campaign_ids = array(9999999);
}
$posts_per_page = get_option('posts_per_page', 10);
$args = array(
    'post_type'         => 'product',
    'post__in'          => $campaign_ids,
    'posts_per_page'    => $posts_per_page,
    'paged'             => $page_numb
);
$the_query = new WP_Query($args);

ob_start(); ?>

<div class="xwoo-content">
    <div class="xwoo-form campaign-listing-page">
        <div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">
            <?php if ($the_query->have_posts()) : global $post; ?>
                <div class="xwoo-responsive-table">
                    <table class="stripe-table">
                        <thead>
                            <tr>
                                <th><?php _e("Title", "xwoo"); ?></th>
                                <th><?php _e("Created Time", "xwoo"); ?></th>
                                <th><?php _e("Action", "xwoo"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                                <tr>
                                    <td><?php the_title(); ?></td>
                                    <td><?php _e('Created at', 'xwoo'); ?> : <?php the_date(); ?></td>
                                    <td><a href="<?php the_permalink(); ?>"><?php _e("View", "xwoo"); ?></a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php _e('Sorry, No bookmark found.', 'xwoo'); ?></p>
            <?php endif; ?>
        </div>
        <?php echo xwoo_function()->get_pagination($page_numb, $the_query->max_num_pages); ?>
    </div>
</div>

<?php $html .= ob_get_clean(); ?>