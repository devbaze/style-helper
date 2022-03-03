<?php
/**
 * Priority hooks for rare occasions when plugins do not respect loading order.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Force Mailgun tracking options to disabled.
 *
 * Turn off by using `remove_filter( 'option_mailgun', 'helper_disable_mailgun_tracking' )`
 *
 * @since 2.15.0
 */
add_filter( 'option_mailgun', 'helper_disable_mailgun_tracking' );
function helper_disable_mailgun_tracking( $option ) {
  $option['track-clicks'] = 'no';
  $option['track-opens'] = '0';

  return $option;
} // end helper_disable_mailgun_tracking

/**
 * Disable Mailgun tracking option selects in admin page, as we force those options always to be disabled.
 *
 * Turn off by using `remove_action( 'admin_footer-settings_page_mailgun', 'helper_disable_mailgun_tracking_settings' )`
 *
 * @since 2.15.0
 */
add_action( 'admin_footer-settings_page_mailgun', 'helper_disable_mailgun_tracking_settings' );
function helper_disable_mailgun_tracking_settings() { ?>
  <script type="text/javascript">
    document.querySelector('select[name="mailgun[track-clicks]"]').disabled = true;
    document.querySelector('select[name="mailgun[track-opens]"]').disabled = true;
  </script>
<?php } // end helper_disable_mailgun_tracking_settings
