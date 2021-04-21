<?php
namespace XWOO\shortcode;

defined( 'ABSPATH' ) || exit;

class Search {
    function __construct() {
        add_shortcode( 'XWOO_search', array( $this, 'search_callback' ) );
    }

    function search_callback() {
        ob_start(); ?>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" class="search-field" placeholder="<?php _e("Search", "wp-xwoo"); ?>" value="<?php if (isset($_GET['s'])) {
                echo $_GET['s'];
            } ?>" name="s">
            <input type="hidden" name="post_type" value="product">
            <input type="hidden" name="product_type" value="croudfunding">
            <button type="submit"><?php _e("Search", "wp-xwoo"); ?></button>
        </form>
        <?php
        return ob_get_clean();
    }
}