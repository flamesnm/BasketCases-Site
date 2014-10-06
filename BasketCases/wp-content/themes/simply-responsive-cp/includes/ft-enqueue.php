<?php
/**
 * These are scripts used within the theme
 * To increase speed and performance, we only want to
 * load them when needed
 *
 * @package ClassiPress
 *
 */


// changes the css file based on what is selected on the options page
if ( !function_exists('cp_style_changer') ) :
function cp_style_changer() {
	global $cp_options;

	wp_enqueue_style( 'at-main', get_stylesheet_uri(), false );

	// turn off stylesheets if customers want to use child themes
	if ( ! $cp_options->disable_stylesheet )
		wp_enqueue_style( 'at-color', get_stylesheet_directory_uri() . '/styles/' . $cp_options->stylesheet, false );

	if ( file_exists( get_template_directory() . '/styles/custom.css' ) )
		wp_enqueue_style( 'at-custom', get_template_directory_uri() . '/styles/custom.css', false );

}
endif;
add_action( 'wp_enqueue_scripts', 'cp_style_changer' );
