<?php

class Xwoo_Extensions {
    /**
     * @var null
     *
     * Instance of this class
     */
    protected static $_instance = null;

    /**
     * @return null|XWOO
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init',                                 array( $this, 'embed_data') );
        // add_action( 'wp_enqueue_scripts',                   array( $this, 'extensions_enqueue_frontend_script') ); //Add 
        add_action( 'init',                                 array( $this, 'extensions_save_settings') ); // Extensions Settings
        add_action( 'wp_ajax_xwoo_embed_action',            array( $this, 'embed_product_action') );
        add_action( 'wp_ajax_nopriv_xwoo_embed_action',     array( $this, 'embed_product_action') );
        // add_action( 'xwoo_after_single_product_summary',   array( $this, 'single_product_extensions') );
    }

    // public function extensions_enqueue_frontend_script() {
    //     wp_enqueue_script('xwoo-social-share-front', XWOO_DIR_URL .'extensions/social-share/assets/js/SocialShare.min.js', array('jquery'), XWOO_VERSION, true);
    // }

    /**
     * All settings will be save in this method
     */
    public function extensions_save_settings(){
        if (isset($_POST['xwoo_admin_settings_submit_btn']) && isset($_POST['xwoo_varify_share']) && wp_verify_nonce( $_POST['xwoo_settings_page_nonce_field'], 'xwoo_settings_page_action' ) ){
            // Checkbox
            $embed_share = sanitize_text_field(xwoo_function()->post('xwoo_embed_share'));
            xwoo_function()->update_checkbox('xwoo_embed_share', $embed_share);

            $extensions = xwoo_function()->post('xwoo_extensions');
            xwoo_function()->update_checkbox('xwoo_extensions', $extensions);
        }
    }

    // Data Post Embed Code
    public function embed_data(){
        $url = $_SERVER["REQUEST_URI"];
        $embed = strpos($url, 'xwooembed');
        if ($embed!==false){
            $end_part = explode('/', rtrim($url, '/'));
            if( $end_part ){
                global $post;
                $post_id = end( $end_part );
                $args = array( 'p' => $post_id, 'post_type' => 'product','post_status' => 'publish' );
                $myposts = get_posts( $args );
                foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <style type="text/css">
                                .xwoo-listings{
                                    width: 300px;
                                    border: 1px solid #e9e9e9;
                                    border-radius: 3px;
                                }
                                .xwoo-listing-img {
                                    position: relative;
                                }
                                .xwoo-listing-img img {
                                    width: 100%;
                                    height: auto;
                                }
                                .xwoo-listing-content{
                                    padding: 15px;
                                }
                                .xwoo-listing-content h4{
                                    margin: 0;
                                }
                                .xwoo-listing-content h4 a {
                                    color: #000;
                                    font-size: 24px;
                                    font-weight: normal;
                                    line-height: 28px;
                                    box-shadow: none;
                                    text-decoration: none;
                                    letter-spacing: normal;
                                    text-transform: capitalize;
                                }
                                .xwoo-author {
                                    color: #737373;
                                    font-size: 16px;
                                    line-height: 18px;
                                    margin: 0;
                                }
                                .xwoo-author a {
                                    color: #737373;
                                    text-decoration: none;
                                    box-shadow: none;
                                }
    
                                #neo-progressbar {
                                    overflow: hidden;
                                    background-color: #f2f2f2;
                                    border-radius: 7px;
                                    padding: 0px;
                                }
                                #neo-progressbar > div {
                                    background-color: #4C76FF;
                                    height: 10px;
                                    border-radius: 10px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="xwoo-listings">
                                <div class="xwoo-listing-img">
                                    <a target="_top" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php echo woocommerce_get_product_thumbnail(); ?></a>
                                </div>
                                <div class="xwoo-listing-content">
    
                                    <h4><a target="_top" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <p class="xwoo-author"><?php _e('by','xwoo'); ?> 
                                        <a target="_top" href="<?php echo xwoo_function()->get_author_url( get_the_author_meta( 'user_login' ) ); ?>"><?php echo xwoo_function()->get_author_name(); ?></a>
                                    </p>
    
                                    <?php
                                        $location = xwoo_function()->product_location(); 
                                        if ($location){ ?>
                                        <div class="xwoo-location-wrapper">
                                            <span><?php echo $location; ?></span>
                                        </div>
                                    <?php } ?>
    
                                    <?php $desc = xwoo_function()->limit_word_text(strip_tags(get_the_content()), 130); ?>
                                    <?php if($desc){ ?>
                                        <p class="xwoo-short-description"><?php echo $desc; ?></p>
                                    <?php } ?>
    
                                    <?php $raised_percent = xwoo_function()->get_fund_raised_percent_format(); ?>
                                    <div class="xwoo-raised-percent">
                                        <div class="xwoo-meta-name"><?php _e('Raised Percent', 'xwoo'); ?> :</div>
                                        <div class="xwoo-meta-desc" ><?php echo $raised_percent; ?></div>
                                    </div>
    
                                    <div class="xwoo-raised-bar sjkdhfjdshf">
                                        <div id="neo-progressbar">
                                            <?php $css_width = xwoo_function()->get_raised_percent(); if( $css_width >= 100 ){ $css_width = 100; } ?>
                                            <div style="width: <?php echo $css_width; ?>%"></div>
                                        </div>
                                    </div>
    
                                    <div class="xwoo-funding-data">
                                        
                                        <?php $funding_goal = get_post_meta( get_the_ID() , '_nf_funding_goal', true); ?>
                                        <div class="xwoo-funding-goal">
                                            <div class="xwoo-meta-desc"><?php echo wc_price( $funding_goal ); ?></div>
                                            <div class="xwoo-meta-name"><?php _e('Funding Goal', 'xwoo'); ?></div>
                                        </div>
    
                                        <?php
                                        $end_method = get_post_meta(get_the_ID(), 'xwoo_product_end_method', true);
                                        $days_remaining = apply_filters('date_expired_msg', __('0', 'xwoo'));
                                        if (xwoo_function()->get_date_remaining()){
                                            $days_remaining = apply_filters('date_remaining_msg', __(xwoo_function()->get_date_remaining(), 'xwoo'));
                                        }
                                        if ($end_method != 'never_end'){ ?>
                                            <div class="xwoo-time-remaining">
                                                <div class="xwoo-meta-desc"><?php echo $days_remaining; ?></div>
                                                <div class="xwoo-meta-name float-left"><?php _e('Days to go', 'xwoo'); ?></div>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php
                                        $raised = 0;
                                        $total_raised = xwoo_function()->get_total_fund();
                                        if ($total_raised){ $raised = $total_raised; }
                                        ?>
                                        <div class="xwoo-fund-raised">
                                            <div class="xwoo-meta-desc"><?php echo wc_price($raised); ?></div>
                                            <div class="xwoo-meta-name"><?php _e('Fund Raised', 'xwoo'); ?></div>
                                        </div>
    
                                    </div>                     
                                </div>
                            </div>
                        </body>
                    </html>
                <?php endforeach; 
                wp_reset_postdata();
            }
            exit();
        }
    }

    // Odrer Data View 
    public function embed_product_action() {
        $html = '';
        $title = __("Embed Code","xwoo");
        $postid = sanitize_text_field($_POST['postid']);
        if( $postid ){
            $html .= '<div>';
            $html .= '<textarea><iframe width="310" height="656" src="'.esc_url( home_url( "/" ) ).'xwooembed/'.$postid.'" frameborder="0" scrolling="no"></iframe></textarea>';
            $html .= '<i>'.__("Copy this code and paste inside your content.", "xwoo").'</i>';
            $html .= '</div>';
        }
        die(json_encode(array('success'=> 1, 'message' => $html, 'title' => $title )));
    }

    // public function single_product_extensions() {
    //     xwoo_function()->template('include/social-share');
    // }
}
Xwoo_Extensions::instance();
