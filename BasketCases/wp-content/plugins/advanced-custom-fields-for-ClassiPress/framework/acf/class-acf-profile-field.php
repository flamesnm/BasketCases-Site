<?php
/**
 * Description of acf-class-visibility
 *
 * @author forester
 */
class ACF_Profile_Field {
	public $args = array();
	public $title = '';
	public $description = '';

	public function __construct( $name = '', $values = array(), $user_id = 0 ) {
		if ( isset( $values['title'] ) && $values['title'] != '' )
			$this->title = $values['title'];
		else
			$this->title = ucfirst( str_replace( '_', ' ', $name ) );

		$this->get_args( $name, $values, $user_id );

		if ( isset( $values['description'] ) && $values['description'] != '' )
			$this->description = $values['description'];
	}

	public function get_args( $name, $values, $user_id ) {
		$this->args['required'] = acf_required( $values );

		if ( isset( $values['format'] ) )
			$this->args['format'] = $values['format'];
		if ( isset( $values['limits'] ) )
			$this->args['limits'] = $values['limits'];
		if ( isset( $values['limits_attr'] ) )
			$this->args['limits_attr'] = $values['limits_attr'];

		$this->args['name'] = $name;
		$this->args['id'] = $name;

		if ( isset( $_POST[$name] ) )
			$this->args['value'] = $_POST[$name];
		elseif ( $user_id != 0 )
			$this->args['value'] = get_user_meta( $user_id, $name, true );
		elseif ( isset( $values['default'] ) )
			$this->args['value'] = $values['default'];
		else
			$this->args['value'] = '';

		if ( isset( $values['type'] ) ) {
			$this->args['type'] = $values['type'];

			switch ( $values['type'] ) {
				case 'drop-down':
				case 'radio':
				case 'checkbox':
					if ( ! isset( $values['values'] ) )
						$values['values'] = '';
					$this->args['values'] = apply_filters( 'acf_field_values', $values['values'], $name );
					$this->args['pls_select'] = '<option value="">' . __( '-- Select --', APP_TD ) . '</option>';
					break;
				case 'text area':
					$this->args['rows'] = 8;
					$this->args['cols'] = 40;
				default:
					break;
			}
		}
	}
}
?>