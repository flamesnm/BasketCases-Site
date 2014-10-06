<?php
/**
 * Here placed profile formbuilders
 *
 * @since 1.1.3
 */

add_filter( 'acf_form_profile_field', 'acf_location_filter', 10, 3 );
add_filter( 'acf_save_profile_field', 'acf_location_filter', 10, 3 );
add_filter( 'acf_save_profile_field', 'acf_transform_filter', 99 );
add_filter( 'acf_check_profile_field', 'acf_location_filter', 10, 3 );
/**
 * Returns an array list of values ​​for dropdowns, checkboxes and radio buttons.
 * If '$field_values' is the name of the ad field, return a list of values ​​for this field.
 *
 * @since 1.0
 * @global type $wpdb
 * @param type $field_values
 * @return array||boolean
 */
function acf_dropdown_values( $field_values ) {
	global $wpdb;
	if ( !$field_values )
		return false;
	else
		$field_values = esc_sql( $field_values );

	$ad_fields = $wpdb->get_results( "SELECT field_name, field_values FROM " . $wpdb->cp_ad_fields . " WHERE field_name = '" . $field_values . "';" );

	if ( $ad_fields )
		$field_values = $ad_fields[0]->field_values;

	$field_values = explode( ',', $field_values );
	return $field_values;
}
add_filter( 'acf_field_values', 'acf_dropdown_values' );

/**
 * Print extra  profile fields on the registration form.
 * Uses action hook 'register_form'
 *
 * @since 1.0
 * @global array $acf_posted
 */
function acf_registration_form() {
	global $acf_posted;

	//'reg_form_display'
	if ( ! $acf_posted )
		$acf_posted = get_option( 'acf_profile_fields' );
	if ( empty( $acf_posted ) )
		return false;
	// load profile field class
    require_once( ACF_DIR . '/framework/acf/class-acf-profile-field.php' ); ?>

	<div class="acfform">
		<?php foreach ( $acf_posted as $field_name => $profile_field ){
				$profile_field = apply_filters( 'acf_form_profile_field', $profile_field, $field_name, 'reg_form_display' );
				if ( ! $profile_field ){
					unset ( $acf_posted[ $field_name ] );
					continue;
				}
				$field = new ACF_Profile_Field( $field_name, $profile_field ); ?>
				<div class="clr"></div>
				<div class="rowwrap">
					<label><?php echo esc_html( $field->title ); ?>: <?php if ( $field->args['required'] ) { ?>
						<span class="colour">*</span><?php } ?>
					</label>
					<?php acf_tags_html( $field->args ); ?>
				</div>
				<?php if ( $field->args['type'] == 'drop-down' ) { ?>
					<br />
				<?php } ?>
				<span class="description acffields"><?php echo stripslashes( $field->description ); ?></span>
		<?php } ?>
	</div>
	<?php
}
add_action( 'register_form', 'acf_registration_form', 9 );


/**
 * Adds extra fields on edit profile form.
 * Reworked function 'cp_profile_fields($user)'.
 * Uses action hook 'show_user_profile' and 'edit_user_profile'
 *
 * @since 1.0
 * @param type $user
 */
function acf_edit_profile_form( $user ) {
	global $pagenow, $acf_posted;
	// no filter option if it runs in admin area or it's already filtered
	if ( ! $acf_posted )
		$acf_posted = get_option( 'acf_profile_fields' );
	if ( empty( $acf_posted ) )
		return false;
	if ( 'user-edit.php' === $pagenow || 'profile.php' === $pagenow )
		$location = 'edit_profile_admin';
	else
		$location = 'edit_profile_display';
    // load profile field class
    require_once( ACF_DIR . '/framework/acf/class-acf-profile-field.php' ); ?>

	<table class="form-table">
		<?php foreach ( $acf_posted as $field_name => $profile_field ) {
			$profile_field = apply_filters( 'acf_form_profile_field', $profile_field, $field_name, $location );
			if ( ! $profile_field ){
				unset ( $acf_posted[ $field_name ] );
				continue;
			}
			$field = new ACF_Profile_Field( $field_name, $profile_field, $user->ID ); ?>
			<tr id="<?php echo esc_attr( $field_name );?>_row">
				<th>
					<label for="<?php echo esc_attr( $field_name ) ;?>">
						<?php echo esc_html( $field->title ); ?>: <?php if ( $field->args['required'] ) { ?>
							<span class="colour">*</span>
						<?php } ?>
					</label>
				</th>
				<td>
					<?php acf_tags_html( $field->args ); ?>
					<div class="clr"></div>
					<span class="description">
						<?php echo stripslashes( $field->description ); ?>
					</span>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php

}
add_action( 'show_user_profile', 'acf_edit_profile_form', 9 );
add_action( 'edit_user_profile', 'acf_edit_profile_form', 9 );


/**
 * Save the ACF user profile fields
 * Uses action hook 'personal_options_update', 'edit_user_profile_update' and 'user_register'
 *
 * @since 1.0
 * @param type $user_id
 * @return boolean
 */
function acf_profile_fields_save( $user_id ) {
	global $pagenow, $acf_posted, $acf_location;
	$edit_user = acf_is_edit_profile_action();
	$value = '';

	if( ! $acf_location )
		if ( 'user-edit.php' === $pagenow || 'profile.php' === $pagenow )
			$acf_location = 'edit_profile_admin';
		else
			$acf_location = 'edit_profile_display';

	if ( ( $edit_user || $pagenow == 'user-edit.php' ) && !current_user_can( 'edit_user', $user_id ) )
		return false;
	// no filter option if it runs in admin area or it's already filtered
	if ( ! $acf_posted )
		$acf_posted = get_option( 'acf_profile_fields' );

	foreach ( $acf_posted as $field_id => $field_values ) {
		$field_values['value'] = ( isset( $_POST[ $field_id ] ) ) ? $_POST[ $field_id ] : '';
		$field_values = apply_filters( 'acf_save_profile_field', $field_values, $field_id, $acf_location );

		if ( ! $field_values )
			continue;

		$value = $field_values['value'];
		if ( is_array( $value ) )
			$value = stripslashes( strip_tags( implode( ",", $value ) ) );
		elseif ( $field_values['type'] == 'textarea' )
			$value = stripslashes( nl2br( $value ) );
		else
			$value = stripslashes( strip_tags( $value ) );

		do_action( 'acf_update_user_meta', $value, $field_id, $user_id );
		update_user_meta( $user_id, $field_id, $value );
	}
}
add_action( 'user_register', 'acf_profile_fields_save' );
add_action( 'personal_options_update', 'acf_profile_fields_save' );
add_action( 'edit_user_profile_update', 'acf_profile_fields_save' );


/**
 * Executes validation and returns an error during user registration
 * Uses action hook 'registration_errors' and 'user_profile_update_errors'
 *
 * @todo check ad fields before saving and return errors if needed
 * @since 1.0
 * @global type $acf_posted
 * @global type $pagenow
 * @param type $errors
 * @return obgect
 */
function acf_check_fields( $errors ) {
	global $pagenow, $acf_posted, $acf_location;
	$edit_user = acf_is_edit_profile_action();
	$add_user = is_page_template( 'tpl-registration.php' );
	$error_msgs = get_option( 'acf_error_msgs' );

	if ( empty( $acf_posted ) )
		$acf_posted = get_option( 'acf_profile_fields' );

	if ( $add_user )
		$acf_location = 'reg_form_display';
	elseif ( $edit_user )
		$acf_location = 'edit_profile_display';
	elseif ( 'user-edit.php' === $pagenow || 'profile.php' === $pagenow )
		$acf_location = 'edit_profile_admin';
	else
		return $errors;

    // load validation class. If not exists - processing are not allowed
	$validator = acf_call_validator();
	if ( ! $validator ) {
		$errors->add( 'validator_lost', '<strong>' . __( 'ERROR', APP_TD ) . 'Validator not detected on server side. Processing are not allowed!' );
		return $errors;
	}

	foreach ( $acf_posted as $field_name => $field ) {
		$field = apply_filters( 'acf_check_profile_field', $field, $field_name, $acf_location );
		if ( ! $field )
			continue;
		$field_title = (isset( $field['title'] ) && $field['title'] ) ? esc_html( $field['title'] ) : ucfirst( str_replace( '_', ' ', $field_name ) );
		$methods = array( 'format', 'limits' );
		$posted = ( isset( $_POST[ $field_name ] ) ) ? $_POST[ $field_name ] : false;

		foreach ( $methods as $method ) {
			if ( isset( $field[ $method ] ) && ! $validator->is_valid( $field[ $method ], $posted, $field[ 'limits_attr' ] ) )
				$errors->add( $method . '_' . $field_name, '<strong>' . __( 'ERROR', APP_TD ) . '</strong>: ' . $field_title . ' - ' . $validator->get_message() );
		}
	}
	return $errors;
}
add_filter( 'registration_errors', 'acf_check_fields' );
add_action( 'user_profile_update_errors', 'acf_check_fields' );

function acf_save_url_on_user_registration( $user_id ) {

	$user_info = get_userdata( $user_id );

	if ( empty( $user_info->user_url ) && ! isset( $_POST['user_url'] ) )
		return;

	$url = esc_url_raw( $_POST['user_url'] );

	wp_update_user( array ( 'ID' => $user_id, 'user_url' => $url ) );

}
add_action( 'user_register', 'acf_save_url_on_user_registration', 99 );