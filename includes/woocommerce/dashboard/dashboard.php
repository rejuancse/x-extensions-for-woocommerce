<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>
<?php

ob_start();
include_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/wpcrowd-reports-chart.php';
$html .= ob_get_clean();
?>

<?php

$html .= '<div class="xwoo-row">';
    $html .= '<div class="xwoo-col6">';
    $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">'; 
        $html .= '<h4>'.__( "My Campaigns" , "wp-xwoo" ).'</h4>';
        include_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/dashboard-campaign.php';
    $html .= '</div>';//xwoo-shadow 

    global $wp;
    $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">'; 
        $html .= '<h4>'.__( "Export Data" , "wp-xwoo" ).'</h4>';
        $html .= '<br/><a href="'.home_url( $wp->request ).'/?download_data=personal" class="xwoo-edit-btn">'.__('Export Campaign Data', 'xwoo').'</a>';
    $html .= '</div>';//xwoo-shadow 

    $html .= '</div>';//xwoo-col6 
    $html .= '<div class="xwoo-col6">';

    ob_start();
    do_action('XWOO_dashboard_place_3');
    $html .= ob_get_clean();

    $html .= '<div class="xwoo-content xwoo-shadow xwoo-padding25 xwoo-clearfix">'; 
        $html .= '<form id="xwoo-dashboard-form" action="" method="" class="xwoo-form">';
                // User Name
                $html .= '<h4>'.__('My Information', 'xwoo').'</h4>';
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "Username:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<input type="hidden" name="action" value="wp_dashboard_form">';
                        $html .= '<input type="text" name="username" value="'.$current_user->user_login.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';
            
                // Email Address
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "Email:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<input type="email" name="email" value="'.$current_user->user_email.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // First Name
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "First Name:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<input type="text" name="firstname" value="'.$current_user->user_firstname.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // Last Name
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "Last Name:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<input type="text" name="lastname" value="'.$current_user->user_lastname.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // Website
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "Website:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<input type="text" name="website" value="'.$current_user->user_url.'" disabled>';
                    $html .= '</div>';
                $html .= '</div>';

                // Bio Info
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name float-left">';
                        $html .= '<p>'.__( "Bio:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $html .= '<textarea name="description" rows="3" disabled>'.$current_user->description.'</textarea>';
                    $html .= '</div>';
                $html .= '</div>';

            $html .= '<h4>'.__('Payment Info', 'xwoo').'</h4>';
            ob_start();
            do_action('XWOO_dashboard_after_dashboard_form');
            $html .= ob_get_clean();

            $html .= wp_nonce_field( 'wp_xwoo_dashboard_form_action', 'wp_xwoo_dashboard_nonce_field', true, false );
            //Save Button
            $html .= '<div class="xwoo-buttons-group float-right">';
                $html .= '<button id="xwoo-edit" class="xwoo-edit-btn">'.__( "Edit" , "wp-xwoo" ).'</button>';
                $html .= '<button id="xwoo-dashboard-btn-cancel" class="xwoo-cancel-btn xwoo-hidden" type="submit">'.__( "Cancel" , "wp-xwoo" ).'</button>';
                $html .= '<button id="xwoo-dashboard-save" class="xwoo-save-btn xwoo-hidden" type="submit">'.__( "Save" , "wp-xwoo" ).'</button>';
            $html .= '</div>';
            $html .= '<div class="clear-float"></div>';
        $html .= '</form>';//#xwoo-dashboard-form
    $html .= '</div>';//xwoo-content
    $html .= '</div>';//xwoo-col6 
$html .= '</div>';//xwoo-row
