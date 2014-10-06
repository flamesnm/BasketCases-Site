<?php
class DNA_Msgs_Tab extends DNA_Tab {

	const OPTION_NAME = 'acf_error_msgs';

	public function register_js() {
		$this->register_js_var( 'validate_msgs', get_option( self::OPTION_NAME ) );
		$this->register_js_var( 'field_formats', $this->get_field_formats() );
		// add the language validation file if not english
		if ( ! defined( 'CP_VERSION' ) ) {
			global $app_abbr;
			if ( get_option( $app_abbr . '_form_val_lang' ) ) {
				$lang_code = strtolower( get_option( $app_abbr . '_form_val_lang' ) );
				$this->register_script( get_template_directory_uri() . "/includes/js/validate/localization/messages_$lang_code.js" );
			}
		} else {
			global $cp_options;
			if ( $cp_options->form_val_lang ) {
				$lang_code = trim( $cp_options->form_val_lang );
				$this->register_script( APP_FRAMEWORK_URI . "/js/validate/i18n/messages_$lang_code.js", array( 'validate' ) );
			}
		}
	}

	private function field_formats() {
		return array_keys( $this->get_field_formats() );
	}

	public function get_html(){
		$error_msgs = get_option( self::OPTION_NAME );?>
		<table id="acf_error_msgs-table" class="widefat">
			<thead>
				<tr>
					<th><span class="titletip"><?php _e( 'Field Format', ACF_TD ); ?></span></th>
					<th><span class="titletip"><?php _e( 'Error message', ACF_TD ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$formats_arr = $this->field_formats();
				foreach ( $formats_arr as $field_format ) {
					?>
						<tr>
							<td><span class="format_name"><?php echo $field_format; ?></span></td>
							<td><textarea name="<?php echo $field_format; ?>_err" rows="1" cols="100" id="<?php echo $field_format; ?>_err" class="field_format_err textarea" ><?php if ( isset( $error_msgs[$field_format] ) ) echo $error_msgs[$field_format]; ?></textarea></td>
						</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2"></th>
				</tr>
			</tfoot>
		</table>
	<?php
	}

	public function update_option(){
		$option = array();
		$properties = $this->field_formats();
		foreach ( $properties as $key ) {
			if ( isset( $_POST[$key . '_err'] ) )
				$option[$key] = appthemes_clean( $_POST[$key . '_err'] );
		}
		if ( !empty( $option ) )
			update_option( self::OPTION_NAME, $option );
	}
}