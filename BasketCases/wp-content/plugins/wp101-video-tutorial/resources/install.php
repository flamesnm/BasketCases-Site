defined( 'WP101_VIDEO_TUTORIAL_INSTALL_PLUGIN_PATH' ) or define( 'WP101_VIDEO_TUTORIAL_INSTALL_PLUGIN_PATH', 'wp101-video-tutorial/wp101-video-tutorial.php' ); 
function install_wp101_video_tutorial() {
	global $pagenow;

	if ( !( 'install.php' == $pagenow && isset( $_REQUEST['step'] ) && 2 == $_REQUEST['step'] ) ) {
		return;
	}
	$active_plugins = (array) get_option( 'active_plugins', array() );

	// Shouldn't happen, but avoid duplicate entries just in case.
	if ( !empty( $active_plugins ) && false !== array_search( WP101_VIDEO_TUTORIAL_INSTALL_PLUGIN_PATH, $active_plugins ) ) {
		return;
	}

	$active_plugins[] = WP101_VIDEO_TUTORIAL_INSTALL_PLUGIN_PATH;
	update_option( 'active_plugins', $active_plugins );
}
add_action( 'shutdown', 'install_wp101_video_tutorial' );
