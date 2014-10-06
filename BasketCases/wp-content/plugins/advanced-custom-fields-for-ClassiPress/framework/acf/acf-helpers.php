<?php
/**
 * Here placed any acfcp helpers
 *
 * @since 1.1
 *
 */

/**
 * Check page if it a Edit Profile page
 * @since 1.1
 * @return bool
 */
function acf_is_edit_profile_action() {
	if ( isset( $_POST[ 'action' ] ) && 'app-edit-profile' == $_POST[ 'action' ] )
		return true;
	else
		return false;
}

/**
 * Check page if it a Edit Ad page
 * @since 1.1
 * @return bool
 */
function acf_is_edit_ad_action() {
	if ( !isset( $_POST[ 'action' ] ) || 'cp-edit-item' != $_POST[ 'action' ] || !current_user_can( 'edit_posts' ) || !$_POST[ 'custom_fields_vals' ] )
		return false;
	else
		return true;
}

/**
 * ACF checked function.
 * Gets array or delimitted string
 * DO NOT DELETE this function, used in ASP<->ACF checking
 *
 * @param string $option Current option of form field
 * @param string|array $chkarr input value
 * @since 1.0
 * @return string|null checked="checked" or null
 */
function acf_checked( $option, $chkarr ) {

	if ( is_string( $chkarr ) )
		$chkarr = explode( ',', $chkarr );

	if ( is_array( $chkarr ) ) {

		foreach ( $chkarr as $chkval ) {

			if ( trim( $chkval ) == trim( $option ) )
				return 'checked="checked"';
		}
	}
	else
		return '';
}


/**
 * Return required or not, considering limitations mins and ranges
 *
 * @param array $field_props Array of field properties
 *
 * @since 1.1
 * @return bool Required or not
 */
function acf_required( $field_props ) {

	if ( !$field_props )
		return false;

	$required = false;

	if ( isset( $field_props[ 'format' ] ) && 'required' == $field_props[ 'format' ] )
		$required = true;

	if ( isset( $field_props[ 'limits' ] ) && $field_props[ 'limits' ] != '' && isset( $field_props[ 'limits_attr' ] ) && $field_props[ 'limits_attr' ] != '' ) {
		$limit = $field_props[ 'limits' ];
		$limit_arr = array( 'min', 'minlength', 'minWords', 'mincollocations', 'minchoice', 'range', 'rangelength', 'rangeWords', 'rangecollocations', 'rangechoice' );
		$limit_attr = explode( ',', $field_props[ 'limits_attr' ] );
		$limit_attr = (int) $limit_attr[ 0 ];

		if ( in_array( $limit, $limit_arr ) && $limit_attr > 0 )
			$required = true;
	}

	return $required;
}

/**
 * Print html tags
 *
 * @since 1.1
 * @param array $input
 */
function acf_tags_html( $input ) {

		$name = (isset( $input['name'] )) ? ' name="' . esc_attr( $input['name'] ) . '"' : '';
		$value = ' value="' . esc_attr( $input['value'] ) . '"';
		$id = (isset( $input['id'] )) ? ' id="' . esc_attr( $input['id'] ) . '"' : '';
		$class = (isset( $input['class'] )) ? esc_attr( $input['class'] ) . ' ' : '';
		$other_attr = '';

		if ( isset( $input['format'] ) && $input['format'] != '' )
			$class .= esc_attr( $input['format'] ) . ' ';

		if ( isset( $input['limits'] ) && isset( $input['limits_attr'] ) && $input['limits'] != '' && $input['limits_attr'] != '' )
			$other_attr .= ' ' . esc_attr( $input['limits'] ) . '="' . esc_attr( $input['limits_attr'] ) . '"';

		if ( isset( $input['tip'] ) && '' != $input['tip'] )
			$other_attr .= ' tip="' . esc_attr( $input['tip'] ) . '"';

		switch ( $input['type'] ) {

			case 'drop-down':

				echo '<select class="' . $class . 'dropdownlist"' . $name, $id, $other_attr . '>';

				if ( isset( $input['pls_select'] ) )
					echo $input['pls_select'];

				foreach ( $input['values'] as $key ) {
					if ( ! is_array( $key ) )
						$key = array( 'value' => $key, 'title' => $key );
					echo '<option value="' . trim( esc_attr( $key['value'] ) ) . '" ' . selected( trim( $key['value'] ), trim( $input['value'] ), false ) . '>' . esc_html( $key['title'] ) . '</option>';
				}
				echo '</select>';
				break;

			case 'checkbox':

				if ( !isset( $input['values'] ) ) {
					echo '<input type="checkbox"' . checked( $input['value'], 'yes', false ), $name, $id, $other_attr . ' value="yes" class="' . $class . 'checkboxlist" />';
					break;
				}

				$optionCursor = 1;
				echo '<ol class="checkboxes">';

				foreach ( $input['values'] as $option ) {
					if ( ! is_array( $option ) )
						$option = array( 'value' => $option, 'title' => $option );
					echo '<li>';
					echo '<input type="checkbox"' . acf_checked( $option['value'], $input['value'] ), $other_attr . ' name="' . esc_attr( $input['name'] ) . '[]" value="' . trim( esc_attr( $option['value'] ) ) . '" id="' . esc_attr( $input['id'] ) . '_' . $optionCursor++ . '" class="' . $class . 'checkboxlist" />&nbsp;&nbsp;&nbsp;' . stripslashes( appthemes_make_clickable( trim( $option['title'] ) ) );
					echo '</li>';
				}
				echo '</ol>';
				break;

			case 'text area':

				echo '<textarea rows="' . esc_attr( $input['rows'] ) . '" cols="' . esc_attr( $input['cols'] ) . '" class="' . $class . 'text"' . $name, $id, $other_attr . '>' . esc_html( stripslashes( $input['value'] ) ) . '</textarea>';
				break;

			case 'radio':
				$optionCursor = 1;
				echo '<ol class="radios">';
				if ( !isset( $input['required'] ) ) {
					echo '<li>';
					echo '<input type="radio"' . $name, $other_attr . ' id="' . esc_attr( $input['id'] ) . '_' . $optionCursor++ . '" class="' . $class . 'radiolist" checked="checked" value=""/>';
					_e( 'None', APP_TD );
					echo '</li>';
				}
				foreach ( $input['values'] as $option ) {
					if ( ! is_array( $option ) )
						$option = array( 'value' => $option, 'title' => $option );
					echo '<li>';
					echo '<input ' . checked( trim( $option['value'] ), trim( $input['value'] ), false ) . ' type="radio"' . $name, $other_attr . ' value="' . trim( esc_attr( $option['value'] ) ) . '" id="' . esc_attr( $input['id'] ) . '_' . $optionCursor++ . '" class="' . $class . 'radiolist" />&nbsp;&nbsp; ' . stripslashes( appthemes_make_clickable( trim( $option['title'] ) ) );
					echo '</li>';
				}
				echo '</ol>';
				break;

			case 'span':

				echo '<span class="' . trim( $class ) . '"' . $other_attr . '>' . esc_html( $input['value'] ) . '</span>';
				break;

			default:
				echo '<input type="text" class="' . $class . 'text"' . $name, $value, $id, $other_attr . '/>';
				break;
		}
	}

function acf_call_validator() {
    require_once( ACF_DIR . '/framework/acf/class-acf-validator.php' );
	return apply_filters( 'acf_validator', new ACF_Validator( get_option( 'acf_error_msgs' ), ACF_TD ) );
}

function acf_re_action( $tag, $function_to_remove, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	remove_action( $tag, $function_to_remove, $priority );
	add_action( $tag, $function_to_add, $priority, $accepted_args );
}

function acf_re_filter( $tag, $function_to_remove, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	remove_filter( $tag, $function_to_remove, $priority );
	add_filter( $tag, $function_to_add, $priority, $accepted_args );
}
