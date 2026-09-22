<?php
namespace XEWC\settings;

defined( 'ABSPATH' ) || exit;

class Settings_Generator {

    // Settings Option Generator
    public function generator( $arr ){

        $html = '';
        $html .= '<table class="form-table">';
        $html .= '<tbody>';

        foreach ($arr as $value) {
            if(isset( $value['type'] )){
                switch ( $value['type'] ) {

                    case 'dropdown':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value["label"]).'</label></th>';
                        $html .= '<td>';
                        $multiple = '';
                        if(isset($value['multiple'])){ $multiple = 'multiple'; }
                        $html .= '<select id="'.esc_attr($value['id']).'" name="'.esc_attr($value['id']).'" '.$multiple.'>';
                        $product_status = get_option( $value['id'] );
                        if(!empty($value['option'])){
                            foreach ( $value['option'] as $key => $val ){
                                $html .= '<option value="'.esc_attr($key).'" '.( $key == $product_status ? "selected":"" ).'>'.esc_html($val).'</option>';
                            }
                        }
                        $html .= '</select>';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'multiple':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value["label"]).'</label></th>';
                        $html .= '<td>';
                        $multiple = '';
                        if(isset($value['multiple'])){ $multiple = 'multiple'; }
                        $html .= '<select style="height:190px;" id="'.esc_attr($value['id']).'" name="'.esc_attr($value['id']).'[]" '.$multiple.'>';
                        $product_status = get_option( $value['id'] );
                        if(!empty($value['option'])){
                            foreach ( $value['option'] as $val ){
                                if( !empty($product_status) && is_array($product_status) ){
                                    if( in_array( $val , $product_status ) ){
                                        $html .= '<option value="'.esc_attr($val).'" selected>'.esc_html($val).'</option>';
                                    }else{
                                        $html .= '<option value="'.esc_attr($val).'">'.esc_html($val).'</option>';
                                    }
                                }else{
                                    $html .= '<option value="'.esc_attr($val).'">'.esc_html($val).'</option>';
                                }
                            }
                        }
                        $html .= ' </select>';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'text':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</label></th>';
                        $html .= '<td>';
                        $var = get_option( $value['id'] );
                        $default_value = ( isset($value["value"])) ? $value["value"] : '';
                        $html .= '<input type="text" id="'.esc_attr($value['id']).'" value="'.esc_attr( $var ? $var : $default_value ).'" name="'.esc_attr($value['id']).'">';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'password':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</label></th>';
                        $html .= '<td>';
                        $var = (isset($value['encrypt'])) ? base64_decode( get_option($value['id']) ) : get_option( $value['id'] );
                        $html .= '<input type="password" id="'.esc_attr($value['id']).'" value="'.esc_attr( $var ? $var : $value["value"] ).'" name="'.esc_attr($value['id']).'">';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'textarea':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</label></th>';
                        $html .= '<td>';
                        $var = get_option( $value['id'] );
                        $html .= '<textarea name="'.esc_attr($value['id']).'" id="'.esc_attr($value['id']).'">'.esc_textarea( $var ? $var : $value["value"] ).'</textarea>';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'number':
                        $html .= '<tr>';
                        $html .= '<th scope="row"><label for="'.esc_attr($value["id"]).'">'.esc_html($value["label"]).'</label></th>';
                        $html .= '<td>';
                        $data = '';
                        $var = get_option( $value["id"] );
                        if( isset($value["min"]) != "" ){ $data .= 'min="'.esc_attr($value["min"]).'"'; }
                        if( isset($value["max"]) != "" ){ $data .= ' max="'.esc_attr($value["max"]).'"'; }
                        $html .= '<input type="number" value="'.esc_attr( $var ? $var : $value["value"]).'" '.$data.' name="'.esc_attr($value["id"]).'" />';
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'radio':
                        $html .= '<tr>';
                        $html .= '<th scope="row"><label for="'.esc_attr($value["id"]).'">'.esc_html($value["label"]).'</label></th>';
                        $html .= '<td>';
                        $data = '';
                        $var = get_option( $value["id"] );
                        if( ! $var ){ $var =  ! empty($value["value"]) ? $value["value"] : ''  ; }
                        if(!empty($value['option'])){
                            foreach( $value['option'] as $key => $val ){
                                $cehcked = ($key == $var) ? ' checked="checked" ' : '';
                                $html .= '<label> <input type="radio" name="'.esc_attr($value['id']).'" value="'.esc_attr($key).'" '.$cehcked.' > '.esc_html($val).' </label> <br>';
                            }
                        }

                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }

                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'checkbox':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</label></th>';
                        $html .= '<td>';
                            $var = get_option( $value['id'] );
                            if(isset($value['multiple'])) {
                                $save_value = ( is_array( $var ) ? $var : array() );
                                foreach( $value['option'] as $key => $val ){
                                    $html .= '<label><input type="checkbox" name="'.esc_attr($value['id']).'[]" value="'.esc_attr($key).'" '.( in_array( $key , $save_value )?"checked='checked'":"" ).'/>'.esc_html($val).'</label></br>';
                                }
                            } else {
                                $html .= '<input type="checkbox" name="'.esc_attr($value['id']).'" id="'.esc_attr($value['id']).'" value="true" '.($var=="true"?"checked='checked'":"").'/>';
                            }
                            if(isset($value['desc'])) {
                                $html .= '<label for="'.esc_attr($value['id']).'">'.wp_kses_post($value['desc']).'</label>';
                            }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'seperator':
                        $html .= '<tr>';
                        $html .= '<th colspan="2">';
                        if( isset($value['label']) ){ $html .= '<h2>'.esc_html($value["label"]).'</h2>'; }
                        if( isset($value['desc']) ){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        if( isset($value['top_line']) != '' ){ $html .= '<hr>'; }
                        $html .= '</th>';
                        $html .= '</tr>';
                        break;

                    case 'color':
                        $html .= '<tr>';
                        $html .= '<th><label for="'.esc_attr($value['id']).'">'.esc_html($value['label']).'</label></th>';
                        $html .= '<td>';
                        $var = get_option( $value['id'] );
                        if(!$var){ $var = $value['value']; }
                        $html .= '<input type="text" name="'.esc_attr($value['id']).'" value="'.esc_attr($var).'" id="'.esc_attr($value['id']).'" class="xewc-color-field" >';
                        if(isset($value['desc'])){ $html .= '<p>'.wp_kses_post($value['desc']).'</p>'; }
                        $html .= '</td>';
                        $html .= '</tr>';
                        break;

                    case 'hidden':
                        $html .= '<tr>';
                        $html .= '<th colspan="2">';
                        $html .= '<input type="hidden" value="'.esc_attr($value["value"]).'" name="'.esc_attr($value['id']).'">';
                        $html .= '</th>';
                        $html .= '</tr>';
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';

        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- All dynamic values are escaped at construction time above.
    }
}
