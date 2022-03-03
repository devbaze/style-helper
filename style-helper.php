<?php
/**
 * Plugin Name: Style helper
 * Plugin URI: https://github.com/devbaze/style-helper
 * Description: Plugin provides helpful functions and modifications for WordPress projects.
 * Version: 1.0.0
 * Author: Benjamin Pelto
 * Author URI: https://github.com/devbaze
 * Requires at least: 5.5
 * Tested up to: 5.8
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Text Domain: style-helper
 * Domain Path: /languages
 *
 * @package style-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit();
}

/**
 * Get current version of plugin. Version is semver without extra marks, so it can be used as a int.
 *
 * @since 1.6.0
 * @return integer current version of plugin
 */
function helper_version() {
  return 21401;
} // end helper_version

/**
* Require helpers for this plugin.
*
* @since 2.0.0
*/
require 'plugin-helper.php';

/**
 * Priority hooks for rare occasions when plugins do not respect loading order.
 */
require_once helper_base_path() . '/inc/priority/general.php';

/**
* Require priority files.
*/
add_action( 'init', 'helper_priority_fly', 5 );
function helper_priority_fly() {
  // Load textdomain for few translations in this plugin
  load_plugin_textdomain( 'style-helper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

  // Hook & filter files
  require_once helper_base_path() . '/inc/priority/security.php';
  require_once helper_base_path() . '/inc/priority/site-health-check.php';
  require_once helper_base_path() . '/inc/priority/mail-delivery.php';
  require_once helper_base_path() . '/inc/priority/misc.php';
} // end helper_priority_fly

/**
* Require files containing our preferences.
*/
add_action( 'init', 'helper_fly', 998 );
function helper_fly() {
  // Function files
  require_once helper_base_path() . '/functions/archives.php';
  require_once helper_base_path() . '/functions/checks.php';
  require_once helper_base_path() . '/functions/pagination.php';
  require_once helper_base_path() . '/functions/misc.php';
  require_once helper_base_path() . '/functions/localization.php';
  require_once helper_base_path() . '/functions/image-lazyload.php';

  // Hook & filter files
  require_once helper_base_path() . '/inc/mail.php';
  require_once helper_base_path() . '/inc/archives.php';
  require_once helper_base_path() . '/inc/comments.php';
  require_once helper_base_path() . '/inc/rest-api.php';
  require_once helper_base_path() . '/inc/customizer.php';
  require_once helper_base_path() . '/inc/gravity-forms.php';
  require_once helper_base_path() . '/inc/yoast-seo.php';
  require_once helper_base_path() . '/inc/imagify.php';
  require_once helper_base_path() . '/inc/tinymce.php';
  require_once helper_base_path() . '/inc/media.php';
  require_once helper_base_path() . '/inc/misc.php';
} // end helper_fly

/**
* Require files needed on admin side of the site.
*/
add_action( 'init', 'helper_admin_fly' );
  function helper_admin_fly() {
  if ( ! is_user_logged_in() || wp_doing_ajax() ) {
    return false;
  }

  require_once helper_base_path() . '/inc/admin/adminbar.php';
  require_once helper_base_path() . '/inc/admin/autodescription.php';
  require_once helper_base_path() . '/inc/admin/notifications.php';
  require_once helper_base_path() . '/inc/admin/access.php';
  require_once helper_base_path() . '/inc/admin/acf.php';
  require_once helper_base_path() . '/inc/admin/localization.php';
  require_once helper_base_path() . '/inc/admin/dashboard.php';
  require_once helper_base_path() . '/inc/admin/help-widget.php';
  require_once helper_base_path() . '/inc/admin/updates.php';
  require_once helper_base_path() . '/inc/admin/helpscout.php';
  require_once helper_base_path() . '/inc/admin/polylang.php';
} // end helper_admin_fly

/**
* Plugin activation hook to save current version for reference in what version activation happened.
* Check if deactivation without version option is apparent, then do not save current version for
* maintaining backwards compatibility.
*
* @since 1.6.0
*/
register_activation_hook( __FILE__, 'helper_activate' );
function helper_activate() {
  $deactivated_without = get_option( 'helper_deactivated_without_version' );

  if ( 'true' !== $deactivated_without ) {
    update_option( 'helper_activated_at_version', helper_version() );
  }
} // end helper_activate

/**
* Maybe add option if activated version is not yet saved.
* Helps to maintain backwards compatibility.
*
* @since 1.6.0
*/
register_deactivation_hook( __FILE__, 'helper_deactivate' );
add_action( 'admin_init', 'helper_deactivate' );
function helper_deactivate() {
  $activated_version = get_option( 'helper_activated_at_version' );

  if ( ! $activated_version ) {
    update_option( 'helper_deactivated_without_version', 'true', false );
  }
} // end helper_deactivate
