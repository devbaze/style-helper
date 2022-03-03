<?php
/**
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 *  Check that Help Scout Beacon ID is confugured.
 *
 *  @since  2.0.0
 */
add_action( 'admin_init', 'helper_is_helpscout_beacon_configured' );
function helper_is_helpscout_beacon_configured() {
  if ( ! getenv( 'HS_BEACON_ID' ) && helper_site_has_care_plan() ) {
    return false;
  }

  return true;
} // end helper_is_helpscout_beacon_configured

/**
 * Enqueue Helpscout beacon in dashboard for providing user support
 * for sites that are in our care plan.
 *
 * Disable using `remove_action( 'admin_enqueue_scripts', 'helper_enqueue_helpscout_beacon' )`
 *
 * @since  5.0.0
 */
add_action( 'admin_enqueue_scripts', 'helper_enqueue_helpscout_beacon' );
function helper_enqueue_helpscout_beacon() {
  // Show only if in care plan
  if ( ! helper_site_has_care_plan() ) {
    return;
  }

  // Bail if no beacon id configured
  if ( ! helper_is_helpscout_beacon_configured() ) {
    return;
  }

  // Increase body padding for the HS widget not to override paging controls
  add_action( 'admin_head', function() { ?>
    <style type="text/css">
      #wpbody-content {
        padding-bottom: 100px;
      }
    </style>
  <?php } );

  wp_enqueue_script( 'helpscout-beacon', helper_base_url() . '/assets/js/helpscout-beacon.js', [], '2.0.0', true );

  // Settings for beacon and string translations based on the language user has in dashboard rather than using the browser language
  $user_info = get_userdata( get_current_user_id() );
  wp_localize_script( 'helpscout-beacon', 'stylehelperHelpscout', [
    'color'         => '#4d4aff',
    'userEmail'     => $user_info->user_email,
    'userName'      => $user_info->user_nicename,
    'site'          => get_bloginfo( 'name' ),
    'siteUrl'       => get_site_url(),
    'beaconId'      => getenv( 'HS_BEACON_ID' ),
    'signature'     => hash_hmac(
      'sha256',
      $user_info->user_email,
      getenv( 'NONCE_SALT' )
    ),
    'translations'  => [
      'prefilledSubject'          => __( 'Help request', 'style-helper' ),
      'text'                      => __( 'Do you need help?', 'style-helper' ),
      'sendAMessage'              => __( 'Dude user support', 'style-helper' ),
      'howCanWeHelp'              => __( 'How can we help?', 'style-helper' ),
      'responseTime'              => __( 'Our support team will respond to you on next working day at latest', 'style-helper' ),
      'continueEditing'           => __( 'Continue writing…', 'style-helper' ),
      'lastUpdated'               => __( 'Last updated', 'style-helper' ),
      'you'                       => __( 'You', 'style-helper' ),
      'nameLabel'                 => __( 'Name', 'style-helper' ),
      'subjectLabel'              => __( 'Subject', 'style-helper' ),
      'emailLabel'                => __( 'Email address', 'style-helper' ),
      'messageLabel'              => __( 'How can we help?', 'style-helper' ),
      'messageSubmitLabel'        => __( 'Send support request', 'style-helper' ),
      'next'                      => __( 'Next', 'style-helper' ),
      'weAreOnIt'                 => __( 'We’re on it!', 'style-helper' ),
      'messageConfirmationText'   => __( 'You’ll receive an reply shortly.', 'style-helper' ),
      'wereHereToHelp'            => __( 'Dude user support', 'style-helper' ),
      'viewAndUpdateMessage'      => __( 'You can view and update your message in', 'style-helper' ),
      'whatMethodWorks'           => __( 'Our support team will respond to you on next working day at latest', 'style-helper' ),
      'previousMessages'          => __( 'Previous Conversations', 'style-helper' ),
      'messageButtonLabel'        => __( 'Email', 'style-helper' ),
      'noTimeToWaitAround'        => __( 'Send message to our support team', 'style-helper' ),
      'addReply'                  => __( 'Add a reply', 'style-helper' ),
      'addYourMessageHere'        => __( 'Add your message here...', 'style-helper' ),
      'sendMessage'               => __( 'Send message', 'style-helper' ),
      'received'                  => __( 'Received', 'style-helper' ),
      'waitingForAnAnswer'        => __( 'Waiting for an answer', 'style-helper' ),
      'previousMessageErrorText'  => __( 'There was a problem retrieving this message. Please double-check your Internet connection and try again.', 'style-helper' ),
      'justNow'                   => __( 'Just Now', 'style-helper' ),
    ],
  ] );
}
