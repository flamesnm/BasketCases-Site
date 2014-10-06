<?php
/*
 * It is home to the functions, which I want to get rid of,
 * but they are required to support earlier versions of ClassiPress
 */

//cp_get_ad_details()
if ( ( '3.2.1' === get_option( 'cp_version' ) || '3.2' === get_option( 'cp_version' ) ) && !function_exists( 'cp_get_ad_details' ) ) {
/**
 * called in cp_get_ad_details() to hook before ad details
 *
 * @since 3.3
 * @param object $form_fields
 * @param object $post
 * @param string $location
 *
 */
	function cp_action_before_ad_details( $form_fields, $post, $location ) {
		do_action( 'cp_action_before_ad_details', $form_fields, $post, $location );
	}
	/**
	 * called in cp_get_ad_details() to hook after ad details
	 *
	 * @since 3.3
	 * @param object $form_fields
	 * @param object $post
	 * @param string $location
	 *
	 */
	function cp_action_after_ad_details( $form_fields, $post, $location ) {
		do_action( 'cp_action_after_ad_details', $form_fields, $post, $location );
	}
/**
 * Remake cp_get_ad_details function.
 * display ONLY NECESSARY custom fields on the single ad page, by default they are placed in the list area
 *
 * @global object $wpdb
 * @param type $post_id
 * @param type $category_id
 * @param type $location
 * @return type
 */
	function cp_get_ad_details( $post_id, $category_id, $location = 'list' ) {
		global $wpdb;

		// see if there's a custom form first based on category id.
		$form_id = cp_get_form_id( $category_id );

		$post = get_post( $post_id );
		if ( ! $post )
			return;

		// if there's no form id it must mean the default form is being used
		if ( ! $form_id ) {

			// get all the custom field labels so we can match the field_name up against the post_meta keys
			$sql = "SELECT field_label, field_name, field_type FROM $wpdb->cp_ad_fields";

		} else {

			// now we should have the formid so show the form layout based on the category selected
			$sql = $wpdb->prepare( "SELECT f.field_label, f.field_name, f.field_type, m.field_pos FROM $wpdb->cp_ad_fields f "
				. "INNER JOIN $wpdb->cp_ad_meta m ON f.field_id = m.field_id WHERE m.form_id = %s ORDER BY m.field_pos ASC", $form_id );

		}

		$results = $wpdb->get_results( $sql );

		if ( ! $results ) {
			_e( 'No ad details found.', APP_TD );
			return;
		}

		// allows to hook before ad details
		cp_action_before_ad_details( $results, $post, $location );

		foreach ( $results as $result ) {

			// external plugins can modify or disable field
			$result = apply_filters( 'cp_ad_details_field', $result, $post, $location );
			if ( ! $result )
				continue;

			$disallow_fields = array( 'cp_price', 'cp_currency' );
			if ( in_array( $result->field_name, $disallow_fields ) )
				continue;

			$post_meta_val = get_post_meta( $post->ID, $result->field_name, true );
			if ( empty( $post_meta_val ) && "0" !== $post_meta_val )
				continue;

			if ( $location == 'list' ) {
				if ( $result->field_type == 'text area' )
					continue;

				if ( $result->field_type == 'checkbox' ) {
					$post_meta_val = get_post_meta( $post->ID, $result->field_name, false );
					$post_meta_val = implode( ", ", $post_meta_val );
				}

				$args = array( 'value' => $post_meta_val, 'label' => $result->field_label, 'id' => $result->field_name, 'class' => '' );
				$args = apply_filters( 'cp_ad_details_' . $result->field_name, $args, $result, $post, $location );

				if ( $args )
					echo '<li id="' . $args['id'] . '" class="' . $args['class'] . '"><span>' . esc_html( translate( $args['label'], APP_TD ) ) . ':</span> ' . appthemes_make_clickable( $args['value'] ) . '</li>';

			} elseif ( $location == 'content' ) {
				if ( $result->field_type != 'text area' )
					continue;

				$args = array( 'value' => $post_meta_val, 'label' => $result->field_label, 'id' => $result->field_name, 'class' => 'custom-text-area dotted' );
				$args = apply_filters( 'cp_ad_details_' . $result->field_name, $args, $result, $post, $location );

				if ( $args )
					echo '<div id="' . $args['id'] . '" class="' . $args['class'] . '"><h3>' . esc_html( translate( $args['label'], APP_TD ) ) . '</h3> ' . appthemes_make_clickable( $args['value'] ) . '</div>';

			}
		}

		// allows to hook after ad details
		cp_action_after_ad_details( $results, $post, $location );
	}
}


//cp_formbuilder()
if ( '3.2' === get_option( 'cp_version' ) ) {

	// loops through the custom fields and builds the custom ad form
	if ( !function_exists('cp_formbuilder') ) {
		function cp_formbuilder($results, $post = false) {
			global $wpdb, $cp_options;

			$custom_fields_array = array();

			foreach ( $results as $result ) {

				// external plugins can modify or disable field
				$result = apply_filters( 'cp_formbuilder_field', $result, $post );
				if ( ! $result )
					continue;

				if ( appthemes_str_starts_with( $result->field_name, 'cp_' ) )
					$custom_fields_array[] = $result->field_name;
				$post_meta_val = ( $post ) ? get_post_meta($post->ID, $result->field_name, true) : false;
		?>

				<li id="list_<?php echo esc_attr($result->field_name); ?>">
					<div class="labelwrapper">
						<label><?php if ( $result->field_tooltip ) { ?><a href="#" tip="<?php echo esc_attr( translate( $result->field_tooltip, APP_TD ) ); ?>" tabindex="999"><div class="helpico"></div></a><?php } ?><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>: <?php if ( $result->field_req ) echo '<span class="colour">*</span>'; ?></label>
						<?php if ( ($result->field_type) == 'text area' && ( $cp_options->allow_html ) ) { // only show this for tinymce since it's hard to position the error otherwise ?>
							<br /><label class="invalid tinymce" for="<?php echo esc_attr($result->field_name); ?>"><?php _e( 'This field is required.', APP_TD ); ?></label>
						<?php } ?>
					</div>
					<?php

						switch ( $result->field_type ) {

							case 'text box':

								if ( isset($_POST[$result->field_name]) ) {
									$value = appthemes_clean( $_POST[$result->field_name] );
								} elseif ( $result->field_name == 'post_title' && $post ) {
									$value = $post->post_title;
								} elseif ( $result->field_name == 'tags_input' && $post ) {
									$value = rtrim(trim(cp_get_the_term_list($post->ID, APP_TAX_TAG)), ',');
								} else {
									$value = $post_meta_val;
								}

								$field_class = ( $result->field_req ) ? 'text required' : 'text';
								$field_minlength = ( empty( $result->field_min_length ) ) ? '2' : $result->field_min_length;
								$args = array( 'value' => $value, 'name' => $result->field_name, 'id' => $result->field_name, 'type' => 'text', 'class' => $field_class, 'minlength' => $field_minlength );
								$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );

								echo html( 'input', $args );
								echo html( 'div', array( 'class' => 'clr' ) );

								break;

							case 'drop-down':

								$options = explode( ',', $result->field_values );
								$options = array_map( 'trim', $options );
								$html_options = '';

								$html_options .= html( 'option', array( 'value' => '' ), __( '-- Select --', APP_TD ) );
								foreach ( $options as $option ) {
									$args = array( 'value' => $option );
									if ( $option == $post_meta_val )
										$args['selected'] = 'selected';
									$args = apply_filters( 'cp_formbuilder_' . $result->field_name . '_option', $args, $result, $post );
									$html_options .= html( 'option', $args, $option );
								}

								$field_class = ( $result->field_req ) ? 'dropdownlist required' : 'dropdownlist';
								$args = array( 'name' => $result->field_name, 'id' => $result->field_name, 'class' => $field_class );
								$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );

								echo html( 'select', $args, $html_options );
								echo html( 'div', array( 'class' => 'clr' ) );

								break;

							case 'text area':

								if ( isset($_POST[$result->field_name]) ) {
									$value = appthemes_clean( $_POST[$result->field_name] );
								} elseif ( $result->field_name == 'post_content' && $post ) {
									$value = $post->post_content;
								} else {
									$value = $post_meta_val;
								}

								$field_class = ( $result->field_req ) ? 'required' : '';
								$field_minlength = ( empty( $result->field_min_length ) ) ? '2' : $result->field_min_length;
								$args = array( 'value' => $value, 'name' => $result->field_name, 'id' => $result->field_name, 'rows' => '8', 'cols' => '40', 'class' => $field_class, 'minlength' => $field_minlength );
								$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );
								$value = $args['value'];
								unset( $args['value'] );

								echo html( 'div', array( 'class' => 'clr' ) );
								echo html( 'textarea', $args, esc_textarea( $value ) );
								echo html( 'div', array( 'class' => 'clr' ) );
						?>

								<?php if ( $cp_options->allow_html && ! wp_is_mobile() ) { ?>
									<script type="text/javascript"> <!--
									tinyMCE.execCommand('mceAddControl', false, '<?php echo esc_attr($result->field_name); ?>');
									--></script>
								<?php } ?>

						<?php
								break;

							case 'radio':

								$options = explode( ',', $result->field_values );
								$options = array_map( 'trim', $options );

								$html_radio = '';
								$html_options = '';

								if ( ! $result->field_req ) {
									$args = array( 'value' => '', 'type' => 'radio', 'class' => 'radiolist', 'name' => $result->field_name, 'id' => $result->field_name );
									if ( empty( $post_meta_val ) )
										$args['checked'] = 'checked';
									$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );
									$html_radio = html( 'input', $args ) . '&nbsp;&nbsp;' . __( 'None', APP_TD );
									$html_options .= html( 'li', array(), $html_radio );
								}

								foreach ( $options as $option ) {
									$field_class = ( $result->field_req ) ? 'radiolist required' : 'radiolist';
									$args = array( 'value' => $option, 'type' => 'radio', 'class' => $field_class, 'name' => $result->field_name, 'id' => $result->field_name );
									if ( $option == $post_meta_val )
										$args['checked'] = 'checked';
									$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );
									$html_radio = html( 'input', $args ) . '&nbsp;&nbsp;' . $option;
									$html_options .= html( 'li', array(), $html_radio );
								}

								echo html( 'ol', array( 'class' => 'radios' ), $html_options );
								echo html( 'div', array( 'class' => 'clr' ) );

								break;

							case 'checkbox':

								$post_meta_val = ( $post ) ? get_post_meta($post->ID, $result->field_name, false) : array();
								$options = explode( ',', $result->field_values );
								$options = array_map( 'trim', $options );
								$optionCursor = 1;

								$html_checkbox = '';
								$html_options = '';

								foreach ( $options as $option ) {
									$field_class = ( $result->field_req ) ? 'checkboxlist required' : 'checkboxlist';
									$args = array( 'value' => $option, 'type' => 'checkbox', 'class' => $field_class, 'name' => $result->field_name . '[]', 'id' => $result->field_name . '_' . $optionCursor++ );
									if ( in_array($option, $post_meta_val) )
										$args['checked'] = 'checked';
									$args = apply_filters( 'cp_formbuilder_' . $result->field_name, $args, $result, $post );
									$html_checkbox = html( 'input', $args ) . '&nbsp;&nbsp;' . $option;
									$html_options .= html( 'li', array(), $html_checkbox );
								}

								echo html( 'ol', array( 'class' => 'checkboxes' ), $html_options );
								echo html( 'div', array( 'class' => 'clr' ) );

								break;

						}
						?>

				</li>

		<?php
			}

			// put all the custom field names into an hidden field so we can process them on save
			$custom_fields_vals = implode( ',', $custom_fields_array );
			echo html( 'input', array( 'type' => 'hidden', 'name' => 'custom_fields_vals', 'value' => $custom_fields_vals ) );

			cp_action_formbuilder( $results, $post );
		}
	}

	/**
	 * called in cp_formbuilder() to hook into form builder
	 *
	 * @since 3.2.1
	 * @param object $form_fields
	 * @param object|bool $post
	 *
	 */
	function cp_action_formbuilder( $form_fields, $post ) {
		do_action( 'cp_action_formbuilder', $form_fields, $post );
	}

	/**
	 * Builds the edit ad form on the tpl-edit-item.php page template.
	 *
	 * @deprecated 3.2.1
	 * @deprecated Use cp_formbuilder()
	 * @see cp_formbuilder()
	 */
	if ( ! function_exists('cp_edit_ad_formbuilder') ) {
		function cp_edit_ad_formbuilder( $results, $post ) {
			return cp_formbuilder( $results, $post );
		}
	}
}