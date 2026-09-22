<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
    <div class="wp-xewc-extensions-list">
        <h1 class="addon-list-heading"><?php esc_html_e('X-Extensions for WooCommerce', 'x-extensions-for-woocommerce'); ?></h1>
        <br class="clear">
		<?php
            $xewc_extensions = apply_filters('xewc_extensions_lists_config', array());
            if (is_array($xewc_extensions) && count($xewc_extensions)){
                ?>
                <div class="wp-list-table widefat plugin-install">
                    <div id="the-list">
                        <?php
                        foreach ( $xewc_extensions as $xewc_bas_name => $xewc_addon ) {

                            $xewc_addon_config = xewc_function()->get_addon_config($xewc_bas_name);
                            $xewc_is_enable = (bool)xewc_function()->avalue_dot('is_enable', $xewc_addon_config);

                            $xewc_thumbnail_url =  XEWC_DIR_URL.'assets/images/xewc-plugin.png';
                            if (file_exists($xewc_addon['path'].'assets/images/thumbnail.png') ){
                                $xewc_thumbnail_url = $xewc_addon['url'].'assets/images/thumbnail.png';
                            } elseif (file_exists($xewc_addon['path'].'assets/images/thumbnail.svg')){
                                $xewc_thumbnail_url = $xewc_addon['url'].'assets/images/thumbnail.svg';
                            }
                            ?>
                            <div class="plugin-card">
                                <div class="plugin-card-top">
                                    <div class="icon">
                                        <?php echo '<img src="' . esc_url( $xewc_thumbnail_url ) . '">';  ?>
                                    </div>

                                    <div class="plugin-info">
                                        <h3><?php echo esc_html( $xewc_addon['name'] ); ?></h3>
                                        <p><?php echo esc_html( $xewc_addon['description'] ); ?></p>
                                    </div>

                                    <div class="action-btn">
                                        <ul class="plugin-action-buttons">
                                            <li>
                                                <label class="btn-switch">
                                                    <input type="checkbox" class="xewc_extensions_list_item" value="1" name="<?php echo esc_attr( $xewc_bas_name ); ?>" <?php checked(true, $xewc_is_enable) ?> />
                                                    <div class="btn-slider btn-round"></div>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <br class="clear">
                <?php
            }
		?>
    </div>
</div>
