<?php
/**
 * Advanced Custom Fields plugin modifications.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 *  Hide ACF for all users, execpt for users with spesific domain or override in user meta.
 *
 *  Turn off by using `remove_filter( 'acf/settings/show_admin', 'helper_maybe_hide_acf' )`
 *
 *  @since  1.12.0
 */
add_filter( 'acf/settings/show_admin', 'helper_maybe_hide_acf' );
function helper_maybe_hide_acf() {
  $current_user = get_current_user_id();
  $user = new WP_User( $current_user );
  $domain = apply_filters( 'helper_dont_hide_acf_from_domain', 'stack.test' );
  $meta_override = get_user_meta( $user->ID, '_stylehelper_admin_show_acf', true );

  if ( 'true' === $meta_override ) {
    return $menu_links;
  }

  if ( strpos( $user->user_email, "@{$domain}" ) === false ) {
    return false;
  }

  return true;
} // end helper_maybe_hide_acf

/**
 * If ACF Pro license key is defined in .env file, try to load the key from there.
 *
 * Turn off by using `remove_action( 'admin_init', 'helper_define_acf_pro_license' )`
 *
 * @since 2.12.0
 */
add_action( 'admin_init', 'helper_define_acf_pro_license' );
function helper_define_acf_pro_license() {
  if ( empty( getenv( 'ACF_PRO_KEY' ) ) ) {
    return;
  }

  define( 'ACF_PRO_LICENSE', getenv( 'ACF_PRO_KEY' ) );
} // end helper_define_acf_pro_license
