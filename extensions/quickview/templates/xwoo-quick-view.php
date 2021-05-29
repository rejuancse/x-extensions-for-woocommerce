<?php
/**
 * Quick view.
 *
 * @author  XWOO
 * @package XWOO WooCommerce Quick View
 * @version 1.0.0
 */

defined( 'XWOO_QUICK_VIEW' ) || exit; // Exit if accessed directly.

?>

<div id="xwoo-quick-view-modal">
	<div class="xwoo-quick-view-overlay"></div>
	<div class="xwoo-wcqv-wrapper">
		<div class="xwoo-wcqv-main">
			<div class="xwoo-wcqv-head">
				<a href="#" id="xwoo-quick-view-close" class="xwoo-wcqv-close">X</a>
			</div>
			<div id="xwoo-quick-view-content" class="woocommerce single-product"></div>
		</div>
	</div>
</div>
<?php
