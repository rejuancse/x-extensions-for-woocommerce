<?php
defined( 'ABSPATH' ) || exit;

$post_id = (int) $_GET['postid'];
$saved_campaign_update = get_post_meta($post_id, 'wp_campaign_updates', true);
$saved_campaign_update_a = (array) json_decode($saved_campaign_update, true);

if(isset($_GET["postid"])){
    $post_author = get_post_field( 'post_author', $_GET["postid"] );
    if( $post_author == get_current_user_id() ){
        $var = get_post_meta( $_GET["postid"],"wp_campaign_updates",true );
    }
}

$data = get_user_meta(get_current_user_id());

$html .= '<div id="wp_update_form_wrapper" style="display: none;">';

    $html .= '<div class="xwoo-content">';
    $html .= '<form id="xwoo-dashboard-form" action="" method="" class="xwoo-form">';

    $display = 'block';
    if (count($saved_campaign_update_a) > 0) $display = 'none';
    
    $html .= '<div class="panel woocommerce_options_panel" id="campaign_status">';
    $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
        
        $html .= '<div style="display: '.$display.'" id="campaign_update_field">';
            $html .= '<div class="campaign_update_field_copy">';
                $html .= '<p class="form-field wp_prject_update_date_field[]_field xwoo-single xwoo-first-half">';
                    $html .= '<label for="wp_prject_update_date_field[]">'.__("Date", "xwoo").':</label>';
                    $html .= '<input type="text" placeholder="'.date('d-m-Y').'" value="" id="wp_prject_update_date_field[]" name="wp_prject_update_date_field[]" style="" class="datepicker">';
                $html .= '</p>';
                $html .= '<p class="form-field wp_prject_update_title_field[]_field xwoo-single xwoo-second-half">';
                    $html .= '<label for="wp_prject_update_title_field[]">'.__("Update Title", "xwoo").':</label>';
                    $html .= '<input type="text" placeholder="'.__("Update Title", "xwoo").'" value="" id="wp_prject_update_title_field[]" name="wp_prject_update_title_field[]" style="" class="short">';
                $html .= '</p>';
                $html .= '<p class="form-field wp_prject_update_details_field[]_field xwoo-single">';
                    $html .= '<label for="wp_prject_update_details_field[]">'.__("Update Details", "xwoo").':</label>';
                    $html .= '<textarea cols="20" rows="2" placeholder="'.__("Update Details", "xwoo").'" id="wp_prject_update_details_field[]" name="wp_prject_update_details_field[]" style="" class="short"></textarea>';
                $html .= '</p>';
                $html .= '<input type="button" value="'.__('Remove', 'xwoo').'" class="button tagadd removecampaignupdate" name="remove_udpate" style="display: none;">';
            $html .= '</div>';
        $html .= '</div>';

        $html .= '<div id="campaign_update_addon_field">';
            if ( count($saved_campaign_update_a) > 0 ){
                foreach( $saved_campaign_update_a as $key => $value ){
                    $html .= '<div class="campaign_update_field_copy">';
                        $html .= '<p class="form-field wp_prject_update_date_field[]_field xwoo-single xwoo-first-half">';
                            $html .= '<label for="wp_prject_update_date_field[]">'.__("Date", "xwoo").':</label>';
                            $html .= '<input type="text" placeholder="'.date('d-m-Y').'" value="'.esc_attr($value['date']).'" id="wp_prject_update_date_field[]" name="wp_prject_update_date_field[]" style="" class="datepicker">';
                        $html .= '</p>';
                        $html .= '<p class="form-field wp_prject_update_title_field[]_field xwoo-single xwoo-second-half">';
                            $html .= '<label for="wp_prject_update_title_field[]">'.__("Update Title", "xwoo").':</label>';
                            $html .= '<input type="text" placeholder="'.__("Update Title", "xwoo").'" value="'.esc_attr($value['title']).'" id="wp_prject_update_title_field[]" name="wp_prject_update_title_field[]" style="" class="short">';
                        $html .= '</p>';
                        $html .= '<p class="form-field wp_prject_update_details_field[]_field xwoo-single">';
                            $html .= '<label for="wp_prject_update_details_field[]">'.__("Update Details", "xwoo").':</label>';
                            $html .= '<textarea cols="20" rows="2" placeholder="'.__("Update Details", "xwoo").'" id="wp_prject_update_details_field[]" name="wp_prject_update_details_field[]" style="" class="short" >'.esc_textarea($value['details']).'</textarea>';
                        $html .= '</p>';
                        $html .= '<input type="button" value="'.__('Remove', 'xwoo').'" class="button tagadd removecampaignupdate" name="remove_udpate" style="display: none;">';
                    $html .= '</div>';
                }
            }
        $html .= '</div>';

        $html .= '<input type="button" value="+ '.__('Add Update', 'xwoo').'" id="addcampaignupdate" class="button tagadd" name="save_update">';
        $html .= '<div style="clear: both;"></div>';
    $html .= '</div>';

    $html .= '<input type="hidden"  value="wp_update_status_save" name="action" />';
    $html .= '<input type="hidden"  value="'. intval(esc_attr($post_id)) .'" name="postid" />';
    $html .= '</div>';//xwoo-padding25
    //Save Button
    $html .= '<div class="xwoo-buttons-group float-right">';
    $html .= '<button id="xwoo-update-save" class="xwoo-save-btn" type="submit">'.__( "Save" , "xwoo" ).'</button>';
    $html .= '</div>';
    $html .= '<div class="clear-float"></div>';

    $html .= wp_nonce_field( 'xwoo_form_action', 'xwoo_form_action_field', true, false );

    $html .= '</form>';
    $html .= '</div>';
$html .='</div>'; //update_form_wrapper


$html .= '<div id="wp_update_display_wrapper">';
    if (count($saved_campaign_update_a) > 0){
        $html .= '<div class="xwoo-form">';
            $html .='<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
                $html .= '<table class="stripe-table">';
                    $html .= '<thead>';
                        $html .= '<tr>';
                            $html .= '<th>'.__('Date', 'xwoo').'</th>';
                            $html .= '<th>'.__('Title', 'xwoo').'</th>';
                        $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                        foreach($saved_campaign_update_a as $key => $value){
                            $html .= '<tr>';
                                $html .= '<td>'.$value['date'].'</td>';
                                $html .= '<td>'.$value['title'].'</td>';
                            $html .= '</tr>';
                        }
                    $html .= '</tbody>';
                $html .= '</table>';
            $html .= '</div>';
            $html .= '<input type="button" value="'.__('Add Update', 'xwoo').'" id="wp_active_edit_form" class="button tagadd" name="save_update">';
        $html .= '</div>';

    } else {
        $html .= '<div class="xwoo-form">';
            $html .= '<input type="button" value="'.__('Add Update', 'xwoo').'" id="wp_active_edit_form" class="button tagadd" name="save_update">';
        $html .= '</div>';
    }
$html .='</div>'; //wp_update_display_wrapper
