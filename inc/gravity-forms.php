<?php
/**
 * Gravity forms related actions.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Allow Gravity Forms to hide labels to add placeholders.
 *
 * Turn off by using `add_filter( 'gform_enable_field_label_visibility_settings', '__return_false' )`
 *
 * @since  0.1.0
 */
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
