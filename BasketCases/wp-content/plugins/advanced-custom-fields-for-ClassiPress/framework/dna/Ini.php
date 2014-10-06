<?php

class DNA_Ini {

	/********************************************************************
	 * ============================= WRITE ===============================
	 * ******************************************************************/
	static function write( & $ini, $prefix ) {
		$string = '';
		//ksort($ini);

		if ( is_array($ini) ) {
			foreach ( $ini as $key => $val ) {
				if ( is_array( $val ) ) {
					$string .= self::write( $ini[$key], $prefix . $key . '.' );
				} else {
					$string .= $prefix . $key . ' = ' . str_replace( "\n", "\\\n", self::set_value( $val ) ) . "\n";
				}
			}
		} else {
			$string .= str_replace( "\n", "\\\n", self::set_value( $ini ) ) . "\n";
		}
		return $string;
	}

	/********************************************************************
	 * ============================= READ ===============================
	 * ******************************************************************/
	static function read( $filename ) {
		$ini = array();
		$lines = file( $filename );
		$section = 'default';
		$multi = '';
		foreach ( $lines as $line ) {
			if ( substr( $line, 0, 1 ) !== ';' ) {
				$line = str_replace( "\r", "", str_replace( "\n", "", $line ) );
				if ( preg_match( '/^\[(.*)\]/', $line, $m ) ) {
					$section = $m[1];
					if ( preg_match( '/^\[(.*)\]\s*(=)\s*(.*)/', $line, $m ) ) {
						if ( '=' == $m[2] )
						$ini[$section] = trim( $m[3] );
					}

				} else if ( $multi === '' && preg_match( '/^([a-z0-9_.\[\]-]+)\s*=\s*(.*)$/i', $line, $m ) ) {
					$key = $m[1];
					$val = $m[2];
					if ( substr( $val, -1 ) !== "\\" ) {
						$val = trim( $val );
						self::manage_keys( $ini[$section], $key, $val );
						$multi = '';
					} else {
						$multi = substr( $val, 0, -1 ) . "\n";
					}
				} else if ( $multi !== '' ) {
					if ( substr( $line, -1 ) === "\\" ) {
						$multi .= substr( $line, 0, -1 ) . "\n";
					} else {
						self::manage_keys( $ini[$section], $key, $multi . $line );
						$multi = '';
					}
				}
			}
		}
		return $ini;
	}


	/********************************************************************
	 * ============================= MOVE SETTINGS ======================
	 * ******************************************************************/

	/**
	 * Import settings from file to DB
	 *
	 * @param string $input_name Input name attribute
	 * @param array $options Options names
	 * @return type
	 */
	static function import( $input_name = 'importSettings', $options = array() ){
		$import = self::read( $_FILES[ $input_name ]['tmp_name'] );
		$ret = array();
		foreach ( $options as $option ) {
			if ( isset( $import[ $option ] ) )
				$ret[ $option ] = $import[ $option ];
		}
		
		return $ret;
	}

	/**
	 * Export plugin settings to options_export.ini file.
	 */
	static function export( $options = array() ) {

		if ( empty( $options ) )
			return false;

			$string = '';
			foreach ( $options as $option ) {
				$string .= "[" . $option . "]";
				if ( !is_array( get_option( $option ) ) )
					$string .= " = " . get_option( $option ) . "\n";
				else
					$string .= "\n" .  self::write( get_option( $option ), '' ) . "\n";
			}
			header( "Content-type: application/x-msdownload", true, 200 );
			header( "Content-Disposition: attachment; filename='options_export.ini'" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );
			echo $string;
			exit();
	}

	/**
	 *  manage keys
	 */
	private static function set_value( $val ) {
		if ( $val === true ) {
			return 'true';
		} else if ( $val === false ) {
			return 'false';
		}
		return $val;
	}

	/**
	 *  manage keys
	 */
	private static function get_value( $val ) {
		if ( preg_match( '/^-?[0-9]$/i', $val ) ) {
			return intval( $val );
		} else if ( strtolower( $val ) === 'true' ) {
			return true;
		} else if ( strtolower( $val ) === 'false' ) {
			return false;
		} else if ( preg_match( '/^"(.*)"$/i', $val, $m ) ) {
			return $m[1];
		} else if ( preg_match( '/^\'(.*)\'$/i', $val, $m ) ) {
			return $m[1];
		}
		return $val;
	}

	/**
	 *  manage keys
	 */
	private static function get_key( $val ) {
		if ( preg_match( '/^[0-9]$/i', $val ) ) {
			return intval( $val );
		}
		return $val;
	}

	/**
	 *  manage keys
	 */
	private static function manage_keys( & $ini, $key, $val ) {
		if ( preg_match( '/^([a-z0-9_-]+)\.(.*)$/i', $key, $m ) ) {
			self::manage_keys( $ini[$m[1]], $m[2], $val );
		} else if ( preg_match( '/^([a-z0-9_-]+)\[(.*)\]$/i', $key, $m ) ) {
			if ( $m[2] !== '' ) {
				$ini[$m[1]][self::get_key( $m[2] )] = self::get_value( $val );
			} else {
				$ini[$m[1]][] = self::get_value( $val );
			}
		} else {
			$ini[self::get_key( $key )] = self::get_value( $val );
		}
	}
}
?>