<?php

/**
 * DNA TABS CONFIGURATION
 *
 * @author Artem Frolov (dikiyforester)
 * @version 1.0
 * @package dna
 */

class DNA_Tab {
	/** Field types */
	protected $field_types = array(
		'text_box',
		'drop-down',
		'checkbox',
		'radio',
		'text area'
	);

	/**
	 * Array of all validation methods of the ACF plugin
	 * Located in class ACF_Validator
	 *
	 * Array param definitions are as follows:
	 * key           = validation method name
	 *   |-args      = the number of arguments which accepts method.
	 *   |-validate  = type of method: the format of the field or its limits.
	 *   |-desc      = method description
	 */
	protected $field_formats = array();
	protected $format = array();
	protected $limits = array();
	protected $js_vars = array();
	public $tab = '';
	public $properties = array();
	public $subtabs = array();

	public function __construct( $tab ) {
		$this->tab = $tab;
	}

	protected function get_field_formats() {
		if( empty(  $this->field_formats  ) ) {
			$validator = acf_call_validator();
			$this->field_formats = $validator->get_methods();
		}
		return $this->field_formats;
	}

	protected function get_format_names( $method_type ) {
		if( empty(  $this->$method_type  ) ) {
			$validator = acf_call_validator();
			$this->format = array_keys( $validator->get_methods('format') );
			$this->limits = array_keys( $validator->get_methods('limit') );
		}
		return $this->$method_type;
	}

	protected function get_select_values( $sel_field ) {
		switch ( $sel_field ) {
			case 'format':
			case 'limits':
				return $this->get_format_names( $sel_field );
			case 'type':
				return $this->field_types;
			case 'transform':
				return array(
					'Capitalize',
					'Uppercase',
					'Lowercase'
				);
			default:
				return ( isset( $this->properties[$sel_field]['values'] ) ) ? $this->properties[$sel_field]['values'] : false ;
		}
	}

	public function register_js_var( $handle, $var = '' ) {
		$this->js_vars[ $handle ] = $var;
	}
	public function register_script( $src = '' ) {
		$this->js_vars[ 'dna_tab_scripts' ][] = $src;
	}

	public function register_js() {}

	public function print_js_var() {
		$tabscript = dirname( __FILE__ ) . '/js/acf-' . $this->tab . '-script.js';
		if ( file_exists( $tabscript ) )
			$this->register_script( plugin_dir_url( __FILE__ ) . 'js/acf-' . $this->tab . '-script.js' );

		if ( empty( $this->js_vars ) )
			return;

			$send_to_js = '';
			foreach ( $this->js_vars as $name => $js_var ) {
				$json_var = json_encode( $js_var );
				$send_to_js .=  "var " . esc_js( $name ) . " = " . $json_var . ";\n";
			}
			// print script
			print "<script type='text/javascript'>\n";
			print "/* <![CDATA[ */\n";
			print $send_to_js;
			print "/* ]]> */\n";
			print "</script>\n";
	}
}