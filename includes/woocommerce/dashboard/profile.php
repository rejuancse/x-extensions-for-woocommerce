<?php
defined( 'ABSPATH' ) || exit;

$current_user_id = get_current_user_id();

/**
 * If user can manage options
 */
$logged_user_info = true;
if (user_can($current_user_id, 'manage_options')){
	if (isset($_GET['show_user_id'])){
		$current_user_id = (int) sanitize_text_field($_GET['show_user_id']);
		$logged_user_info = false;
	}
}

$data = get_user_meta($current_user_id);
$user = get_user_by('ID', $current_user_id);

$html .= '<div class="xwoo-content">';
    $html .= '<form id="xwoo-dashboard-form" action="" method="" class="xwoo-form">';
        $html .= '<div class="xwoo-row">';

            $html .= '<div class="xwoo-col6">';
                $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
                    $html .= '<h4>'.__("Profile Picture","wp-xwoo").'</h4>';
                    $html .= '<div class="xwoo-fields">';
                    $html .= '<input type="hidden" name="action" value="wp_profile_form">';
                        
                        $img_src = get_avatar_url( $current_user_id );
                        $image_id = get_user_meta( $current_user_id, 'profile_image_id', true );
                        if ($image_id && $image_id > 0) {
                            $img_src = wp_get_attachment_image_src($image_id, 'full')[0];
                        }
                        $html .= '<img class="profile-form-img" src="'.$img_src.'" alt="'.__( "Profile Image:" , "wp-xwoo" ).'">';

                        $html .= '<span id="xwoo-image-show"></span>';
                        $html .= '<input type="hidden" name="profile_image_id" class="xwoo-form-image-id" value="'.$image_id.'">';
                        $html .= '<input type="hidden" name="xwoo-form-image-url" class="xwoo-form-image-url" value="">';
                        $html .= '<button name="xwoo-upload" id="cc-image-upload-file-button" class="xwoo-image-upload float-right" style="display: none;">'.__( "Upload" , "wp-xwoo" ).'</button>';
                    $html .= '</div>';
                $html .= '</div>';//xwoo-shadow
            $html .= '</div>';//xwoo-col6

            $html .= '<div class="xwoo-col6">';
                
                // Basic info
                $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
                    $html .= '<h4>'.__("Basic Info","wp-xwoo").'</h4>';
				    $html .= '<div class="xwoo-name">';
					$html .= '<p>'.__( "Name:" , "wp-xwoo" ).'</p>';
					$html .= '</div>';
					$html .= '<div class="xwoo-fields float-right">';
					$html .= "<p>".XWOO_function()->get_author_name()."</p>";
                    $html .= '</div>';

					$html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name">';
                    $html .= '<p>'.__( "First Name:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                    $html .= '<input type="text" name="first_name" value="'.$user->first_name.'" disabled>';
					$html .= '</div>';
                    
					$html .= '<div class="xwoo-name">';
					$html .= '<p>'.__( "Last Name:" , "wp-xwoo" ).'</p>';
					$html .= '</div>';
					$html .= '<div class="xwoo-fields float-right">';
					$html .= '<input type="text" name="last_name" value="'.$user->last_name.'" disabled>';
					$html .= '</div>';
				$html .= '</div>';

                // About Us
                $html .= '<div class="xwoo-name">';
                    $html .= '<p>'.__( "About Us:" , "wp-xwoo" ).'</p>';
                $html .= '</div>';
                $html .= '<div class="xwoo-fields float-right">';
                    $value = ''; if(isset($data['profile_about'][0])){ $value = esc_textarea($data['profile_about'][0]); }
                    $html .= '<textarea name="profile_about" rows="3" disabled>'.$value.'</textarea>';
                $html .= '</div>';

                // Profile Information
                $html .= '<div class="xwoo-single">';
                    $html .= '<div class="xwoo-name">';
                        $html .= '<p>'.__( "User Bio:" , "wp-xwoo" ).'</p>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $value = ''; if(isset($data['profile_portfolio'][0])){ $value = esc_textarea($data['profile_portfolio'][0]); }
                        $html .= '<textarea name="profile_portfolio" rows="3" disabled>'.$value.'</textarea>';
                    $html .= '</div>';
                $html .= '</div>';

                $html .= '</div>';//xwoo-shadow
            $html .= '</div>';//xwoo-col6

            // Mobile Number
            $html .= '<div class="xwoo-col6">';
                $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
                    $html .= '<h4>'.__("Contact Info","wp-xwoo").'</h4>';
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Mobile Number:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="xwoo-fields float-right">';
                        $value = ''; if(isset($data['profile_mobile1'][0])){ $value = esc_attr($data['profile_mobile1'][0]); }
                        $html .= '<input type="text" name="profile_mobile1" value="'.$value.'" disabled>';
                    $html .= '</div>';
                    // Email
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Email:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_email1'][0])){ $value = esc_attr($data['profile_email1'][0]); }
                            $html .= '<input type="text" name="profile_email1" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                    // Fax
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Fax:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_fax'][0])){ $value = esc_attr($data['profile_fax'][0]); }
                            $html .= '<input type="text" name="profile_fax" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                    // Website
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Website:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_website'][0])){ $value = esc_url($data['profile_website'][0]); }
                            $html .= '<input type="text" name="profile_website" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Address
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Address:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_address'][0])){ $value = esc_textarea($data['profile_address'][0]); }
                            $html .= '<input type="text" name="profile_address" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';//xwoo-shadow
            $html .= '</div>';//xwoo-col6

            $html .= '<div class="xwoo-col6">';
                $html .= '<div class="xwoo-shadow xwoo-padding25 xwoo-clearfix">';
                    $html .= '<h4>'.__("Social Profile","wp-xwoo").'</h4>';
                    //Facebook
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Facebook:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields">';
                            $value = ''; if(isset($data['profile_facebook'][0])){ $value = esc_textarea($data['profile_facebook'][0]); }
                            $html .= '<input type="text" name="profile_facebook" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Twitter
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name float-left">';
                            $html .= '<p>'.__( "Twitter:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields">';
                            $value = ''; if(isset($data['profile_twitter'][0])){ $value = esc_textarea($data['profile_twitter'][0]); }
                            $html .= '<input type="text" name="profile_twitter" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // VK
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "VK:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_vk'][0])){ $value = esc_textarea($data['profile_vk'][0]); }
                            $html .= '<input type="text" name="profile_vk" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Linkedin
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Linkedin:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_linkedin'][0])){ $value = esc_textarea($data['profile_linkedin'][0]); }
                            $html .= '<input type="text" name="profile_linkedin" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';

                    // Pinterest
                    $html .= '<div class="xwoo-single">';
                        $html .= '<div class="xwoo-name">';
                            $html .= '<p>'.__( "Pinterest:" , "wp-xwoo" ).'</p>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-fields float-right">';
                            $value = ''; if(isset($data['profile_pinterest'][0])){ $value = esc_textarea($data['profile_pinterest'][0]); }
                            $html .= '<input type="text" name="profile_pinterest" value="'.$value.'" disabled>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';//xwoo-shadow
            $html .= '</div>';//xwoo-col6
        $html .= '</div>';//xwoo-row

        ob_start();
        do_action('XWOO_dashboard_after_profile_form');
        $html .= ob_get_clean();

        $html .= wp_nonce_field( 'wp_xwoo_dashboard_form_action', 'wp_xwoo_dashboard_nonce_field', true, false );

        //Save Button
		if ($logged_user_info) {
			$html .= '<div class="xwoo-buttons-group float-right">';
			$html .= '<button id="xwoo-edit" class="xwoo-edit-btn">' . __( "Edit", "wp-xwoo" ) . '</button>';
			$html .= '<button id="xwoo-dashboard-btn-cancel" class="xwoo-cancel-btn xwoo-hidden" type="submit">' . __( "Cancel", "wp-xwoo" ) . '</button>';
			$html .= '<button id="xwoo-profile-save" class="xwoo-save-btn xwoo-hidden" type="submit">' . __( "Save", "wp-xwoo" ) . '</button>';
			$html .= '</div>';
			$html .= '<div class="clear-float"></div>';
		}

    $html .= '</form>';
$html .= '</div>';