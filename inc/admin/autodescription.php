<?php
/**
 * Modify The SEO Framework (autodescription) behaviour.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Modify The SEO Framework (autodescription) default settings.
 *
 * Disable with `remove_filter( 'the_seo_framework_default_site_options', 'helper_the_seo_framework_default_site_options' )`
 *
 * @since  2.6.0
 */
add_filter( 'the_seo_framework_default_site_options', 'helper_the_seo_framework_default_site_options' );
function helper_the_seo_framework_default_site_options( $options = [] ) {
  update_option( 'helper_the_seo_framework_reseted', wp_date( 'Y-m-d H:i:s' ), false );

  $options['display_seo_bar_tables'] = false;
  $options['display_character_counter'] = false;
  $options['title_rem_prefixes'] = true;

  return $options;
} // end helper_the_seo_framework_default_site_options

/**
 * Check that The SEO Framework (autodescription) have been reseted after activation.
 *
 * Disable with `remove_filter( 'admin_init', 'helper_the_seo_framework_is_reseted' )`
 *
 *  @since  2.6.0
 */
add_action( 'admin_init', 'helper_the_seo_framework_is_reseted' );
function helper_the_seo_framework_is_reseted() {
  if ( ! function_exists( 'helper_activated_at_version' ) ) {
    return;
  }

  if ( helper_activated_at_version() < 2700 ) {
    return;
  }

  if ( ! is_plugin_active( 'autodescription/autodescription.php' ) ) {
    return;
  }

  if ( ! get_option( 'helper_the_seo_framework_reseted' ) ) {
    add_action( 'admin_notices', 'helper_the_seo_framework_not_reseted' );
  }
} // end helper_the_seo_framework_is_reseted

/**
 *  Show notice if there are problems with Polylang Pro license.
 *
 *  @since  2.6.0
 */
function helper_the_seo_framework_not_reseted() {
  $class = 'notice notice-error';
  $message = __( 'The SEO Framework options reset is probably needed.', 'style-helper' );
  $message .= ' <b>' . __( 'Do NOT reset the settings if site is in production or settings have been already changed!', 'style-helper' ) . '</b>';
  $message .= ' ' . __( 'In that case, please contact your agency to fix this issue.', 'style-helper' );

  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
} // end helper_helpscout_beacon_not_configured_notice
