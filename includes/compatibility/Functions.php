<?php
defined( 'ABSPATH' ) || exit;

function wpneo_xwoo_get_author_name(){
    return XWOO_function()->get_author_name();
}

function author_name_by_login($author_login){
    return XWOO_function()->author_name_by_login($author_login);
}

function get_XWOO_author_campaigns_url($author_id = 0, $author_nicename = '') {
    XWOO_function()->campaign_url( $author_id, $author_nicename );
}

function wpneo_xwoo_get_campaigns_location(){
    return XWOO_function()->campaign_location();
}

function wpneo_xwoo_get_total_fund_raised_by_campaign($campaign_id = 0){
    return XWOO_function()->fund_raised($campaign_id);
}

function wpneo_xwoo_get_total_goal_by_campaign($campaign_id){
    return XWOO_function()->total_goal($campaign_id);
}

function wpneo_xwoo_price($price, $args = array()){
    return XWOO_function()->price( $price, $args = array() );
}

function wpneo_loved_campaign_count($user_id = 0){
    return XWOO_function()->loved_count($user_id);
}
function is_campaign_loved_html($user_id = 0){
    return XWOO_function()->campaign_loved($user_id);
}

function wpneo_xwoo_wc_login_form(){
    return XWOO_function()->login_form();
}

function wpneo_xwoo_author_all_campaigns($author_id = 0){
    return XWOO_function()->author_campaigns( $author_id );
}

function wpneo_xwoo_add_http($url){
    return XWOO_function()->url($url);
}

function wpneo_xwoo_embeded_video($url){
    return XWOO_function()->get_embeded_video( $url );
}

function wpneo_xwoo_campaign_listing_by_author_url($user_login){
    return XWOO_function()->get_author_url( $user_login );
}

function wpneo_xwoo_load_template($template = '404'){
    return XWOO_function()->template($template);
}

function wpneo_xwoo_pagination($page_numb, $max_page) {
    return XWOO_function()->get_pagination($page_numb, $max_page);
}

function wpneo_wc_version_check($version = '3.0') {
    return XWOO_function()->wc_version($version = '3.0');
}

function wpneo_xwoo_campaign_single_love_this() {
    return XWOO_function()->campaign_single_love_this();
}

function WPNEOCF() {
    return XWOO_function();
}