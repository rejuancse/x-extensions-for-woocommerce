<?php
namespace XWOO\blocks;

defined( 'ABSPATH' ) || exit;

class Submit_Form{
    
    public function __construct(){
        $this->register_submit_form();
    }

    public function register_submit_form(){
        register_block_type(
            'wp-xwoo/submitform',
            array(
                'attributes' => array(
                    'textColor' => array(
                        'type'          => 'string',
                        'default'       => '#ffffff',
                    ),
                    'bgColor'   => array(
                        'type'          => 'string',
                        'default'       => '#1adc68',
                    ),
                    'cancelBtnColor'   => array(
                        'type'          => 'string',
                        'default'       => '#cf0000',
                    ),
                ),
                'render_callback' => array( $this, 'submit_form_block_callback' ),
            )
        );
    }

    public function submit_form_block_callback( $att ){
        $textColor          = isset( $att['textColor']) ? $att['textColor'] : '';
        $bgColor            = isset( $att['bgColor']) ? $att['bgColor'] : '';
        $cancelBtnColor     = isset( $att['cancelBtnColor']) ? $att['cancelBtnColor'] : '';
    
        $html = '';
        $html .= XWOO_get_submit_form_campaign();

        $html .= '<style>';
            $html .= 'input[type="button"].xwoo-image-upload, .xwoo-image-upload.float-right, .xwoo-image-upload-btn, #addreward, #xwoofrontenddata .xwoo-form-action input[type="submit"].xwoo-submit-campaign, .xwoo-single .xwoo-image-upload-btn {
                background-color: '. $bgColor .';
            }';
            $html .= 'input[type="button"].xwoo-image-upload, .xwoo-image-upload.float-right, .xwoo-image-upload-btn, #addreward, #xwoofrontenddata .xwoo-form-action input[type="submit"].xwoo-submit-campaign, a.xwoo-cancel-campaign, .editor-styles-wrapper a.xwoo-cancel-campaign {
                color: '.$textColor.'
            }';
            $html .= 'a.xwoo-cancel-campaign {
                background-color: '.$cancelBtnColor.'
            }';
        $html .= '</style>';

        return $html;
    }
}
