<?php
/**
 * Compatibility layer beyween ACFCP and WPML
 */
add_filter( 'acf_display_ad_field', 'acf_wpml_display_ad_field', 999, 3 );
add_filter( 'acf_form_profile_field', 'acf_wpml_form_profile_field', 999, 2 );
add_filter( 'acf_display_profile_field', 'acf_wpml_display_profile_field', 999, 2 );
add_filter( 'acf_field_values', 'acf_wpml_profile_field_options', 999, 2 );
add_filter( 'option_acf_error_msgs', 'acf_wpml_validation_msgs' );
add_filter( 'option_acf_date_picker', 'acf_wpml_date_picker' );


function acf_wpml_display_ad_field( $props, $name, $location ) {
	if ( ! $props )
		return false;

	if ( 'single_ad_cont' === $location )
		$props['label'] = icl_translate( APP_TD, 'label_' . $props['label'], $props['label'] );

	return $props;
}

function acf_wpml_profile_field_options( $options, $name ) {
	if ( ! empty( $options ) ) {
		$new_options = array();
		foreach ( $options as $key => $option ) {
			$new_options[ $key ]['value'] = $option;
			$new_options[ $key ]['title'] = icl_translate( ACF_TD, 'value_' . $name . ' ' . trim( $option ), $option );
		}
	}
	return $new_options;
}

function acf_wpml_form_profile_field( $props, $name ) {
	if ( ! $props )
		return false;

	if ( isset( $props['title'] ) )
		$props['title'] = icl_translate( ACF_TD, 'title_' . $name, $props['title'] );

	if ( isset( $props['description'] ) )
		$props['description'] = icl_translate( ACF_TD, 'description_' . $name, $props['description'] );

	return $props;
}

function acf_wpml_display_profile_field ( $props, $name ) {
	if ( ! $props )
		return false;

	if ( isset( $props['title'] ) )
		$props['title'] = icl_translate( ACF_TD, 'title_' . $name, $props['title'] );

	if ( isset( $props['values'] ) && ! empty( $props['values'] ) && ! empty( $props['value'] ) ){
		$values = array_map( 'trim', explode( ',', $props['value'] ) );
		foreach ( $values as $key => $value ) {
			$values[ $key ] = icl_translate( ACF_TD, 'value_' . $name . ' ' . $value, $value );
		}
		$props['value'] = implode( ', ', $values );
	}

	return $props;
}

function acf_wpml_validation_msgs( $msgs ) {
	if ( is_array( $msgs ) ) {
		foreach ( $msgs as $key => $msg ) {
			$msgs[ $key ] = icl_translate( ACF_TD, 'message_' . $key, $msg );
		}
	}
	return $msgs;
}

function acf_wpml_date_picker( $settings ) {
	// Well, JS and WPML might have different language codes, so to resolve this
	// Administrator should forcibly translate default js language code for each used languages
	// Item in String Translation table is 'js_lang_code_[default datepicker language code]'
	if ( is_array( $settings ) && isset( $settings['locale'] )  ) {
		$code = icl_translate( ACF_TD, 'js_lang_code_' . $settings['locale'], $settings['locale'] );
		$settings['locale'] = $code;
	}
	return $settings;
}