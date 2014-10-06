<?php
class DNA_Adfields_Tab extends DNA_Tab {
	const OPTION_NAME = 'acf_ad_fields';

	public function __construct( $tab ) {
		parent::__construct( $tab );
			$this->properties =  array(
			'name' => array(
				'title' => __( 'Name', ACF_TD ),
				'col' => '0',
				'type' => 'span',
				'desc' => __( 'The "Meta Name" of the ClassiPress ad field.', ACF_TD )
			),
			'title' => array(
				'title' => __( 'Title', ACF_TD ),
				'col' => '1',
				'type' => 'span',
				'desc' => __( 'Field title.', ACF_TD )
			),
			'type' => array(
				'title' => __( 'Field Type', ACF_TD ),
				'col' => '1',
				'type' => 'span',
				'desc' => __( 'Type of the field. While there are only the following types: text_box, drop-down, checkbox, radio, text area.<br /> Default value: text_box.', ACF_TD )
			),
			'default' => array(
				'title' => __( 'Default Value', ACF_TD ),
				'col' => '1',
				'type' => 'text',
				'desc' => __( 'This is the default value for the form fields. <br /><strong>If you want to inherit the value of some field profile into ClassiPress field - enter  name of the field profile.</strong> <br /> The user can change this value or remain unchanged. <br />If the type field contains a list of values, the default value should be in this list.', ACF_TD )
			),
			'format' => array(
				'title' => __( 'Format Of Values', ACF_TD ),
				'col' => '2',
				'type' => 'drop-down',
				'desc' => __( 'Formats and limitations will allow you to get a more ordered information from the user.', ACF_TD ) . ' <br />' . sprintf( __( 'You can set the format for email or url, or number, or simply required etc. <br />See detailed description of formats and limitations in the Help topic "%s".', ACF_TD ), __( 'Formats & Limitations', ACF_TD ) )
			),
			'limits' => array(
				'title' => __( 'Limitations Of Values', ACF_TD ),
				'col' => '2',
				'type' => 'drop-down',
				'desc' => sprintf( __( 'Restricts user data input. You can set limits on the number of characters, words, collocations, and also limits on numeric values. <br />There are 3 types of limitations: minimum, maximum and range.<br /> The value of the limitations must be entered in the field "%s". <br />Read detailed description of formats and limitations in the Help topic "%s".', ACF_TD ), __( 'Limitations Attributes', ACF_TD ), __( 'Formats & Limitations', ACF_TD ) )
			),
			'limits_attr' => array(
				'title' => __( 'Limitations Attributes', ACF_TD ),
				'col' => '2',
				'type' => 'text',
				'desc' => sprintf( __( 'Methods of limiting take 1 or 2 parameters, you must specify in this field. <br />For example, you chose limitation "rangeWords" - this means that the user must enter a few words in a certain range. <br />Just write the numbers separated by comma in the "%s" (for example: 2,10).', ACF_TD ), __( 'Limitations Attributes', ACF_TD ) )
			),
			'transform' => array(
				'title' => __( 'Text Transform', ACF_TD ),
				'col' => '2',
				'type' => 'drop-down',
				'desc' => __( 'As well as the CSS <strong><em>text-transform</em></strong> property controls the capitalization of text.', ACF_TD ) . '<br />'
						. __( 'But here capitalization occurs before user\'s data saves in database.', ACF_TD ) . '<br />'
						. __( 'You can select one of 4 options:', ACF_TD ) . '<ul>'
						. '<li><strong><em>Default</em></strong> - ' . __( 'The text renders as it is. This is default', ACF_TD ). '</li>'
						. '<li><strong><em>Capitalize</em></strong> - ' . __( 'Transforms The First Character Of Each Word To Uppercase, All Others Letters To Lowercase.', ACF_TD ). '</li>'
						. '<li><strong><em>Uppercase</em></strong> - ' . __( 'TRANSFORMS ALL CHARACTERS TO UPPERCASE.', ACF_TD ). '</li>'
						. '<li><strong><em>Lowercase</em></strong> - ' . __( 'transforms all characters to lowercase.', ACF_TD ). '</li>' . '<ul>'
			),
			'new_ad_display' => array(
				'title' => __( 'New ad', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the new ad page.', ACF_TD ) . ' ' . __( 'By default is on, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'edit_ad_display' => array(
				'title' => __( 'Edit ad', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the edit ad page.', ACF_TD ) . ' ' . __( 'By default is on, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'single_ad_display' => array(
				'title' => __( 'Single ad list', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ad single page.', ACF_TD ) . ' ' . __( 'By default is on, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'single_ad_cont' => array(
				'title' => __( 'Single ad content', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ad single page in separated content area.', ACF_TD ) . ' ' . __( 'By default is off.', ACF_TD )
			),
			'loop_ad_top' => array(
				'title' => __( 'Loop ad top', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ads loop before the description.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'loop_ad_bottom' => array(
				'title' => __( 'Loop ad bottom', ACF_TD ),
				'col' => '3',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ads loop after the description.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'featured' => array(
				'title' => __( 'Featured field', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'If you set this property, then this field will display only on Featured Ads.', ACF_TD )
			),
			'logged_in' => array(
				'title' => __( 'For Logged-In', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'If you set this property, then this field will display only for logged-in users.', ACF_TD )
			),
			'private' => array(
				'title' => __( 'Private field', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'If you set this property, then this field will see only the author, and administrators. Ordinary visitors and search engines will not see this field.', ACF_TD )
			)
		);

		$this->subtabs = array(
			1 => array( 'title' => __( 'Main Properties', ACF_TD ) ),
			2 => array( 'title' => __( 'Formats & Limitations', ACF_TD ) ),
			3 => array( 'title' => __( 'Display Options', ACF_TD ) ),
			4 => array( 'title' => __( 'Conditional Display Options', ACF_TD ) ),
		);
	}

	public function get_html(){
		global $acf_admin;
		$cp_ad_fields = $this->get_ad_fields();
		$ad_fields = get_option( self::OPTION_NAME );
		$acf_admin->print_subtabs( $this->subtabs );
		?>
		<table id="acf_ad_field-table" class="widefat">
			<thead>
				<tr>
					<?php
					$class = '';
					foreach ( $this->properties as $property ) {
						 $class = ( 0 != $property['col'] ) ? 'col' . $property['col'] .' dna-col' : '';
						echo '<th class="'. esc_attr( $class ) .'"><span class="titletip" tip="' . esc_attr( $property['desc'] ) . '">' . $property['title'] . '</span></th>';
					} ?>
					<th class="row_actions">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $cp_ad_fields as $cp_ad_field ) {
					echo '<tr>';
						if ( !isset( $ad_fields[$cp_ad_field->field_name] ) )
							$ad_fields[$cp_ad_field->field_name] = '';
						$ad_fields[$cp_ad_field->field_name]['title'] = $cp_ad_field->field_label;
						$ad_fields[$cp_ad_field->field_name]['type'] = $cp_ad_field->field_type;
						$ad_fields[$cp_ad_field->field_name]['desc'] = $cp_ad_field->field_desc;
						$ad_fields[$cp_ad_field->field_name]['id'] = $cp_ad_field->field_id;
						$this->get_row_html( $cp_ad_field->field_name, $ad_fields[$cp_ad_field->field_name] ); ?>
						<td class="row_actions"><?php $this->get_row_actions( $cp_ad_field ); ?></td>
					<?php echo '</tr>';
				} ?>
			</tbody>
			<tfoot>
				<tr>
					<?php
					foreach ( $this->properties as $key => $property ) {
						$class = ( 0 != $property['col'] ) ? 'col' . $property['col'] .' dna-col' : '';
						if ( $property['type'] == 'checkbox' && $key != 'date' )
							echo '<th class="' . $class .'"><input type="checkbox" value="" class="col_check" id="field_' . $key . '"><br />' . $property['title'] . '</th>';
						else
							echo '<th class="' . $class .'">' . $property['title'] . '</th>';
					} ?>
					<th class="row_actions">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="<?php echo count( $this->properties ); ?>" style="text-align:center"><a target="_blank" href="?page=fields&amp;action=addfield" class="button-secondary" style="padding:4px"><?php _e( 'Add field', ACF_TD ); ?></a></th>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" name="deleted_fields" value="" id="deleted_fields" />
		<datalist id="default_vals">
			<?php $profile_fields = array_keys( get_option( 'acf_profile_fields' ) );
			foreach ( $profile_fields as $profile_field ){ ?>
				<option value="<?php echo esc_attr( $profile_field );?>">
			<?php } ?>
		</datalist>
		<?php
	}

	private function get_row_actions ( $result ) {
		switch($result->field_perm) {
			case '1': // core fields no editing
			case '2': // core fields some editing
			?>
				<img width="16" height="16" alt="" src="<?php bloginfo('template_directory'); ?>/images/cross-grey.png" />
			<?php
			break;
			default: // regular fields full editing
				// don't change this line to plain html/php. Get t_else error msg
				echo '<a class="deletecross" href="javascript:void(0)" id="'. $result->field_id .'"><img alt="' . __( 'Delete', ACF_TD ) . '" title="' . __( 'Delete', ACF_TD ) . '" width="16" height="16" src="'. get_template_directory_uri() .'/images/cross.png" /></a>';
	   } // endswitch
	}

	private function get_row_html( $field_name = '', $option_field = '' ) {
		foreach ( $this->properties as $prop_name => $field_prop ) {
			$input['class'] = 'field_' . $prop_name;
			$input['name'] = $field_name . '_' . $prop_name;
			$td_class = ( 0 != $field_prop['col'] ) ? ' class="col' . $field_prop['col'] .' dna-col"' : '';

			if ( $prop_name == 'name' ) {
				$input['value'] = $field_name;
				$input['class'] .= ' titletip';
				$input['tip'] = $option_field['desc'];
			} else {
				$input['value'] = ( isset( $option_field[$prop_name] ) ) ? $option_field[$prop_name] : '';
			}

			if ( $prop_name == 'default' ){
				$input['limits'] = 'list';
				$input['limits_attr'] = 'default_vals';
			}
			$input['type'] = $field_prop['type'];

			if ( $input['type'] == 'drop-down' )
				$input['values'] = $this->get_select_values( $prop_name );

			if ( $prop_name != 'type' )
				$input['pls_select'] = '<option value="">' . __( '-- Default --', APP_TD ) . '</option>';

			if ( $input['type'] == 'text area' ) {
				$input['rows'] = 1;
				$input['cols'] = 70;
			}

			$input['id'] = $input['name'];

			echo '<td' . $td_class . '>';
			/*<a target="_blank" href="?page=fields&amp;action=editfield&amp;id=<?php echo $result->field_id; ?>"></a>*/
			if ( $prop_name == 'name' )
				echo '<a target="_blank" href="?page=fields&amp;action=editfield&amp;id=' . $option_field['id'] . '">';
			acf_tags_html( $input );
			echo '</td>';
			if ( $prop_name == 'name' )
				echo '</a>';
			unset( $input );
		}
	}

	private function delete_ad_fields( $ids_raw ) {
		global $wpdb;
        // check and make sure this fields perms allow deletion
        $sql = $wpdb->prepare( "SELECT field_id, field_perm FROM $wpdb->cp_ad_fields WHERE field_id IN (" . $ids_raw . ")" );
        $results = $wpdb->get_results( $sql );

		if ( ! $results )
			return;

		$ids = array();
		foreach ( $results as $result ) {
			if ( ! $result->field_perm > 0 )
				$ids[] = $result->field_id;
		}

		if ( empty( $ids ) )
			return;

		$ids = implode( "," , $ids );
		$delete = $wpdb->prepare( "DELETE FROM $wpdb->cp_ad_fields WHERE field_id IN (" . $ids . ")" );
		$wpdb->query( $delete );
	}


	public function update_option(){
		//if field deleted
		if ( isset( $_POST['deleted_fields'] ) && '' != $_POST['deleted_fields'] ) {
			$ids = substr($_POST['deleted_fields'], 1);
			$this->delete_ad_fields( $ids );
		}

		$option = array();
		$cp_ad_fields = $this->get_ad_fields();
		$properties = array_keys( $this->properties );

		foreach ( $cp_ad_fields as $cp_ad_field ) {
			foreach ( $properties as $key ) {
				$name = $cp_ad_field->field_name . '_' . $key;
				if ( isset( $_POST[$name] ) )
					$option[$cp_ad_field->field_name][$key] = appthemes_clean( $_POST[$name] );
			}
		}
		if ( ! empty( $option ) )
			update_option( self::OPTION_NAME, $option );
	}


	private function get_ad_fields(){
		global $wpdb;
		$sql = "SELECT field_name, field_label, field_type, field_desc, field_perm, field_id "
				. "FROM " . $wpdb->prefix . "cp_ad_fields "
				. "ORDER BY field_name desc";

		return $wpdb->get_results( $sql );
	}

}