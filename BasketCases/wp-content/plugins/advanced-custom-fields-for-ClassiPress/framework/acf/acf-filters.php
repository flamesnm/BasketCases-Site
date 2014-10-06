<?php
/**
 * Here placed acfcp visibility filters
 *
 * @since 2.0
 *
 */

/**
 * Check if current user is author or admin
 *
 * @param int $author Ad poster ID
 * @param int $current_user Current User ID
 *
 * @since 1.1
 * @return bool Returns True if User is Admin or Author
 */
function acf_u_can_see( $author, $current_user ) {
	return ( current_user_can( 'manage_options' ) || $author == $current_user ) ? true : false;
}

function acf_location_filter( $props, $name, $location ) {
	if ( isset( $props[ $location ] ) && 'yes' === $props[ $location ] )
		return $props;
}

function acf_private_filter( $props ) {
	if ( isset( $props['private'] ) && 'yes' === $props['private'] && isset( $props['can_see'] ) && ! $props['can_see']  )
		return false;

	return $props;
}

function acf_logged_filter( $props ) {
	if ( isset( $props['logged_in'] ) && 'yes' === $props['logged_in'] && ! is_user_logged_in() )
		return false;

	return $props;
}

function acf_featured_filter( $props ) {
	if ( isset( $props['featured'] ) && 'yes' === $props['featured'] && ! is_sticky() )
		return false;

	return $props;
}

/**
 * Transform case of sending text
 *
 * @since 1.1
 * @param type $props
 * @return boolean|array
 */
function acf_transform_filter( $props ) {
	if ( ! $props ) return false;
	if ( isset( $props['transform'] ) && '' != $props['transform'] && ! is_array( $props['value'] ) && $props['value'] ) {

		switch ( $props['transform'] ) {
			case 'Capitalize':
				$props['value'] = mb_convert_case( $props['value'], MB_CASE_TITLE, "UTF-8" );
				break;
			case 'Uppercase':
				$props['value'] = mb_convert_case( $props['value'], MB_CASE_UPPER, "UTF-8" );
				$props['value'] = preg_replace( '/&NBSP;/', '&nbsp;', $props['value'] );
				break;
			case 'Lowercase':
				$props['value'] = mb_convert_case( $props['value'], MB_CASE_LOWER, "UTF-8" );
				break;
			default:
				break;
		}
	}
	return $props;
}