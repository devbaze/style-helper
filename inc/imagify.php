<?php
/**
 * Imagify default settings.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

// Disable some features
add_filter( 'get_imagify_option_admin_bar_menu', '__return_false' );
add_filter( 'get_imagify_option_convert_to_webp', '__return_false' );
add_filter( 'get_imagify_option_backup', '__return_false' );

/**
 * Get Imagify API key from .env
 *
 * @since 5.0.0
 */
add_filter( 'get_imagify_option_api_key', 'helper_imagify_api_key' );
function helper_imagify_api_key() {
  return getenv( 'IMAGIFY_API_KEY' );
} // end helper_imagify_api_key

/**
 * Resize large images and set maximum width.
 *
 * Disable with `add_filter( 'get_imagify_option_resize_larger', '__return_false' )` and `remove_filter( 'get_imagify_option_resize_larger_w', 'helper_imagify_resize_larger_w' )`
 * Modify the maximum width with `helper_imagify_resize_larger_w` filter
 *
 * @since  5.0.0
 */
add_filter( 'get_imagify_option_resize_larger', '__return_true' );
add_filter( 'get_imagify_option_resize_larger_w', 'helper_imagify_resize_larger_w' );
function helper_imagify_resize_larger_w() {
  return apply_filters( 'helper_imagify_resize_larger_w', '2048' );
} // end helper_imagify_resize_larger_w

/**
 * Set optimization level to normal.
 *
 * Disable with `remove_filter( 'get_imagify_option_optimization_level', 'helper_imagify_optimization_level' )`
 * Modify the level with `helper_imagify_optimization_level` filter. 0 = normal, 1 = aggressive, 2 = ultra.
 *
 * @since  5.0.0
 */
add_filter( 'get_imagify_option_optimization_level', 'helper_imagify_optimization_level' );
function helper_imagify_optimization_level( $level ) {
  return (string) apply_filters( 'helper_imagify_optimization_level', '0' );
} // end helper_imagify_optimization_level
