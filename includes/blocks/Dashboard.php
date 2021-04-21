<?php
namespace XWOO\blocks;

defined( 'ABSPATH' ) || exit;

class Dashboard{
    
    public function __construct(){
        $this->register_dashboard();
    }

    public function register_dashboard(){
        register_block_type(
            'wp-xwoo/dashboard',
            array(
                'attributes' => array(
                    'bgColor'    => array(
                        'type'          => 'string',
                        'default'       => '#1adc68',
                    ),
                    'titleColor'    => array(
                        'type'          => 'string',
                        'default'       => '#ffffff',
                    ),
                ),
                'render_callback' => array( $this, 'dashboard_block_callback' ),
            )
        );
    }

    public function dashboard_block_callback( $att ){
        $majorColor     = isset( $att['bgColor']) ? $att['bgColor'] : '';
        $textcolor      = isset( $att['titleColor']) ? $att['titleColor'] : '';
    
        $html = $get_id = '';

        if( isset($_GET['page_type']) ){ $get_id = $_GET['page_type']; }
            if ( is_user_logged_in() ) {
                $pagelink = get_permalink( get_the_ID() );
                $dashboard_menus = apply_filters('XWOO_frontend_dashboard_menus', array(
                    'dashboard' => array(
                        'tab'             => 'dashboard',
                        'tab_name'        => __('Dashboard','xwoo'),
                        'load_form_file'  => XWOO_DIR_PATH.'includes/woocommerce/dashboard/dashboard.php'
                    ),
                    'profile' => array(
                        'tab'             => 'account',
                        'tab_name'        => __('Profile','xwoo'),
                        'load_form_file'  => XWOO_DIR_PATH.'includes/woocommerce/dashboard/profile.php'
                    ),
                    'contact' => array(
                        'tab'             => 'account',
                        'tab_name'        => __('Contact','xwoo'),
                        'load_form_file'  => XWOO_DIR_PATH.'includes/woocommerce/dashboard/contact.php'
                    ),
                    'campaign' => array(
                        'tab'             => 'campaign',
                        'tab_name'        => __('My Campaigns','xwoo'),
                        'load_form_file'  => XWOO_DIR_PATH.'includes/woocommerce/dashboard/campaign.php'
                    ),
                    'backed_campaigns' => array(
                        'tab'             => 'campaign',
                        'tab_name'        => __('My Invested Campaigns','xwoo'),
                        'load_form_file'  => XWOO_DIR_PATH.'includes/woocommerce/dashboard/investment.php'
                    ),
                    'pledges_received' => array(
                        'tab'            => 'campaign',
                        'tab_name'       => __('Pledges Received','xwoo'),
                        'load_form_file' => XWOO_DIR_PATH.'includes/woocommerce/dashboard/order.php'
                    ),
                    'bookmark' => array(
                        'tab'            => 'campaign',
                        'tab_name'       => __('Bookmarks','xwoo'),
                        'load_form_file' => XWOO_DIR_PATH.'includes/woocommerce/dashboard/bookmark.php'
                    ),
                    'password' => array(
                        'tab'            => 'account',
                        'tab_name'       => __('Password','xwoo'),
                        'load_form_file' => XWOO_DIR_PATH.'includes/woocommerce/dashboard/password.php'
                    ),
                    'rewards' => array(
                        'tab'            => 'account',
                        'tab_name'       => __('Rewards','xwoo'),
                        'load_form_file' => XWOO_DIR_PATH.'includes/woocommerce/dashboard/rewards.php'
                    ),
                ));
                

                /**
                 * Print menu with active link marking...
                 */
                
                $html .= '<div class="XWOO-dashboard">';
                $html .= '<div class="xwoo-wrapper">';
                $html .= '<div class="xwoo-head xwoo-shadow">';
                    $html .= '<div class="xwoo-links clearfix">';

                        $dashboard = $account = $campaign = $extra = '';
                        foreach ($dashboard_menus as $menu_name => $menu_value){

                            if ( empty($get_id) && $menu_name == 'dashboard'){ $active = 'active';
                            } else { $active = ($get_id == $menu_name) ? 'active' : ''; }

                            $pagelink = add_query_arg( 'page_type', $menu_name , $pagelink );

                            if( $menu_value['tab'] == 'dashboard' ){
                                $dashboard .= '<div class="xwoo-links-list '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                            }elseif( $menu_value['tab'] == 'account' ){
                                $account .= '<div class="xwoo-links-lists '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                            }elseif( $menu_value['tab'] == 'campaign' ){
                                $campaign .= '<div class="xwoo-links-lists '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                            }else{
                                $extra .= '<div class="xwoo-links-list '.$active.'"><a href="'.$pagelink.'">'.$menu_value['tab_name'].'</a></div>';
                            }
                        }
                        
                        $html .= $dashboard;
                        $html .= '<div class="xwoo-links-list wp-crowd-parent"><a href="#">'.__("My Account","wp-xwoo").'<span class="wpcrowd-arrow-down"></span></a>';
                            $html .= '<div class="wp-crowd-submenu xwoo-shadow">';
                                $html .= $account;
                                $html .= '<div class="xwoo-links-lists"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout','xwoo').'</a></div>';
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="xwoo-links-list wp-crowd-parent"><a href="#">'.__("Campaigns","wp-xwoo").'<span class="wpcrowd-arrow-down"></span></a>';
                            $html .= '<div class="wp-crowd-submenu xwoo-shadow">';
                                $html .= $campaign;
                            $html .= '</div>';
                        $html .= '</div>';
                        $html .= $extra;
                        $html .= '<div class="wp-crowd-new-campaign"><a class="wp-crowd-btn wp-crowd-btn-primary" href="'.get_permalink(get_option('wp_form_page_id')).'">'.__("Add New Campaign","wp-xwoo").'</a></div>';

                    $html .= '</div>';
                $html .= '</div>';


                $var = '';
                if( isset($_GET['page_type']) ){
                    $var = $_GET['page_type'];
                }
        
                ob_start();
                if( $var == 'update' ){
                    require_once XWOO_DIR_PATH.'includes/woocommerce/dashboard/update.php';
                }else{
                    if ( ! empty($dashboard_menus[$get_id]['load_form_file']) ) {
                        if (file_exists($dashboard_menus[$get_id]['load_form_file'])) {
                            include $dashboard_menus[$get_id]['load_form_file'];
                        }
                    }else{
                        include $dashboard_menus['dashboard']['load_form_file'];
                    }
                }
                $html .= ob_get_clean();
                
            $html .= '</div>'; //xwoo-wrapper
            $html .= '</div>';

            $html .= '<style>';
                $html .= '.XWOO-dashboard .wp-crowd-btn-primary, .XWOO-dashboard .xwoo-dashboard-summary ul li.active,
                .XWOO-dashboard .xwoo-edit-btn, .XWOO-dashboard .xwoo-pagination ul li span.current, .xwoo-pagination ul li a:hover, .xwoo-pagination ul li span.current {
                    background-color: '. $majorColor .';
                }';
                $html .= '.xwoo-links div.active a, .xwoo-links div a:hover, .XWOO-dashboard .xwoo-name > p, .XWOO-dashboard .wpcrowd-listing-content .wpcrowd-admin-title h3 a{
                    color: '. $majorColor .';
                }';

                $html .= '.xwoo-links div a.wp-crowd-btn.wp-crowd-btn-primary, .xwoo-links div a.wp-crowd-btn.wp-crowd-btn-primary:hover, .XWOO-dashboard .wp-crowd-btn-primary, .XWOO-dashboard .xwoo-pagination ul li span.current, .xwoo-pagination ul li a:hover, .xwoo-pagination ul li span.current, .XWOO-dashboard .xwoo-edit-btn, .xwoo-dashboard-summary ul li.active .xwoo-value, .xwoo-dashboard-summary ul li.active .xwoo-value-info {
                    color: '. $textcolor .'
                }';

                $html .= '.xwoo-dashboard-summary ul li.active:after {
                    border-color: '.$majorColor.' rgba(0, 128, 0, 0) rgba(255, 255, 0, 0) rgba(0, 0, 0, 0);
                }';
                $html .= '.xwoo-pagination ul li a:hover, .xwoo-pagination ul li span.current {
                    border: 2px solid '.$majorColor.';
                }';
            $html .= '</style>';

        } else {
            $html .= '<div class="woocommerce">';
            $html .= '<div class="woocommerce-info">' . __("Please log in first?", "wp-xwoo") . ' <a class="xwooShowLogin" href="#">' . __("Click here to login", "wp-xwoo") . '</a></div>';
            $html .= XWOO_function()->login_form();
            $html .= '</div>';
        }

        return $html;
        
    }
}
