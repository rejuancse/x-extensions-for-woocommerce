<div class="wrap">
    <div class="wp-xwoo-extensions-list">
        <h1 class="addon-list-heading"><?php _e('XWOO Extensions', 'xwoo'); ?></h1>
        <br class="clear">
		<?php
        $extensions = apply_filters('xwoo_extensions_lists_config', array());

		if (is_array($extensions) && count($extensions)){
			?>
            <div class="wp-list-table widefat plugin-install">
                <style>
                    .wp-xwoo-extensions-list .plugin-card-top{
                        min-height: 98px;
                    }
                    .wp-xwoo-extensions-list .plugin-icon{
                        width: 64px;
                        height: 64px;
                    }
                    .wp-xwoo-extensions-list .wp-list-table a{
                        color: #fff;
                        margin-top: 100px;
                        padding: 10px 20px;
                        border-radius: 4px;
                        text-decoration: none;
                        background: #0085ba;
                        background-color: #0073aa;
                        border-color: #006a95 #00648c #00648c;
                        box-shadow: inset 0 -1px 0 #00648c;
                        text-shadow: 0 -1px 1px #005d82, 1px 0 1px #005d82, 0 1px 1px #005d82, -1px 0 1px #005d82;
                    }
                    .wp-xwoo-extensions-list .plugin-card .name,
                    .wp-xwoo-extensions-list .plugin-card .desc{
                        margin-left: 84px;
                    }

                    .wp-xwoo-extensions-list .btn-switch {
                        display: inline-block;
                        height: 22px;
                        position: relative;
                        width: 50px;
                    }
                    .wp-xwoo-extensions-list .btn-switch input {
                        display:none;
                    }
                    .wp-xwoo-extensions-list .btn-slider {
                        background-color: #ccc;
                        bottom: 0;
                        cursor: pointer;
                        left: 0;
                        position: absolute;
                        right: 0;
                        top: 0;
                        transition: .4s;
                    }
                    .wp-xwoo-extensions-list .btn-slider:before {
                        background-color: #fff;
                        bottom: 3px;
                        content: "";
                        height: 16px;
                        left: 4px;
                        position: absolute;
                        transition: .4s;
                        width: 16px;
                    }
                    .wp-xwoo-extensions-list .btn-switch input:checked + .btn-slider {
                        background-color: #66bb6a;
                    }
                    .wp-xwoo-extensions-list .btn-switch input:checked + .btn-slider:before {
                        transform: translateX(26px);
                    }
                    .wp-xwoo-extensions-list .btn-slider.btn-round {
                        border-radius: 34px;
                    }
                    .wp-xwoo-extensions-list .btn-slider.btn-round:before {
                        border-radius: 50%;
                    }
                </style>
                <div id="the-list">
					<?php
					foreach ( $extensions as $basName => $addon ) {
						$addonConfig = xwoo_function()->get_addon_config($basName);
                        $isEnable = (bool)xwoo_function()->avalue_dot('is_enable', $addonConfig);

						$thumbnailURL =  XWOO_DIR_URL.'assets/images/xwoo-plugin.png';
						if (file_exists($addon['path'].'assets/images/thumbnail.png') ){
							$thumbnailURL = $addon['url'].'assets/images/thumbnail.png';
                        } elseif (file_exists($addon['path'].'assets/images/thumbnail.svg')){
							$thumbnailURL = $addon['url'].'assets/images/thumbnail.svg';
						}
						?>
                        <div class="plugin-card">
                            <div class="plugin-card-top">
                                <div class="name column-name">
                                    <h3>
										<?php
                                        echo $addon['name']; 
										echo "<img src='{$thumbnailURL}' class='plugin-icon' alt=''>";
										?>
                                    </h3>
                                </div>
                                <div class="action-links">
                                    <ul class="plugin-action-buttons">
                                        <li>
                                            <label class="btn-switch">
                                                <input type="checkbox" class="xwoo_extensions_list_item" value="1" name="<?php echo $basName; ?>" <?php checked(true, $isEnable) ?> />
                                                <div class="btn-slider btn-round"></div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="desc column-description">
                                    <p><?php echo $addon['description']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php }

                    //PRO ADDONS LIST FOR DISPLAY
                    if( xwoo_function()->is_free() || (!function_exists('WC') && !xwoo_function()->is_free()) ) {
                        $proAddons = array(
                            'bookmarks' => array(
                                'name'          => __( 'Bookmarks', 'xwoo' ),
                                'description'   => __( 'Products bookmarks in products list.', 'xwoo' ),
                            ),
                            'variable-color' => array(
                                'name'          => __( 'Product Variable Color', 'xwoo' ),
                                'description'   => __( 'Variable products colors list.', 'xwoo' ),
                            ),
                            'product-love' => array(
                                'name'          => __( 'Product Love', 'xwoo' ),
                                'description'   => __( 'Love your products in your products list.', 'xwoo' ),
                            ),
                            'reports' => array(
                                'name'          => __( 'Reports', 'xwoo' ),
                                'description'   => __( 'Get detailed analytics & stats using advanced filters with powerful reports.', 'xwoo' ),
                            ),
                            'delivery-slot' => array(
                                'name'          => __( 'Product Delivery Slot', 'xwoo' ),
                                'description'   => __( 'Enable product delivery date/slot your product.', 'xwoo' ),
                            ),
                            'wistlist' => array(
                                'name'          => __( 'WistList', 'xwoo' ),
                                'description'   => __( 'Support native payment system for all donations using the native wallet addon.', 'xwoo' ),
                            ),
                            'reword' => array(
                                'name'          => __( 'Reword', 'xwoo' ),
                                'description'   => __( 'Support native payment system for all donations using the native wallet addon.', 'xwoo' ),
                            ),
                            'donate' => array(
                                'name'          => __( 'Donate', 'xwoo' ),
                                'description'   => __( 'Support native payment system for all donations using the native wallet addon.', 'xwoo' ),
                            )
                        );

                        foreach ( $proAddons as $basName => $addon ) {
                            $addonConfig = xwoo_function()->get_addon_config($basName);
    
                            $extensions_path = trailingslashit(XWOO_DIR_PATH."assets/extensions/{$basName}");
                            $extensions_url = trailingslashit(XWOO_DIR_URL."assets/extensions/{$basName}");
    
                            $thumbnailURL =  XWOO_DIR_URL.'assets/images/xwoo-plugin.png';
    
                            if (file_exists($extensions_path.'thumbnail.png') ) {
                                $thumbnailURL = $extensions_url.'thumbnail.png';
                            } elseif (file_exists($extensions_path.'thumbnail.svg')) {
                                $thumbnailURL = $extensions_url.'thumbnail.svg';
                            }
    
                            ?>
                            <div class="plugin-card">
                                <div class="plugin-card-top">
                                    <div class="name column-name">
                                        <h3>
                                            <?php
                                            echo $addon['name'];
                                            echo "<img src='{$thumbnailURL}' class='plugin-icon' alt=''>";
                                            ?>
                                        </h3>
                                    </div>
                                    <div class="action-links">
                                        <ul class="plugin-action-buttons">
                                            <?php if( xwoo_function()->is_free() ) { ?>
                                                <li>
                                                    <a href="https://www.xwoo.com/product/wp-xwoo-plugin/?utm_source=xwoo_plugin"
                                                    class="addon-buynow-link" target="_blank"><?php _e('Buy Now','xwoo'); ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="desc column-description">
                                        <p><?php echo $addon['description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } ?>
                </div>
            </div>

            <br class="clear">
			<?php
		}
		?>
    </div>
</div>
