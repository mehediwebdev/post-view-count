<?php
/*
 * Plugin Name:       Post View Count
 * Plugin URI:        
 * Description:       Post View Count is an essential plugin for website users; it displays views count of the posts on the post details page and the admin column.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Author:            Mehedi Hasan
 * Author URI:        
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        
 * Text Domain:       post-view-count
 * Domain Path:       /languages
 */


 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if (!class_exists('WPH_Post_View_Count')) {
    class WPH_Post_View_Count
    {
        public function __construct(){
            add_action( 'init', array( $this, 'init' ) );          
        }

        public function init(){
          $this->define_constants();
          $this->load_textdomain();
          
         add_action('wp_head', array( $this, 'track_post_views_count' ));
         //add_action('wp_footer', array( $this, 'display_post_views_count' ));
         add_filter('the_content', array($this, 'display_post_views_count'));

         //add_filter('the_content', [$this, 'display_post_views_count']);

         require_once( WPH_POST_VIEW_COUNT_PATH . 'includes/class.wph_post_vc_admin_cl.php' );
		 $WPH_Post_Vc_Admin_Cl = new WPH_Post_Vc_Admin_Cl();

         require_once( WPH_POST_VIEW_COUNT_PATH . 'templates/shortcode/class.wph_post_vc_shortcode.php' );
		 $WPH_Post_Vc_Shortcode = new WPH_Post_Vc_Shortcode();

         add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
         add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
        }

        public function define_constants() {
            define('WPH_POST_VIEW_COUNT_PATH', plugin_dir_path(__FILE__));
            define('WPH_POST_VIEW_COUNT_URL', plugin_dir_url(__FILE__));
            define('WPH_POST_VIEW_COUNT_VERSION', '1.0.0');
        }

       
    // Function to track post views
    public function track_post_views_count() {
        if (is_single()) {
            global $post;
            $post_id = $post->ID;
            $views = get_post_meta($post_id, 'post_views', true);
            $views = $views ? $views : 0;
            $views++;
            update_post_meta($post_id, 'post_views', $views);
        }
    }

      // Display post views on single post page
      public function display_post_views_count($content) {
        if (is_single()) {
            $views = get_post_meta(get_the_ID(), 'post_views', true);
            $views_html = '<p>Total Views: ' . esc_html($views ? $views : 0) . '</p>';
            $content .= $views_html;
        }
        return $content;
    }
    // Admin assets enqueue callback function
    public function load_admin_assets($screen){
        $version = WPH_POST_VIEW_COUNT_VERSION;
        $asset_directory = plugins_url('assets/', __FILE__);
        if($screen == 'edit.php'){
         wp_enqueue_style( 'wph-pvc-admin-style', $asset_directory . '/admin/css/admin-style.css', [], $version, 'all' );
        }
    }

    // Front-end assets enqueue callback function
    public function load_assets(){
        $version = WPH_POST_VIEW_COUNT_VERSION;
        $asset_directory = plugins_url('assets/', __FILE__);
        wp_enqueue_style( 'wph-pvc-main-style', $asset_directory . '/public/css/main-style.css', [], $version, 'all' );
    }

        public static function activate(){
            // Activation tasks, if any
        }

        public static function deactivate(){
            flush_rewrite_rules();
        }

        public static function uninstall(){
            // Uninstall tasks, if any
        }

        public function load_textdomain(){
            load_plugin_textdomain(
                'post-view-count',
                false,
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }
    }
}

if (class_exists('WPH_Post_View_Count')) {
    register_activation_hook(__FILE__, array('WPH_Post_View_Count', 'activate'));
    register_deactivation_hook(__FILE__, array('WPH_Post_View_Count', 'deactivate'));
    register_uninstall_hook(__FILE__, array('WPH_Post_View_Count', 'uninstall'));

    $wph_post_view_count = new WPH_Post_View_Count();
}
