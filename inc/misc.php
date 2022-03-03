<?php
/**
 * Collection of miscellaneous actions.
 *
 * @Author: Benjamin Pelto
 *
 * @package style-helper
 */

/**
 * Remove unnecessary type attributes to suppress HTML validator messages.
 *
 * Turn off by using `add_filter( 'style_loader_tag', 'helper_remove_type_attr' )`
 * Turn off by using `add_filter( 'script_loader_tag', 'helper_remove_type_attr' )`
 * Turn off by using `add_filter( 'autoptimize_html_after_minify', 'helper_remove_type_attr' )`
 *
 * @since  2.3.0
 */
add_filter( 'style_loader_tag', 'helper_remove_type_attr', 10, 2 );
add_filter( 'script_loader_tag', 'helper_remove_type_attr', 10, 2 );
add_filter( 'autoptimize_html_after_minify', 'helper_remove_type_attr', 10, 2 );
function helper_remove_type_attr( $tag, $handle = '' ) {
  return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag ); // phpcs:ignore
} // end helper_remove_type_attr

/**
 *  Strip unwanted html tags from titles
 *
 *  Turn off by using `remove_filter( 'nav_menu_item_title', 'helper_strip_tags_menu_item' )`
 *  Turn off by using `remove_filter( 'the_title', 'helper_strip_tags_menu_item' )`
 *
 *  @since  1.4.1
 *  @param  string $title title to strip.
 *  @param  mixed  $arg_2 whatever filter can pass.
 *  @param  mixed  $arg_3 whatever filter can pass.
 *  @param  mixed  $arg_4 whatever filter can pass.
 */
add_filter( 'nav_menu_item_title', 'helper_strip_tags_menu_item', 10, 4 );
add_filter( 'the_title', 'helper_strip_tags_menu_item', 10, 2 );
function helper_strip_tags_menu_item( $title, $arg_2 = null, $arg_3 = null, $arg_4 = null ) {
  return strip_tags( $title, apply_filters( 'helper_allowed_tags_in_title', '<br><em><b><strong>' ) );
} // end helper_strip_tags_menu_item

/**
 * Add instant.page just-in-time preloading script to footer.
 *
 * Disble using `remove_action( 'wp_enqueue_scripts', 'helper_enqueue_instantpage_script', 50 )`
 *
 * @since 5.0.0
 */
add_action( 'wp_enqueue_scripts', 'helper_enqueue_instantpage_script' );
function helper_enqueue_instantpage_script() {
  wp_enqueue_script( 'instantpage', helper_base_url() . '/assets/js/instantpage.js', [], '5.1.0', true );
} // end helper_enqueue_instantpage_script
