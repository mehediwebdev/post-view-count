<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPH_Post_Vc_Shortcode' ) ) {
    class WPH_Post_Vc_Shortcode {
        public function __construct() {
            // Register shortcode
            add_shortcode( 'post_view_count', array( $this, 'post_view_count_shortcode' ) );
        }

        // Shortcode to display post view count
        public function post_view_count_shortcode( $atts ) {
            $atts = shortcode_atts( array(
                'id' => null,
            ), $atts );

            if ( $atts['id'] ) {
                $views = get_post_meta( $atts['id'], 'post_views', true );
                $output = '<div class="post-view-count">';
                $output .= '<h2>' . esc_html__( 'Page Views', 'post-view-count' ) . '</h2>';
                $output .= '<h3>' . esc_html__( 'Total Views', 'post-view-count' ) . '<br><strong>' . esc_html( $views ? $views : 0 ) . '</strong></h3>';
                $output .= '</div>';
                return $output;
            } else {
                return "<div class='post-view-count-error'><h2>" . esc_html__( 'Error:', 'post-view-count' ) . "</h2> <h3>" . esc_html__( 'Please provide a post ID.', 'post-view-count' ) . "</h3></div>";
            }
        }
    }
}

