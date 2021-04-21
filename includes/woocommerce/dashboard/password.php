<?php
defined( 'ABSPATH' ) || exit;

$html .= '<div class="xwoo-content">';
    $html .= '<form id="xwoo-dashboard-form" action="" method="post" class="xwoo-form">';

        $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
            // Current Password
            $html .= '<div class="xwoo-single">';
                $html .= '<div class="xwoo-name float-left">';
                    $html .= '<p>'.__( "Current Password" , "wp-xwoo" ).'</p>';
                $html .= '</div>';
                $html .= '<div class="xwoo-fields float-right">';
                    $html .= '<input type="hidden" name="action" value="wp_password_form">';
                    $html .= '<input type="password" name="password" value="" autocomplete="off">';
                $html .= '</div>';
            $html .= '</div>';

            // New Password
            $html .= '<div class="xwoo-single">';
                $html .= '<div class="xwoo-name float-left">';
                    $html .= '<p>'.__( "New Password" , "wp-xwoo" ).'</p>';
                $html .= '</div>';
                $html .= '<div class="xwoo-fields float-right">';
                    $html .= '<input type="password" name="new-password" value="" autocomplete="off">';
                $html .= '</div>';
            $html .= '</div>';

            // Retype Password
            $html .= '<div class="xwoo-single">';
                $html .= '<div class="xwoo-name float-left">';
                $html .= '<p>'.__( "Retype Password" , "wp-xwoo" ).'</p>';
                $html .= '</div>';
                $html .= '<div class="xwoo-fields float-right">';
                $html .= '<input type="password" name="retype-password" value="" autocomplete="off">';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';//xwoo-shadow


        $html .= wp_nonce_field( 'wp_xwoo_dashboard_form_action', 'wp_xwoo_dashboard_nonce_field', true, false );

        //Save Button
        $html .= '<div class="xwoo-buttons-group float-right">';
            $html .= '<button id="xwoo-dashboard-btn-cancel" class="xwoo-cancel-btn xwoo-hidden" type="submit">'.__( "Cancel" , "wp-xwoo" ).'</button>';
            $html .= '<button id="xwoo-password-save" class="xwoo-save-btn" type="submit">'.__( "Save" , "wp-xwoo" ).'</button>';
        $html .= '</div>';
        $html .= '<div class="clear-float"></div>';

    $html .= '</form>';
$html .= '</div>';
