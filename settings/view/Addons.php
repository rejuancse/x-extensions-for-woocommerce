<div class="wrap">
    <div class="wp-xwoo-extensions-list">
        <h1 class="addon-list-heading"><?php _e('XWOO Extensions', 'xwoo'); ?></h1>
        <br class="clear">
		<?php
            $extensions = apply_filters('xwoo_extensions_lists_config', array());
            if (is_array($extensions) && count($extensions)){
                ?>
                <div class="wp-list-table widefat plugin-install">
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
                        <?php } ?>
                    </div>
                </div>
                <br class="clear">
                <?php
            }
		?>
    </div>
</div>
