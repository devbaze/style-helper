<?php
/**
 * Customizer actions.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Remove the additional CSS section from the Customizer.
 *
 * Add back by using `remove_action( 'customize_register', 'helper_customizer_remove_css_section' )`
 *
 * @param object $wp_customize WP_Customize_Manager.
 * @since  1.3.0
 */
add_action( 'customize_register', 'helper_customizer_remove_css_section', 15 );
function helper_customizer_remove_css_section( $wp_customize ) {
  $wp_customize->remove_section( 'custom_css' );
} // end helper_customizer_remove_css_section
