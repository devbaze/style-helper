<?php
/**
 * Commenting and pingback related.
 *
 *@Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 * Turn off by using `remove_action( 'wp_head', 'helper_pingback_header' )`
 *
 * @since  0.1.0
 */
add_action( 'wp_head', 'helper_pingback_header' );
function helper_pingback_header() {
  if ( is_singular() && pings_open() ) {
    echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
  }
} // end helper_pingback_heade


/**
 * Remove unnecessary WordPress injected .recentcomments
 *
 * @since 2.6.0
 */
add_action( 'widgets_init', 'helper_remove_recent_comments_style' );
function helper_remove_recent_comments_style() {
  if ( ! function_exists( 'helper_activated_at_version' ) ) {
    return;
  }

  if ( helper_activated_at_version() < 2600 ) {
    return;
  }

  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
} // end helper_remove_recent_comments_style
