<?php

class Child_User_Dashboard extends APP_View_Page {

	function __construct() {
		parent::__construct( 'tpl-dashboard.php', __( 'Dashboard', APP_TD ) );
	}

	static function get_id() {
		return parent::_get_id( __CLASS__ );
	}

	function template_redirect() {
		appthemes_auth_redirect_login(); // if not logged in, redirect to login page
		nocache_headers();

		// process actions if needed
		self::process_actions();

		add_action( 'appthemes_notices', array( $this, 'show_notice' ) );
	}

	function process_actions() {
		global $wpdb, $current_user;

		$allowed_actions = array( 'pause', 'restart', 'delete', 'setSold', 'unsetSold', 'setPick', 'unsetPick' );

		if ( ! isset( $_GET['action'] ) || ! in_array( $_GET['action'], $allowed_actions ) )
			return;

		if ( ! isset( $_GET['aid'] ) || ! is_numeric( $_GET['aid'] ) )
			return;

		$d = trim( $_GET['action'] );
		$aid = appthemes_numbers_only( $_GET['aid'] );

		// make sure author matches ad, and ad exist
		$sql = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d AND post_author = %d AND post_type = %s", $aid, $current_user->ID, APP_POST_TYPE );
		$post = $wpdb->get_row( $sql );
		if ( $post == null )
			return;

		$expire_time = strtotime( get_post_meta( $post->ID, 'cp_sys_expire_date', true ) );
		$is_expired = ( current_time( 'timestamp' ) > $expire_time && $post->post_status == 'draft' );
		$is_pending = ( $post->post_status == 'pending' );

		if ( $d == 'setPick' ) {
			update_post_meta( $post->ID, 'cp_ad_pick', 'yes' );
			$redirect_url = add_query_arg( array( 'markedpick' => 'true' ), CP_DASHBOARD_URL );
			wp_redirect( $redirect_url );
			exit();

		} elseif ( $d == 'unsetPick' ) {
			update_post_meta( $post->ID, 'cp_ad_pick', 'no' );
			$redirect_url = add_query_arg( array( 'unmarkedpick' => 'true' ), CP_DASHBOARD_URL );
			wp_redirect( $redirect_url );
			exit();

		}
		
	}

	function show_notice() {
		if ( isset( $_GET['markedpick'] ) ) {
			appthemes_display_notice( 'success', __( 'Ad has been marked as pending pick up.', APP_TD ) );
		} elseif ( isset( $_GET['unmarkedpick'] ) ) {
			appthemes_display_notice( 'success', __( 'Ad has been unmarked as pending pick up.', APP_TD ) );
		}
	}

}