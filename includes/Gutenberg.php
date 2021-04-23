<?php
namespace XWOO;

defined( 'ABSPATH' ) || exit;

class Gutenberg{

    public function __construct(){
        add_action( 'init', array( $this, 'blocks_init' ));
        add_action( 'enqueue_block_editor_assets', array( $this, 'post_editor_assets' ) );
        add_filter( 'block_categories', array( $this, 'block_categorie_callback'), 1 , 2 );
    }
    
    /** 
     * Blocks Init
     */
    public function blocks_init(){
        require_once XWOO_DIR_PATH . 'includes/blocks/Search.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Donate.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Project_Listing.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Popular_Campaigns.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Campaign_Box.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Registration.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Dashboard.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Single_Campaign.php';
        require_once XWOO_DIR_PATH . 'includes/blocks/Submit_Form.php';

        new \XWOO\blocks\Search();
        new \XWOO\blocks\Donate();
        new \XWOO\blocks\ProjectListing();
        new \XWOO\blocks\PopularCampaigns();
        new \XWOO\blocks\CampaignBox();
        new \XWOO\blocks\Registration();
        new \XWOO\blocks\Dashboard();
        new \XWOO\blocks\Single_Campaign();
        new \XWOO\blocks\Submit_Form();
    }
    
    /**
     * Only for the Gutenberg Editor(Backend Only)
     */
    public function post_editor_assets(){
        
        // Scripts
        wp_enqueue_script(
            'XWOO-block-script-js',
            XWOO_DIR_URL . 'assets/js/blocks.min.js', 
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
            false,
            true
        );

        // Localize Scripts
        wp_localize_script( 'XWOO-block-script-js', 'plugin_option', array(
            'plugin' => XWOO_DIR_URL,
            'name' => 'xwoo'
        ) );
        
    }

    /**
     * Block Category Add
     */
    public function block_categorie_callback( $categories, $post ){
        return array_merge(
            $categories,
            array(
                array(
                    'slug' 	=> 'xwoo',
                    'title' => __( 'WP Xwoo', 'xwoo' ),
                )
            )
        );
    }
}

