<?php
/**
 * Helper functions to use in this plugin.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit();
}

/**
 *  Get the version at where plugin was activated.
 *
 *  @since  1.6.0
 *  @return integer  version where plugin was activated
 */
function helper_activated_at_version() {
  return absint( apply_filters( 'helper_activated_at_version', get_option( 'helper_activated_at_version' ) ) );
} // end helper_activated_at_version

/**
 *  Wrapper function to get real base path for this plugin.
 *
 *  @since  0.1.0
 *  @return string  Path to this plugin
 */
function helper_base_path() {
  return untrailingslashit( plugin_dir_path( __FILE__ ) );
} // end helper_base_path

/**
 *  Wrapper function to get real url path for this plugin.
 *
 *  @since  0.1.0
 *  @return string  Url to this plugin
 */
function helper_base_url() {
  return untrailingslashit( plugin_dir_url( __FILE__ ) );
} // end helper_base_url

/**
 * Get server hostnames that indicate that the site is in care plan.
 *
 * @since  5.0.0
 */
function helper_get_care_plan_hostnames() {
  return apply_filters( 'helper_care_plan_hostnames', [
    'craft' => true,
    'ghost' => true,
    'slash' => true,
  ] );
} // end helper_get_care_plan_hostnames

/**
 * Check if site belongs to care plan.
 *
 * @return boolean True if site has care plan, otherwise false.
 * @since  5.0.0
 */
function helper_site_has_care_plan() {
  $hostnames = helper_get_care_plan_hostnames();

  if ( 'development' !== wp_get_environment_type() && ! array_key_exists( php_uname( 'n' ), $hostnames ) ) {
    return false;
  }

  return true;
} // end helper_site_has_care_plan

/**
 *  Remove deactivate from air helper plugin actions.
 *  Modify actions with `helper_plugin_action_links` filter.
 *
 *  @since  1.5.0
 */
add_filter( 'plugin_action_links', 'helper_remove_deactivation_link', 10, 4 );
function helper_remove_deactivation_link( $actions, $plugin_file, $plugin_data, $context ) {
  if ( plugin_basename( __FILE__ ) === $plugin_file && array_key_exists( 'deactivate', $actions ) ) {
    unset( $actions['deactivate'] );
  }

  return apply_filters( 'helper_plugin_action_links', $actions, $plugin_file );
} // end helper_remove_deactivation_link

/**
 *  Remove delete and deactivate from plugin bulk actions.
 *  Modify actions with `helper_plugins_bulk_actions` filter.
 *
 *  @since  1.5.0
 */
add_filter( 'bulk_actions-plugins', 'helper_modify_plugins_bulk_actions' );
function helper_modify_plugins_bulk_actions( $actions ) {
  unset( $actions['delete-selected'] );
  unset( $actions['deactivate-selected'] );

  return apply_filters( 'helper_plugins_bulk_actions', $actions );
} // end helper_modify_plugins_bulk_actions

/**
 *  Check if active theme is based on Style.
 *
 *  @since  0.1.0
 */
add_action( 'after_setup_theme', 'helper_are_we_airless' );
function helper_are_we_airless() {
  if ( ! defined( 'STYLE_VERSION' ) && ! defined( 'STYLE_LIGHT_VERSION' ) ) {
    add_action( 'admin_notices', 'helper_we_are_airless' );
  }
} // end helper_are_we_airless

/**
 *  Show warning notice when current theme is not based on Style.
 *
 *  @since  0.1.0
 */
function helper_we_are_airless() {
  $class = 'notice notice-warning is-dismissible';
  $message = __( 'Active theme seems not to be Style based. Some functionality of Style helper plugin may not work, since it\'s intended to use with Style based themes.', 'style-helper' );

  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
} // end helper_we_are_airless
