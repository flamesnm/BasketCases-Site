<?php

/**
 * Here placed functions for enqueue styles and js
 *
 * @since 1.1
 */

/**
 * Loads styles only on neccessary pages
 */
function acf_enqueue_css() {

	if ( is_page_template( 'tpl-add-new.php' ) || is_page_template( 'tpl-profile.php' ) || is_page_template( 'tpl-edit-item.php' ) || is_page_template( 'tpl-registration.php' ) || is_page_template( 'tpl-login.php' ) ) {

		wp_enqueue_style( 'jqueryUI_datepicker_style', ACF_URL . '/css/jquery-ui-1.8.21.datepicker.css', 99 );

		if ( is_page_template( 'tpl-registration.php' ) || is_page_template( 'tpl-login.php' ) ) {

			// supported child themes
			$childs = array(
				'Headline Blue - Classipress Child Theme' => 'headline',
				'Headline Green - Classipress Child Theme' => 'headline',
				'Headline Orange - Classipress Child Theme' => 'headline',
				'Headline Purple - Classipress Child Theme' => 'headline',
				'Headline Red - Classipress Child Theme' => 'headline'
			);

			$theme = wp_get_theme();
			$themename = $theme->name;
			$stylesheet = $theme->get_stylesheet();
			$theme_id = array_key_exists( $themename, $childs ) ? $childs[ $themename ] : $stylesheet;
			$filename =  '/css/acf-' . $theme_id  . '-reg.css';

			if ( file_exists( ACF_DIR . $filename ) )
				wp_enqueue_style( 'acf_forms_style', ACF_URL . $filename, 99 );
			else
				wp_enqueue_style( 'acf_forms_style', ACF_URL . '/css/acf-default-reg.css', 99 );
		}

		if ( is_page_template( 'tpl-edit-item.php' ) ) {

			wp_register_style( 'acf-edit-item', ACF_URL . '/css/acf-edit-item.css', array( 'at-color' ) );
			wp_enqueue_style( 'acf-edit-item' );
		}

		do_action( 'acf_enqueue_form_styles' );

	} elseif ( is_singular( 'ad_listing' ) ) {
		$theme = wp_get_theme();
		$themename = $theme->name;
		$filename = ACF_URL . '/css/acf-' . $themename . '-single-ad.css';
		if ( file_exists( $filename ) )
			wp_enqueue_style( 'acf_forms_style', $filename, 99 );
		else
			wp_enqueue_style( 'acf_forms_style', ACF_URL . '/css/acf-default-single-ad.css', 99 );

		do_action( 'acf_enqueue_listing_styles' );
	}
}
add_action( 'wp_print_styles', 'acf_enqueue_css', 99 );


/**
 * Loads scripts only on neccessary pages
 */
function acf_enqueue_js() {

	if ( is_page_template( 'tpl-add-new.php' ) || is_page_template( 'tpl-profile.php' ) || is_page_template( 'tpl-edit-item.php' ) || is_page_template( 'tpl-registration.php' ) || is_page_template( 'tpl-login.php' ) ) {

		// send arrays to js
		$msgs_arr = get_option( 'acf_error_msgs' );
		$date_obj = get_option( 'acf_date_picker' );
		if ( 'yes' === $date_obj['icon_trigger'] )
			$date_obj['buttonImage'] = get_template_directory_uri() . '/images/calendar.gif';

		if ( is_page_template( 'tpl-registration.php' )  || is_page_template( 'tpl-login.php' ) ) {
			wp_enqueue_script( 'validate' );
			wp_enqueue_script( 'acf_script', ACF_URL . '/js/acf-registration.js', array('validate') );
		}

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jqueryUI_locale_script', ACF_URL . '/js/jquery-ui-i18n.min.js', array('jquery-ui-datepicker') );
		wp_enqueue_script( 'acf_datepicker_script', ACF_URL . '/js/acf-date-picker.js', array('jqueryUI_locale_script') );

		if ( ! defined( 'CP_VERSION' ) )
			wp_enqueue_script( 'morevalidate', get_template_directory_uri() . '/includes/js/validate/additional-methods.js', array('validate') );
		else
			wp_enqueue_script( 'morevalidate', get_template_directory_uri() . '/framework/js/validate/additional-methods.js', array('validate') );

		wp_enqueue_script( 'ValidateFix_script', ACF_URL . '/js/ValidateFix.js', array('morevalidate') );


		if ( is_page_template( 'tpl-profile.php' ) || is_page_template( 'tpl-registration.php' ) || is_page_template( 'tpl-login.php' ) )
			wp_enqueue_script( 'acf_validation', ACF_URL . '/js/acf-validation.js', array( 'theme-scripts' ) );

		wp_localize_script( 'validate', 'validate_msgs', $msgs_arr );
		wp_localize_script( 'jquery-ui-datepicker', 'dateOptions', $date_obj );
		do_action( 'acf_enqueue_form_scripts' );
	}
}
add_action( 'wp_print_scripts', 'acf_enqueue_js', 99 );
