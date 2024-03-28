<?php

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

if( ! class_exists( 'WPH_Post_Vc_Admin_Cl' ) ){
   class WPH_Post_Vc_Admin_Cl{
      public function __construct(){
        // add_shortcode( 'mv_slider', array( $this, 'add_shortcode'));
  // Add custom column in admin
  add_filter('manage_posts_columns', array($this, 'add_view_count_column'));
  add_action('manage_posts_custom_column', array($this, 'display_view_count_column'), 10, 2);

  // Make the column sortable
  add_filter('manage_edit-post_sortable_columns', array($this, 'make_view_count_column_sortable'));
  add_action('pre_get_posts', array($this, 'sort_by_view_count'));
  }

  public function add_view_count_column($columns) {
   //$columns['post_views'] = 'View Count';
   $columns['post_views'] = '<div class="post-view-count-column">View Countt</div>';
   return $columns;
}

// Display view count in custom column
public function display_view_count_column($column, $post_id) {
   if ($column === 'post_views') {
       $views = get_post_meta($post_id, 'post_views', true);
       echo esc_html($views ? $views : 0);
   }
}

// Make the column sortable
public function make_view_count_column_sortable($columns) {
   $columns['post_views'] = 'post_views';
   return $columns;
}

// Sort posts by view count
public function sort_by_view_count($query) {
   if (!is_admin() || !$query->is_main_query()) {
       return;
   }

   $orderby = $query->get('orderby');

   if ('post_views' === $orderby) {
       $query->set('meta_key', 'post_views');
       $query->set('orderby', 'meta_value_num');
   }
} 
    


 }
}
