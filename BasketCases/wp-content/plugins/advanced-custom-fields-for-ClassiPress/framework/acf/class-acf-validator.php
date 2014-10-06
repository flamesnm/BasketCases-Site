<?php
/**
 * Here is where ACF PHP validation methods is stored
 *
 * @author Artem Frolov (dikiyforester)
 * @version 1.0
 */
class ACF_Validator {

	/**
	 * Current validation error message
	 * @var string
	 */
	protected $message = '';

	/**
	 * Array of all error messages for validation methods
	 * Must be filled in object creation or before the validation process
	 * @var array
	 */
	protected $err_msgs = array();

	/**
	 * Array of all validation methods of the ACF plugin
	 *
	 * Array param definitions are as follows:
	 * key           = validation method name
	 *   |-args      = the number of arguments which accepts method.
	 *   |-validate  = type of method: the format of the field or its limits.
	 *   |-desc      = method description
	 */
	protected $methods;

	/**
	 * Text domain for internationalization
	 * @var string
	 */
	protected $td;


	public function __construct( $err_msgs = array(), $td = 'dna' ) {
		$this->err_msgs = $err_msgs;
		$this->td = $td;
		$this->methods = $this->default_methods();
	}


	/**
	 * Used for overloading new validation methods
	 *
	 * @param type $name Called method name
	 * @param type $args Sent arguments
	 * @return type
	 * @throws Exception
	 */
	public function __call( $name, $args ) {
		if( array_key_exists( $name, $this->methods ) ) {
			//array_unshift($args, $this);
			return call_user_func_array($this->methods[ $name ]['callback'], $args);
		} else {
			throw new Exception("No registered method called ".__CLASS__."::".$name);
		}
	}


	/**
	 * Add new validation method on the fly
	 *
	 * @param string $name Validation method name. Must be the same with jQuery.Validate brother method name.
	 * @param string $callback Function to process validation. Must be defined outside the class
	 * @param int $args Number of arguments, which accepts method. Default 0.
	 * @param string $type Type of validation: "format" (by default) or "limit"
	 * @param string $desc Method description - used for ToolTips in the backend.
	 * @param string $msg Validation error message by default.
	 * It may contain argument placeholders {0}, {1}, etc.
	 * Where 0, 1 - sequence numbers of method arguments.
	 */
	public function add_method ( $name, $callback, $args = 0, $type = 'format', $desc = '', $msg = '' ) {
		$this->methods[ $name ] = array(
			'args'		=> $args,
			'validate'	=> $type,
			'desc'		=> $desc,
			'callback'	=> $callback
		);
		if ( ! isset( $this->err_msgs[ $name ] )  )
		$this->err_msgs[ $name ] = $msg;
	}

	/**
	 * Return methods array filtered by accessed parameter: format | limit | or empty
	 * if type is empty - return all methods
	 * @param type $type
	 * @return array filtered methods array
	 */
	public function get_methods( $type = '' ) {
		$ret = $this->methods;
		if( ! $type ) {
			return $ret;
		} else {
			foreach ( $ret as $key => $val ) {
				if ( $type !== $val['validate'] )
					unset( $ret[ $key ] );
			}
			return $ret;
		}
	}

	/**
	 * Validate posted values and returns validation result
	 * @param string $method
	 * @param mixed $posted
	 * @param string $param
	 * @return boolean
	 */
	public function is_valid( $method, $posted, $param = '' ) {
		$valid = true;
		//if there is no such method - return true
		if ( ! $method || ! isset( $this->methods[ $method ] ) )
			return $valid;
		//explode string to get param array
		$param = explode( ',', $param );
		$values = array();
		if ( ! is_array( $posted ) )
			$values[] = $posted;
		else
			$values = $posted;

		if ( in_array( $method, array( 'maxchoice', 'minchoice', 'rangechoice' ) ) ) {
			if ( ! $this->$method( $values, $param ) )
				$valid = false;
		} else {
			foreach ( $values as $value ) {
				if ( ! $this->$method( $value, $param ) )
					$valid = false;
			}
		}
		//check validation result
		if ( $valid )
			return $valid;
		//else, prepare error message
		else
			$this->prepare_msg( $method, $param );
		//return false after all
		return false;
	}

	/**
	 * Get prepeared Validation error message
	 * @return string Returns prepeared error message
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Search arguments placeholders in message and replace it with method arguments
	 * @param string $method
	 * @param array $param
	 * @return boolean
	 */
	protected function prepare_msg( $method, $param ) {
		$message = '';
		// if there is no such message - so there is nothing to do
		if ( ! isset( $this->err_msgs[ $method ] ) )
			return false;
		else
			$message = $this->err_msgs[ $method ];
		// trying to replace placeholders with method arguments
		for ($i = 0; $i < count( $param ); $i++) {
			$message = str_replace( '{' . $i . '}', $param[ $i ], $message );
		}
		$this->message = $message;
	}


	protected function default_methods() {
		return array(
		/*  Formats  */
		'email' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require a valid email.', $this->td ) . ' ' . __( 'Works with text inputs.', $this->td )
		),
		'url' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require a valid url.', $this->td ) . ' ' . __( 'Works with text inputs.', $this->td ) . '<br />'
					. '<strong>'. __( 'REQUIRE:', $this->td ) . '</strong>' . __( '"http://" | "https://" | "ftp://" in the beginning of the line.', $this->td ) . '<br />'
		),
		'phone' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require a valid phone number in any style.', $this->td ) . ' ' . __( 'Works with text inputs.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 5555555 | 555-5555 | 555.5555 | 555 5555 | 5555555555 | 555-555-5555 | 555.555.5555 | 555 555 5555 | (555)5555555 | (555)-555-5555 | (555).555.5555 | (555) 555 5555 | +XX5555555555 | +XX-555-555-5555 | +XX.555.555.5555 | +XX 555 555 5555 | +XX(555)5555555 | +XX-(555)-555-5555 | +XX.(555).555.5555 | +XX (555) 555 5555<br />'
		),
		'phoneUS' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require a valid US phone number.  Where the area code may not start with 1 and the prefix may not start with 1. <br />Allows "-" or " " as a separator and allows parens around area code. Some people may want to put a "1" in front of their number.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 1(212)-999-2345 | 212 999 2344 | 212-999-0983<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> 111-123-5434 | 212 123 4567'
		),
		'phone_4_4' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require a format with 2 separeted 4-gigits numbers.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 55555555 | 5555-5555 | 5555.5555 | 5555 5555 <br />'
		),
		'dateCustom' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => sprintf( __( 'Makes the element require a valid date format. You can customize absolutely any date format on the settings tab "%s"', $this->td ), __( 'Date Picker Settings', $this->td ) ) . '<br />'
					. __( 'Works with text inputs.', $this->td ) . '<br />'
					. __( 'For the selected field will be added date picker. <br />And most importantly: <strong>no matter what date format you have set up - plug-in will check the user input value for this format!</strong>', $this->td ) . '<br />'
		),
		'number' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require positive or negative decimal number. Separator can be dot or a comma.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 55,555 | 555.55 | -55,5555 | -5555.55 <br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> 55,5a5 | 55.5,55 etc.'
		),
		'digits' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require digits only.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 555555555<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> 55a5555555 | 5555 555 | 555,5555 etc.'
		),
		'integer' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require positive or negative non-decimal number', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 555555555 | -555555555<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> 55a5555555 | 5555 555 | 555,5555 etc.'
		),
		'numeric_ws' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Matches numbers and whitespaces. Use for i.g. misc. zip-codes, phone-numbers, and other..  where you may not completely know the format.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> 555555555 | 555 555 55 5<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> 55a5555555 | -555 5555 | 5 55,5555 etc.'
		),
		'letterswithbasicpunc' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require only latin letters or punctuation', $this->td ) . '( <span class="args">- . , ( ) &#39;  &quot; </span>).'
		),
		'alphanumeric' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require only latin letters, numbers, or underscores.', $this->td )
		),
		'lettersonly' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element require only latin letters.', $this->td )
		),
		'nowhitespace' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Makes the element do not accept white space.', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> ' . __( 'Simple_characters_set_#156416.', $this->td ) . '<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> ' . __( 'Simple characters set #156416.', $this->td )
		),
		'required' => array(
			'args' => '0',
			'validate' => 'format',
			'desc' => __( 'Jast required element.', $this->td )
		),
		/*  Limits  */
		'maxlength' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given maxmimum length.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'minlength' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given minimum length.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'rangelength' => array(
			'args' => '2',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given range length.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">2</span> comma separated positive integer arguments (minimum & maximum).', $this->td )
		),
		'range' => array(
			'args' => '2',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given value range.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">2</span> comma separated positive integer arguments (minimum & maximum).', $this->td )
		),
		'max' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given maximum.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'min' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given minimum.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'maxWords' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given maximum words.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'minWords' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given minimum words.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'rangeWords' => array(
			'args' => '2',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given range words.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">2</span> comma separated positive integer arguments (minimum & maximum).', $this->td )
		),
		'maxcollocations' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given maximum comma-separated collocations.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'mincollocations' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given minimum comma-separated collocations.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'rangecollocations' => array(
			'args' => '2',
			'validate' => 'limit',
			'desc' => __( 'Makes the element require a given range comma-separated collocations.', $this->td ) . '<br />'
					. __( 'For example, you can use it for <strong>"tags_input"</strong> field and limit number of the tags in range 1-3', $this->td ) . '<br />'
					. '<strong>' . __( 'VALID:', $this->td ) . '</strong> ' . __( 'Tag1 | Tag1, Another tag2 | Tag1, Another tag2, Yet another tag3', $this->td ) . '<br />'
					. '<strong>' . __( 'INVALID:', $this->td ) . '</strong> ' . __( 'Tag1, Another tag2, Yet another tag3, | Tag1, Another tag2, Yet another tag3, And another tag4', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">2</span> comma separated positive integer arguments (minimum & maximum).', $this->td )
		),
		'maxchoice' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the multi select element require given maximum number of choices.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'minchoice' => array(
			'args' => '1',
			'validate' => 'limit',
			'desc' => __( 'Makes the multi select element require given minimum number of choices.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">1</span> positive integer argument.', $this->td )
		),
		'rangechoice' => array(
			'args' => '2',
			'validate' => 'limit',
			'desc' => __( 'Makes the multi select element require given range number of choices.', $this->td ) . '<br />'
					. __( 'Method accepts <span class="args">2</span> comma separated positive integer arguments (minimum & maximum).', $this->td )
		),
	);
	}

	/* Formats */

	protected function required( $posted ) {
		if ( $posted )
			return true;
	}

	protected function email( $posted ) {
		if ( !$posted || is_email( $posted ) )
			return true;
	}

	protected function url( $posted ) {
		if ( !$posted || preg_match( "/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])*([a-z]|\d|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])))\.)+(([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])*([a-z]|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\x{E000}-\x{F8FF}]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\x{00A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/iu", $posted ) )
			return true;
	}

	protected function phone( $posted ) {
		if ( !$posted || preg_match( '/^(([\+]\d{1,3})?[ \.-]?[\(]?\d{3}[\)]?)?[ \.-]?\d{3}[ \.-]?\d{4}$/', $posted ) )
			return true;
	}

	protected function phoneUS( $posted ) {
		$posted = preg_replace( '/\s+/', '', $posted );
		if ( !$posted || mb_strlen( $posted, 'UTF-8' ) > 9 && preg_match( '/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/', $posted ) )
			return true;
	}

	protected function number( $posted ) {
		if ( !$posted || preg_match( '/^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/', $posted ) )
			return true;
	}

	protected function phone_4_4( $posted ) {
		if ( !$posted || preg_match( '/^\d{4}[ \.-]?\d{4}$/', $posted ) )
			return true;
	}

	protected function digits( $posted ) {
		if ( !$posted || preg_match( '/^\d+$/', $posted ) )
			return true;
	}

	protected function integer( $posted ) {
		if ( !$posted || preg_match( '/^-?\d+$/', $posted ) )
			return true;
	}

	protected function numeric_ws( $posted ) {
		if ( !$posted || preg_match( '/^[\s0-9]+$/', $posted ) )
			return true;
	}

	protected function letterswithbasicpunc( $posted ) {
		if ( !$posted || preg_match( "/^[a-z-.,()'\"\s]+$/i", $posted ) )
			return true;
	}

	protected function alphanumeric( $posted ) {
		if ( !$posted || preg_match( '/^\w+$/i', $posted ) )
			return true;
	}

	protected function lettersonly( $posted ) {
		if ( !$posted || preg_match( '/^[a-z]+$/i', $posted ) )
			return true;
	}

	protected function nowhitespace( $posted ) {
		if ( !$posted || preg_match( '/^\S+$/i', $posted ) )
			return true;
	}

	/* Limits */

	protected function max( $posted, $param ) {
		if ( $this->integer( $posted ) && intval( $param[ 0 ] ) && intval( $posted ) <= intval( $param[ 0 ] ) )
			return true;
	}

	protected function min( $posted, $param ) {
		if ( $this->integer( $posted ) && intval( $param[ 0 ] ) && intval( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function range( $posted, $param ) {
		if ( $this->integer( $posted ) && intval( $param[ 0 ] ) && intval( $param[ 1 ] ) && intval( $posted ) >= intval( $param[ 0 ] ) && intval( $posted ) <= intval( $param[ 1 ] ) )
			return true;
	}

	protected function minlength( $posted, $param ) {
		if ( intval( $param[ 0 ] ) && mb_strlen( $posted, 'UTF-8' ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function maxlength( $posted, $param ) {
		if ( intval( $param[ 0 ] ) && mb_strlen( $posted, 'UTF-8' ) <= intval( $param[ 0 ] ) )
			return true;
	}

	protected function rangelength( $posted, $param ) {
		if ( intval( $param[ 0 ] ) && intval( $param[ 1 ] ) && mb_strlen( $posted, 'UTF-8' ) >= intval( $param[ 0 ] ) && mb_strlen( $posted, 'UTF-8' ) <= intval( $param[ 1 ] ) )
			return true;
	}

	protected function striptags( $posted ) {
		return preg_replace( '/<.[^<>]*?>/', '', $posted );
	}

	protected function striphtmlspace( $posted ) {
		return preg_replace( '/&nbsp;|&#160;/i', '', $posted );
	}

	protected function stripnumbersandpunc( $posted ) {
		return preg_replace( '/[0-9.(),;:!?%&#$\'"_+=\/-]*/', '', $posted );
	}

	protected function lettersanddelim( $posted ) {
		return preg_replace( '/\s+/', ',', $this->stripnumbersandpunc( $this->striphtmlspace( $this->stripTags( $posted ) ) ) );
	}

	protected function numwords( $posted ) {
		if ( $this->lettersanddelim( $posted ) )
			return count( explode( ',', $this->lettersanddelim( $posted ) ) );
		else
			return 0;
	}

	protected function maxwords( $posted, $param ) {
		if ( $this->numwords( $posted ) <= intval( $param[ 0 ] ) )
			return true;
	}

	protected function minwords( $posted, $param ) {
		if ( $this->numwords( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function rangewords( $posted, $param ) {
		if ( $this->numwords( $posted ) <= intval( $param[ 1 ] ) && $this->numwords( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function collocationsanddelim( $posted ) {
		return preg_replace( '/[0-9.();:!?%&#$\'"_+=\/-]*/', '', $this->striphtmlspace( $this->stripTags( $posted ) ) );
	}

	protected function numcollocations( $posted ) {
		if ( $this->collocationsanddelim( $posted ) )
			return count( explode( ',', $this->collocationsanddelim( $posted ) ) );
		else
			return 0;
	}

	protected function maxcollocations( $posted, $param ) {
		if ( $this->numcollocations( $posted ) <= intval( $param[ 0 ] ) )
			return true;
	}

	protected function mincollocations( $posted, $param ) {
		if ( $this->numcollocations( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function rangecollocations( $posted, $param ) {
		if ( $this->numcollocations( $posted ) <= intval( $param[ 1 ] ) && $this->numcollocations( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function maxchoice( $posted, $param ) {
		if ( count( $posted ) <= intval( $param[ 0 ] ) )
			return true;
	}

	protected function minchoice( $posted, $param ) {
		if ( count( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function rangechoice( $posted, $param ) {
		if ( count( $posted ) <= intval( $param[ 1 ] ) && count( $posted ) >= intval( $param[ 0 ] ) )
			return true;
	}

	protected function dateCustom( $posted ) {
		return true;
	}
}