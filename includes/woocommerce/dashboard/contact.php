<?php
defined( 'ABSPATH' ) || exit;

$id 		    = get_current_user_id();
// Billing Data
$f_name 	    = get_user_meta( $id,'shipping_first_name',true );
$l_name 	    = get_user_meta( $id,'shipping_last_name',true );
$company 	    = get_user_meta( $id,'shipping_company',true );
$address1 	    = get_user_meta( $id,'shipping_address_1',true );
$address2 	    = get_user_meta( $id,'shipping_address_2',true );
$city 		    = get_user_meta( $id,'shipping_city',true );
$postcode 	    = get_user_meta( $id,'shipping_postcode',true );
$country 	    = get_user_meta( $id,'shipping_country',true );
$state 		    = get_user_meta( $id,'shipping_state',true );
// Shipping Data
$b_f_name       = get_user_meta( $id,'billing_first_name',true );
$b_l_name       = get_user_meta( $id,'billing_last_name',true );
$b_company      = get_user_meta( $id,'billing_company',true );
$b_address1     = get_user_meta( $id,'billing_address_1',true );
$b_address2     = get_user_meta( $id,'billing_address_2',true );
$b_city         = get_user_meta( $id,'billing_city',true );
$b_postcode     = get_user_meta( $id,'billing_postcode',true );
$b_country      = get_user_meta( $id,'billing_country',true );
$b_state        = get_user_meta( $id,'billing_state',true );
$b_phone        = get_user_meta( $id,'billing_phone',true );
$b_email        = get_user_meta( $id,'billing_email',true );
ob_start();
?>


<div class="xwoo-content">

    <form id="xwoo-dashboard-form" action="" method="" class="xwoo-form">

        <div class="xwoo-row">

            <div class="xwoo-col6">
                <div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">
                    <h4><?php _e("Shipping Address","wp-xwoo"); ?></h4>
                    
                    <!-- // First Name ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "First Name:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                        <input type="hidden" name="action" value="wp_contact_form">
                            <input type="text" name="shipping_first_name" value="<?php echo $f_name; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Last Name ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Last Name:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_last_name" value="<?php echo $l_name; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Company ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Company:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_company" value="<?php echo $company; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Address 1 ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Address 1:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_address_1" value="<?php echo $address1; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Address 2 ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Address 2:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_address_2" value="<?php echo $address2; ?>" disabled>
                        </div>
                    </div>

                    <!-- // City ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "City:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_city" value="<?php echo $city; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Postcode ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Postcode:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_postcode" value="<?php echo $postcode; ?>" disabled>
                        </div>
                    </div>

                    <!-- // Country ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Country:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <?php
                            $countries_obj   = new WC_Countries();
                            $countries   = $countries_obj->__get('countries');
                            array_unshift($countries, __('Select a country','xwoo'));
                            ?>
                            <select name="shipping_country" disabled>
                                <?php foreach ($countries as $key=>$value) {
                                    if( $country==$key ){ ?>
                                        <option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- // State ( Shipping ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "State:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="shipping_state" value="<?php echo $state; ?>" disabled>
                        </div>
                    </div>

                </div>
            </div>

            <div class="xwoo-col6">
                <div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">
                    <!-- // Billing Address -->
                    <h4><?php _e("Billing Address","wp-xwoo"); ?></h4>
                    <!-- // First Name ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "First Name:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_first_name" value="<?php echo $b_f_name; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Last Name ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Last Name:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_last_name" value="<?php echo $b_l_name; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Company ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Company:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_company" value="<?php echo $b_company; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Address 1 ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Address 1:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_address_1" value="<?php echo $b_address1; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Address 2 ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Address 2:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_address_2" value="<?php echo $b_address2; ?>" disabled>
                        </div>
                    </div>


                    <!-- // City ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "City:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_city" value="<?php echo $b_city; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Postcode ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Postcode:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_postcode" value="<?php echo $b_postcode; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Country ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Country:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <?php
                            $countries_obj = new WC_Countries();
                            $countries = $countries_obj->__get('countries');
                            array_unshift($countries, __('Select a country','xwoo')); ?>
                            <select name="billing_country" disabled>
                            <?php foreach ($countries as $key=>$value) {
                                if( $b_country==$key ){ ?>
                                    <option selected="selected" value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php }
                            } ?>
                            </select>
                        </div>
                    </div>


                    <!-- // State ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "State:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_state" value="<?php echo $b_state; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Telephone ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Telephone:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="text" name="billing_phone" value="<?php echo $b_phone; ?>" disabled>
                        </div>
                    </div>


                    <!-- // Email ( Billing ) -->
                    <div class="xwoo-single">
                        <div class="xwoo-name float-left">
                            <p><?php _e( "Email:" , "wp-xwoo" ); ?></p>
                        </div>
                        <div class="xwoo-fields">
                            <input type="email" name="billing_email" value="<?php echo $b_email; ?>" disabled>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <?php echo wp_nonce_field( 'wp_xwoo_dashboard_form_action', 'wp_xwoo_dashboard_nonce_field', true, false ); ?>

		<!-- //Save Button -->
        <div class="xwoo-buttons-group float-right">
            <button id="xwoo-edit" class="xwoo-edit-btn"><?php _e( "Edit" , "wp-xwoo" ); ?></button>
            <button id="xwoo-dashboard-btn-cancel" class="xwoo-cancel-btn xwoo-hidden" type="submit"><?php _e( "Cancel" , "wp-xwoo" ); ?></button>
            <button id="xwoo-contact-save" class="xwoo-save-btn xwoo-hidden" type="submit"><?php _e( "Save" , "wp-xwoo" ); ?></button>
        </div>
        <div class="clear-float"></div>

	</form>

</div>

<?php $html .= ob_get_clean(); ?>