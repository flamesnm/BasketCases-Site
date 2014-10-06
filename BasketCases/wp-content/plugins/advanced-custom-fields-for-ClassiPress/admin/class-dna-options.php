<?php
/**
 * Plugin options by default
 *
 * @author Artem Frolov (dikiyforester)
 * @version 1.0
 * @package dna
 */
class DNA_Options {
	static $default_config = array(
		'acf_delete' => '',
		'acf_profile_fields' => array(),
		'acf_error_msgs' => array(),
		'acf_ad_fields' => array(),
		'acf_date_picker' => array(),
	);

	static function get_default_config(){
		global $wpdb;
		$sql = "SELECT field_name, field_label "
				. "FROM " . $wpdb->prefix . "cp_ad_fields "
				. "ORDER BY field_name desc";
		$cp_ad_fields = $wpdb->get_results( $sql );
		$acf_ad_fields = array();

		foreach ( $cp_ad_fields as $cp_ad_field ) {
			$acf_ad_fields[ $cp_ad_field->field_name ] = array(
				'new_ad_display' => 'yes',
				'edit_ad_display' => 'yes',
				'single_ad_display' => 'yes'
			);
		}

		$acf_ad_fields['cp_country']['default'] = 'user_country';
		$acf_ad_fields['cp_state']['default'] = 'user_state';
		$acf_ad_fields['cp_city']['default'] = 'user_city';
		$acf_ad_fields['cp_zipcode']['default'] = 'user_zipcode';
		$acf_ad_fields['cp_street']['default'] = 'user_street';

		self::$default_config['acf_ad_fields'] = $acf_ad_fields;
		self::$default_config['acf_profile_fields'] = array(
			'user_country' => array(
				'type' => 'drop-down',
				'values' => 'cp_country',
				'format' => 'required',
				'limits' => 'maxlength',
				'limits_attr' => '100',
				'title' => 'Country',
				'description' => 'Select your country'
			),
			'user_state' => array(
				'type' => 'drop-down',
				'values' => 'cp_state',
				'format' => 'required',
				'limits' => 'maxlength',
				'limits_attr' => '100',
				'title' => 'State',
				'description' => 'Select your state'
			),
			'user_city' => array(
				'type' => 'text_box',
				'limits' => 'rangelength',
				'limits_attr' => '1,100',
				'title' => 'City',
				'description' => 'Enter your city'
			),
			'user_zipcode' => array(
				'type' => 'text_box',
				'format' => 'digits',
				'limits' => 'rangelength',
				'limits_attr' => '1,6',
				'title' => 'Zip/Postal code',
				'description' => 'Enter your Zip/Postal code'
			),
			'user_street' => array(
				'type' => 'text_box',
				'limits' => 'rangelength',
				'limits_attr' => '1,100',
				'title' => 'Street',
				'description' => 'Enter your street'
			),
			'user_office' => array(
				'type' => 'text_box',
				'limits' => 'rangelength',
				'limits_attr' => '1,100',
				'title' => 'Office / Apartament',
				'description' => 'Enter your office or apartament'
			),
			'user_phone_number' => array(
				'type' => 'text_box',
				'format' => 'phone',
				'title' => 'Phone number',
				'description' => 'Enter your phone number'
			),
			'user_age' => array(
				'type' => 'text_box',
				'format' => 'integer',
				'limits' => 'min',
				'limits_attr' => '18',
				'title' => 'Age',
				'description' => 'Enter your Age'
			),
			'user_tax_id' => array(
				'type' => 'text_box',
				'format' => 'required',
				'limits' => 'maxlength',
				'limits_attr' => '15',
				'title' => 'Tax ID',
				'description' => 'Enter your Tax ID (This field will able to see only you and administrator)',
				'protected' => 'yes'
			),
			'event_date' => array(
				'type' => 'text_box',
				'format' => 'dateCustom',
				'limits' => 'maxlength',
				'limits_attr' => '50',
				'title' => 'Event Date',
				'description' => 'Select your Event date'
			),
			'event_description' => array(
				'type' => 'text area',
				'limits' => 'maxlength',
				'limits_attr' => '1000',
				'title' => 'Event description',
				'description' => 'Provide some details on your event'
			),
			'type_of_owner' => array(
				'type' => 'radio',
				'values' => 'Individual,Corporation,Disregarded entity,Partnership,Simple trust,Grantor trust,Complex trust,Estate,Government,International organization,Central bank of issue,Tax-exempt organization,Private foundation',
				'default' => 'Individual',
				'format' => 'required',
				'limits' => 'maxlength',
				'limits_attr' => '100',
				'title' => 'Type of beneficial owner',
				'description' => 'Select type of beneficial owner'
			),
			'user_offer' => array(
				'type' => 'checkbox',
				'values' => 'Services,Products',
				'default' => 'Products',
				'format' => 'required',
				'limits' => 'maxlength',
				'limits_attr' => '100',
				'title' => 'What do you offer',
				'description' => 'Select what do you offer'
			),
			'accept_terms' => array(
				'type' => 'checkbox',
				'values' => 'I accept the terms of service',
				'limits' => 'maxlength',
				'limits_attr' => '100',
				'title' => 'Accept Terms',
				'description' => 'Check if you are accept the terms of service'
			)
		);
		self::$default_config['acf_date_picker'] = array(
			'date_format' => '',
			'custom_format_text' => 'dd.mm.y',
			'locale' => 'en-GB',
			'animation' => 'show',
			'multi_month' => '1',
			'button_bar' => '',
			'menus' => '',
			'minDate' => '',
			'maxDate' => '',
			'yearRange' => 'c-10:c+10',
			'other_dates' => 'yes',
			'icon_trigger' => 'yes'
		);

		return self::$default_config;

	}
}