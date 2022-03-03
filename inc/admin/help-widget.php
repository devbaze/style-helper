<?php
/**
 * News and help widget for upkeeping customers.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 *  Add our news and support widget to dashboard. Also make sure that it is always first in
 *  order.
 *
 *  Turn off by using `remove_action( 'wp_dashboard_setup', 'helper_admin_dashboard_widgets_setup' )`
 *
 *  @since  1.7.0
 */
add_action( 'wp_dashboard_setup', 'helper_admin_dashboard_widgets_setup' );
function helper_admin_dashboard_widgets_setup() {
  // Show only if in care plan
  if ( ! helper_site_has_care_plan() ) {
    return;
  }

  // Add the dashboard widget
  wp_add_dashboard_widget(
    'style-helper-help', // id
    __( 'Updates from Dude', 'style-helper' ), // name
    'helper_admin_dashboard_widget_callback' // callbac
  );

  // Alter the widget order and make our widget always first
  global $wp_meta_boxes;
  $widget = $wp_meta_boxes['dashboard']['normal']['core']['style-helper-help'];
  unset( $wp_meta_boxes['dashboard']['normal']['core']['style-helper-help'] );
  $wp_meta_boxes['dashboard']['side']['core']['style-helper-help'] = $widget; // phpcs:ignore
} // end helper_admin_dashboard_widgets_setup

/**
 *  Enqueue styles and scripts for dashboard widget.
 *
 *  @since  1.7.0
 */
add_action( 'admin_enqueue_scripts', 'helper_dashboard_widget_styles' );
function helper_dashboard_widget_styles() {
  wp_register_style( 'style-helper-dashboard-widget', helper_base_url() . '/assets/css/dashboard-widget.css', false, helper_version() );
  wp_enqueue_style( 'style-helper-dashboard-widget' );
} // end helper_dashboard_widget_styles

/**
 *  Output dashboard widget content.
 *
 *  @since  1.7.0
 *  @param  mixed $post          Where widget is shown.
 *  @param  array $callback_args Arguments passed into callback function.
 */
function helper_admin_dashboard_widget_callback( $post, $callback_args ) {
  // get data for widget
  $data = _helper_admin_dashboard_widget_get_data();

  // if no data, show error message and bail
  if ( empty( $data ) ) {
    echo wp_kses_post( wpautop( __( 'Error while fetching data.', 'style-helper' ) ) );
    return;
  } ?>

  <div class="style-helper-help-wrapper">

    <div class="news-wrapper">

      <?php // check if we have sheculed maintenances
      if ( ! empty( $data->maintenances ) ) :
        // show only first maintennace
        $maintenance = $data->maintenances[0];
        $statuspage_url = apply_filters( 'helper_dashboard_widget_statuspage_url', 'https://status.stack.test' );

        if ( isset( $maintenance->start ) &&
          isset( $maintenance->end ) &&
          isset( $maintenance->title ) &&
          isset( $maintenance->desc )
        ) :
          // make maintenance start and end times to human readbale string
          $day_str = _helper_admin_dashboard_widget_get_time_str( $maintenance->start, $maintenance->end ); ?>
          <div class="maintenance">
            <h3><?php echo esc_html( $maintenance->title ) ?></h3>

            <p class="time">
              <?php echo esc_html( $day_str ) ?>
            </p>

            <?php echo wp_kses_post( wpautop( $maintenance->desc ) ) ?>

            <p class="read-more">
              <a href="<?php echo esc_url( $statuspage_url ) ?>" target="_blank"><?php echo esc_html( 'Server status on real time', 'style-helper' ) ?> &rarr;</a>
            </p>
          </div>
        <?php endif; // maintennace content isset
      endif; // ! empty( $data->maintenances )

      // check if we have news to show
      if ( ! empty( $data->news ) ) :

        // loop news
        foreach ( $data->news as $news ) :

          // if no essential content, skip this and continue to next
          if ( ! isset( $news->content ) ||
            ! isset( $news->time ) ||
            ! isset( $news->title ) ||
            ! isset( $news->content )
          ) {
            continue;
          }

          // strip tags from content.
          $content = strip_tags( $news->content, '<a><i><b><br><strong><italic>' ); ?>
          <div class="news">
            <p class="time">
              <?php echo esc_html( date_i18n( 'j.n.Y H:i', strtotime( $news->time ) ) ) ?>
            </p>

            <h3><?php echo esc_html( $news->title ) ?></h3>

            <?php echo wp_kses_post( wpautop( $content ) );

            if ( isset( $news->link ) ) :
              if ( ! empty( $news->link->href ) && ! empty( $news->link->title ) ) : ?>
                <p class="read-more">
                  <a href="<?php echo esc_url( $news->link->href ) ?>" target="_blank"><?php echo esc_html( $news->link->title ) ?> &rarr;</a>
                </p>
              <?php endif;
            endif; ?>

          </div>
        <?php endforeach; // loop news
      endif; // ! empty( $data->news ) ?>
    </div>

  </div>
<?php } // end function helper_admin_dashboard_widget_help_callback

/**
 *  Get data for the widget from helpwidget api.
 *
 *  @since  1.7.0
 *  @return mixed  false if no data, otherwise data object
 */
function _helper_admin_dashboard_widget_get_data() {
  // Get data from transient
  $data = get_site_transient( 'helpwidget_data' );
  if ( $data ) {
    return $data;
  }

  // Make api reauest if data isn't in cache
  $api_base = _helper_admin_dashboard_widget_get_api_url();
  $api_access_token = _helper_admin_dashboard_widget_get_api_key();
  $request = wp_remote_request( "{$api_base}/v1/newsfeed?access_token={$api_access_token}" );

  if ( is_wp_error( $request ) ) {
    return false;
  }

  if ( empty( $request['body'] ) ) {
    return false;
  }

  // decode returned data
  $data = json_decode( $request['body'] );

  // Set data to cache
  set_site_transient( 'helpwidget_data', $data, apply_filters( 'helper_dashboard_widget_data_cache_lifetime', HOUR_IN_SECONDS ) );

  return $data;
} // end function _helper_admin_dashboard_widget_get_data

function _helper_admin_dashboard_widget_get_time_str( $start = null, $end = null ) {
  $day_str = '';

  // bail if not time provided
  if ( empty( $start ) ) {
    return $day_str;
  }

  // no end time provided or same as start, make simple string
  if ( empty( $end ) || $start === $end ) {
    $day_str = ucfirst( date_i18n( 'l j.n.Y H:i:s', strtotime( get_date_from_gmt( $start ) ) ) );
  } else {
    // get months and dates for comparison
    $start_month = date_i18n( 'M', strtotime( get_date_from_gmt( $start ) ) );
    $end_month = date_i18n( 'M', strtotime( get_date_from_gmt( $end ) ) );
    $start_day = date_i18n( 'D', strtotime( get_date_from_gmt( $start ) ) );
    $end_day = date_i18n( 'D', strtotime( get_date_from_gmt( $end ) ) );

    // make str start based on if start month and end month are same or if dates are same
    if ( $start_month !== $end_month ) {
      $day_str = ucfirst( date_i18n( 'l\n\a j.n. -', strtotime( get_date_from_gmt( $start ) ) ) );
    } elseif ( $start_day !== $end_day ) {
      $day_str = ucfirst( date_i18n( 'l\n\a j. -', strtotime( get_date_from_gmt( $start ) ) ) );
    }

    // add end day to str
    $day_str .= ucfirst( date_i18n( 'l\n\a j.n.Y', strtotime( get_date_from_gmt( $end ) ) ) );

    // and times to str
    $day_str .= date_i18n( ' H:i', strtotime( get_date_from_gmt( $start ) ) );
    $day_str .= date_i18n( ' - H:i', strtotime( get_date_from_gmt( $end ) ) );
  }

  // return str
  return $day_str;
} // end function _helper_admin_dashboard_widget_get_time_str

/**
 *  Get API base url for dashboard widget API calls.
 *
 *  @since  1.7.0
 *  @return string  api base url
 */
function _helper_admin_dashboard_widget_get_api_url() {
  return 'http://api.stack.test/helpwidget';
} // end _helper_admin_dashboard_widget_get_api_url

/**
 *  Get key for dashbard widget API calls.
 *
 *  @since  1.7.0
 *  @return string  api key
 */
function _helper_admin_dashboard_widget_get_api_key() {
  // Fall back to SendGrid api key for legacy support
  return ! empty( getenv( 'STACK_API_KEY' ) ) ? getenv( 'STACK_API_KEY' ) : getenv( 'SENDGRID_API_KEY' );
} // end _helper_admin_dashboard_widget_get_api_key
