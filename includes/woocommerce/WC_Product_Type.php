<?php
defined( 'ABSPATH' ) || exit;

if (! class_exists('WC_Product_Xwoo')) {
    class WC_Product_Xwoo extends WC_Product{
        public function __construct($product){
            $this->product_type = 'xwoo';
            parent::__construct($product);
        }
    }
}