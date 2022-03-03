<?php
/**
 * Modify admin bar.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

// Hide always all email address encoder notifications
define( 'EAE_DISABLE_NOTICES', apply_filters( 'helper_remove_eae_admin_bar', true ) );

/**
 * Clean up admin bar.
 *
 * Turn off by using `remove_action( 'wp_before_admin_bar_render', 'helper_helper_remove_admin_bar_links' )`
 * Modify list by using `add_filter( 'helper_helper_remove_admin_bar_links', 'myprefix_override_helper_helper_remove_admin_bar_links' )`
 *
 * @since  0.1.0
 */
add_action( 'wp_before_admin_bar_render', 'helper_helper_remove_admin_bar_links' );
function helper_helper_remove_admin_bar_links() {
  global $wp_admin_bar;

  $remove_items = apply_filters( 'helper_helper_remove_admin_bar_links', [
    'wp-logo',
    'about',
    'wporg',
    'documentation',
    'support-forums',
    'feedback',
    'updates',
    'comments',
    'customize',
    'imagify',
  ] );

  foreach ( $remove_items as $item ) {
    $wp_admin_bar->remove_menu( $item );
  }
} // end helper_helper_remove_admin_bar_links

/**
 * Add envarioment marker to adminbar.
 *
 * Turn off by using `remove_action( 'admin_bar_menu', 'helper_adminbar_show_env' )`
 *
 * @since  1.1.0
 */
add_action( 'admin_bar_menu', 'helper_adminbar_show_env', 999 );
function helper_adminbar_show_env( $wp_admin_bar ) {
  // Default to production env
  $env = wp_get_environment_type();
  $class = 'style-helper-env-prod';

  if ( wp_get_environment_type() === 'staging' ) {
    $class = 'style-helper-env-stage';
  } else if ( wp_get_environment_type() === 'development' ) {
    $env .= ' (DB ' . getenv( 'DB_HOST' ) . ')'; // On dev, show database
    $class = 'style-helper-env-dev';
  }

  $wp_admin_bar->add_node( [
    'id'    => 'stylehelperenv',
    'title' => wp_sprintf( __( 'Environment: %s', 'style-helper' ), $env ),
    'href'  => '#',
    'meta'  => [
      'class' => $class,
    ],
  ] );
} // end helper_adminbar_show_env

/**
 * Add envarioment marker styles.
 *
 * Turn off by using `remove_action( 'admin_head', 'helper_adminbar_show_env_styles' )`
 *
 * @since  1.1.0
 */
add_action( 'admin_head', 'helper_adminbar_show_env_styles' );
add_action( 'wp_head', 'helper_adminbar_show_env_styles' );
function helper_adminbar_show_env_styles() { ?>
  <style>
    #wp-admin-bar-stylehelperenv.style-helper-env-prod > a {
      background: #00bb00 !important;
      color: black !important;
    }

    #wp-admin-bar-stylehelperenv.style-helper-env-stage > a {
      background: orange !important;
      color: black !important;
    }

    #wp-admin-bar-stylehelperenv.style-helper-env-dev > a {
      background: red !important;
      color: black !important;
    }
  </style>
<?php } // end helper_adminbar_show_env_styles
