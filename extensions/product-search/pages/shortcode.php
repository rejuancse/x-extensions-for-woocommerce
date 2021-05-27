<div class="wrap xwoo-wrap">
    <div id="xwoo-tabs" class="xwoo-settings-panel xwoo-main-setting-panel">
        <div class="xwoo-tabs-content xwoo-main-setting-content">
            <table class="form-table xwoo-option-table xwoo-main-setting-table">
                <tr class="xwoo-field xwoo-field-group">
                    <th>
                        <?php _e('Products Search Shortcodes', 'xwoo'); ?>
                    </th>

                    <td>
                        <h3><?php _e('Integration code by theme location', 'xwoo'); ?></h3>
                        <?php
                            echo '<div class="wp-megamenu-integration-code">';
                                echo "<p class='integration-code-row'> <span>PHP</span>  <code> &lt;?php echo do_shortcode('[xwoo_product_search]') ?&gt;</code></p>";
                                echo "<p class='integration-code-row'> <span>SHORTCODE</span> <code> [xwoo_product_search] </code> </p>";
                            echo '</div>';
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>
</div>
