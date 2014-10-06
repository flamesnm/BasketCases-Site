<?php
class DNA_Profile_Tab extends DNA_Tab {

	const OPTION_NAME = 'acf_profile_fields';

	public function __construct( $tab ) {
		parent::__construct( $tab );
		$this->properties = array(
			'name' => array(
				'title' => __( 'Name', ACF_TD ),
				'col' => '0',
				'type' => 'text',
				'desc' => __( 'The name of the field. Required field.<br /> The field can contain only letters, numbers, and underscores.', ACF_TD )
			),
			/* Type and values */
			'type' => array(
				'title' => __( 'Field Type', ACF_TD ),
				'col' => '1',
				'type' => 'drop-down',
				'desc' => __( 'Type of the field. While there are only the following types: text_box, drop-down, checkbox, radio, text area.<br /> Default value: text_box.', ACF_TD )
			),
			'values' => array(
				'title' => __( 'List Of Values', ACF_TD ),
				'col' => '1',
				'type' => 'text area',
				'desc' => __( 'The list of values available in the following field types: drop-down, checkbox, radio. This field is required for these types. <br />Enter a comma separated list of values or single value you want to appear in this drop-down box. <br />If you want to use an existing list of values of a specific ad field - just enter the name of the field. As done for the field "user_state". This is very handy.', ACF_TD )
			),
			'default' => array(
				'title' => __( 'Default Value', ACF_TD ),
				'col' => '1',
				'type' => 'text',
				'desc' => __( 'This is the default value for the form fields. The user can change this value or remain unchanged.<br />If the type field contains a list of values, the default value should be in this list.', ACF_TD )
			),
			/* Formats and Limitations */
			'format' => array(
				'title' => __( 'Format Of Values', ACF_TD ),
				'col' => '2',
				'type' => 'drop-down',
				'desc' => __( 'Formats and limitations will allow you to get a more ordered information from the user. <br />You can set the format for email or url, or number, or simply required etc. <br />See detailed description of formats and limitations in the Help topic "Formats & Limitations".', ACF_TD )
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
						.	'<li><strong><em>'. __( 'Default', ACF_TD ) . '</em></strong> - '		. __( 'The text renders as it is. This is default', ACF_TD ) . '</li>'
						.	'<li><strong><em>Capitalize</em></strong> - '	. __( 'Transforms The First Character Of Each Word To Uppercase, All Others Letters To Lowercase.', ACF_TD ) . '</li>'
						.	'<li><strong><em>Uppercase</em></strong> - '	. __( 'TRANSFORMS ALL CHARACTERS TO UPPERCASE.', ACF_TD ) . '</li>'
						.	'<li><strong><em>Lowercase</em></strong> - '	. __( 'transforms all characters to lowercase.', ACF_TD ) . '</li></ul>'
			),
			/* Labels and Descriptions */
			'title' => array(
				'title' => __( 'Title', ACF_TD ),
				'col' => '3',
				'type' => 'text',
				'desc' => __( 'Field title.', ACF_TD )
			),
			'description' => array(
				'title' => __( 'Description', ACF_TD ),
				'col' => '3',
				'type' => 'text area',
				'desc' => __( 'Field description.', ACF_TD )
			),
			/* Display field options */
			'reg_form_display' => array(
				'title' => __( 'Registration form', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the registration page.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'edit_profile_display' => array(
				'title' => __( 'Edit profile form', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the edit profile page.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'edit_profile_admin' => array(
				'title' => __( 'Edit profile admin form', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the edit profile admin page.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'author_page_display' => array(
				'title' => __( 'Author’s page', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the author\'s page.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'user_sidebar_display' => array(
				'title' => __( 'User sidebar', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the users\'s sidebar (Widget "Account information").', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'user_sidebar_ad_display' => array(
				'title' => __( 'Single ad sidebar', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the Single ad sidebar (tab "Poster").', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'single_ad_display' => array(
				'title' => __( 'Single ad before details', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ad single page before ad fields.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'single_ad_after' => array(
				'title' => __( 'Single ad after details', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ad single page after ad fields.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'loop_ad_top' => array(
				'title' => __( 'Loop ad top', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ads loop before the description.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'loop_ad_bottom' => array(
				'title' => __( 'Loop ad bottom', ACF_TD ),
				'col' => '4',
				'type' => 'checkbox',
				'desc' => __( 'Check to display this field in the ads loop after the description.', ACF_TD ) . ' ' . __( 'By default is off, so that you could control the display of fields after activating the plugin.', ACF_TD )
			),
			'logged_in' => array(
				'title' => __( 'For Logged-In', ACF_TD ),
				'col' => '5',
				'type' => 'checkbox',
				'desc' => __( 'If you set this property, then this field will display only for logged-in users.', ACF_TD )
			),
			'private' => array(
				'title' => __( 'Private field', ACF_TD ),
				'col' => '5',
				'type' => 'checkbox',
				'desc' => __( 'If you set this property, then this field will see only the author, and administrators. Ordinary visitors and search engines will not see this field.', ACF_TD )
			)
		);

		$this->subtabs = array(
			1 => array( 'title' => __( 'Main Properties', ACF_TD ) ),
			2 => array( 'title' => __( 'Formats & Limitations', ACF_TD ) ),
			3 => array( 'title' => __( 'Labels & Descriptions', ACF_TD ) ),
			4 => array( 'title' => __( 'Display Options', ACF_TD ) ),
			5 => array( 'title' => __( 'Conditional Display Options', ACF_TD ) ),
		);
	}

	public function register_js() {
		$this->register_js_var( 'field_properties', array_keys( $this->properties ) );
	}

	public function get_html(){
		global $acf_admin;
		$profile_fields = get_option( self::OPTION_NAME );
		$acf_admin->print_subtabs( $this->subtabs );?>
		<table id="acf_profile_field-table" class="widefat">
			<thead>
				<tr>
					<?php foreach ( $this->properties as $property ) {
						$class = ( 0 != $property['col'] ) ? 'col' . $property['col'] .' dna-col' : ''; ?>
						<th class="<?php echo esc_attr( $class ); ?>">
							<span class="titletip" tip="<?php echo esc_attr( $property['desc'] ); ?>"><?php echo esc_html( $property['title'] ); ?></span>
						</th>
					<?php } ?>
					<th class="row_actions">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$count = 1;
				foreach ( $profile_fields as $key => $profile_field ){ ?>
						<tr data-array_index="<?php echo $count; ?>">
							<?php $this->get_row_html( $key, $profile_field, $count ); ?>
							<td class="row_actions">
								<a href="javascript:void(0)" class ="row_remove">
									<img alt="<?php _e( 'Delete', ACF_TD ); ?>" title="<?php _e( 'Delete', ACF_TD ); ?>" width="16" height="16" src="<?php echo get_template_directory_uri(); ?>/images/cross.png" />
								</a>
							</td>
						</tr>

					<?php $count++;
				} ?>
				<tr class="alternate" id="template_row" >
					<?php $this->get_row_html(); ?>
					<td class="row_actions">
						<a href="javascript:void(0)" class ="row_remove">
							<img alt="<?php _e( 'Delete', ACF_TD ); ?>" title="<?php _e( 'Delete', ACF_TD ); ?>" width="16" height="16" src="<?php echo get_template_directory_uri(); ?>/images/cross.png" />
						</a>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="<?php echo esc_attr( count( $this->properties ) ); ?>" style="text-align:center"><input id="acf_add-field-btn" class="button-secondary" style="padding:4px" type="button" value="<?php _e( 'Add field', ACF_TD ); ?>" /></th>
				</tr>
			</tfoot>
		</table>
	<?php
	}

	protected function get_row_html( $key = '', $option_field = '', $row_index = '' ) {
		$properties = $this->properties;
		foreach ( $properties as $prop_name => $property ) {
			$input['class'] = 'field_' . $prop_name;
			$input['name'] = $input['class'] . '_' . $row_index;
			$td_class = ( 0 != $property['col'] ) ? ' class="col' . $property['col'] .' dna-col"' : '';
			if ( $prop_name == 'name' )
				$input['value'] = $key;
			else
				$input['value'] = ( isset( $option_field[$prop_name] ) ) ? $option_field[$prop_name] : '';

			$input['type'] = $property['type'];

			if ( $input['type'] == 'drop-down' )
				$input['values'] = $this->get_select_values( $prop_name );

			if ( $prop_name != 'type' )
				$input['pls_select'] = '<option value="">-- ' . __( 'Default' ) . ' --</option>';

			if ( $input['type'] == 'text area' ) {
				$input['rows'] = 1;
				$input['cols'] = 70;
			}

			$input['id'] = $input['name'];

			echo '<td' . $td_class . '>';
			acf_tags_html( $input );
			echo '</td>';

			unset( $input );
		}
	}

	public function update_option(){
		$option = array();
		$count = 1;
		$properties = array_keys( $this->properties );
		// Update profile options
		while ( isset( $_POST['field_name_' . $count] ) ) {
			foreach ( $properties as $key ) {
				if ( $key == 'name' )
					continue;
				if ( isset( $_POST['field_' . $key . '_' . $count] ) )
					$option[$_POST['field_name_' . $count]][$key] = appthemes_clean( $_POST['field_' . $key . '_' . $count] );
				else
					$option[$_POST['field_name_' . $count]][$key] = '';
			}
			$count++;
		}
		if ( !empty( $option ) )
			update_option( self::OPTION_NAME, $option );
	}
}