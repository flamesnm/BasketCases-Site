<?php
class DNA_Datepicker_Tab extends DNA_Tab {

	const OPTION_NAME = 'acf_date_picker';

	private $date_locales = array(
			'af'	=> 'Afrikaans',
			'sq'	=> 'Albanian (Gjuha shqipe)',
			'ar-DZ' => 'Algerian Arabic',
			'ar'	=> 'Arabic (&#8235;(&#1604;&#1593;&#1585;&#1576;&#1610;',
			'hy'	=> 'Armenian (&#1344;&#1377;&#1397;&#1381;&#1408;&#1381;&#1398;)',
			'az'	=> 'Azerbaijani (Az&#601;rbaycan dili)',
			'eu'	=> 'Basque (Euskara)',
			'bs'	=> 'Bosnian (Bosanski)',
			'bg'	=> 'Bulgarian (&#1073;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080; &#1077;&#1079;&#1080;&#1082;)',
			'ca'	=> 'Catalan (Catal&agrave;)',
			'zh-HK' => 'Chinese Hong Kong (&#32321;&#39636;&#20013;&#25991;)',
			'zh-CN' => 'Chinese Simplified (&#31616;&#20307;&#20013;&#25991;)',
			'zh-TW' => 'Chinese Traditional (&#32321;&#39636;&#20013;&#25991;)',
			'hr'	=> 'Croatian (Hrvatski jezik)',
			'cs'	=> 'Czech (&#269;e&#353;tina)',
			'da'	=> 'Danish (Dansk)',
			'nl-BE' => 'Dutch (Belgium)',
			'nl'	=> 'Dutch (Nederlands)',
			'en-AU' => 'English/Australia',
			'en-NZ' => 'English/New Zealand',
			'en-GB' => 'English/UK',
			'eo'	=> 'Esperanto',
			'et'	=> 'Estonian (eesti keel)',
			'fo'	=> 'Faroese (f&oslash;royskt)',
			'fa'	=> 'Farsi/Persian (&#8235;(&#1601;&#1575;&#1585;&#1587;&#1740;',
			'fi'	=> 'Finnish (suomi)',
			'fr'	=> 'French (Fran&ccedil;ais)',
			'fr-CH' => 'French/Swiss (Fran&ccedil;ais de Suisse)',
			'gl'	=> 'Galician',
			'ge'	=> 'Georgian',
			'de'	=> 'German (Deutsch)',
			'el'	=> 'Greek (&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;)',
			'he'	=> 'Hebrew (&#8235;(&#1506;&#1489;&#1512;&#1497;&#1514;',
			'hi'	=> 'Hindi (&#2361;&#2367;&#2306;&#2342;&#2368;)',
			'hu'	=> 'Hungarian (Magyar)',
			'is'	=> 'Icelandic (&Otilde;slenska)',
			'id'	=> 'Indonesian (Bahasa Indonesia)',
			'it'	=> 'Italian (Italiano)',
			'ja'	=> 'Japanese (&#26085;&#26412;&#35486;)',
			'kk'	=> 'Kazakhstan (Kazakh)',
			'km'	=> 'Khmer',
			'ko'	=> 'Korean (&#54620;&#44397;&#50612;)',
			'lv'	=> 'Latvian (Latvie&ouml;u Valoda)',
			'lt'	=> 'Lithuanian (lietuviu kalba)',
			'lb'	=> 'Luxembourgish',
			'mk'	=> 'Macedonian',
			'ml'	=> 'Malayalam',
			'ms'	=> 'Malaysian (Bahasa Malaysia)',
			'no'	=> 'Norwegian (Norsk)',
			'pl'	=> 'Polish (Polski)',
			'pt'	=> 'Portuguese (Portugu&ecirc;s)',
			'pt-BR' => 'Portuguese/Brazilian (Portugu&ecirc;s)',
			'rm'	=> 'Rhaeto-Romanic (Romansh)',
			'ro'	=> 'Romanian (Rom&acirc;n&#259;)',
			'ru'	=> 'Russian (&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;)',
			'sr'	=> 'Serbian (&#1089;&#1088;&#1087;&#1089;&#1082;&#1080; &#1112;&#1077;&#1079;&#1080;&#1082;)',
			'sr-SR' => 'Serbian (srpski jezik)',
			'sk'	=> 'Slovak (Slovencina)',
			'sl'	=> 'Slovenian (Slovenski Jezik)',
			'es'	=> 'Spanish (Espa&ntilde;ol)',
			'sv'	=> 'Swedish (Svenska)',
			'ta'	=> 'Tamil (&#2980;&#2990;&#3007;&#2996;&#3021;)',
			'th'	=> 'Thai (&#3616;&#3634;&#3625;&#3634;&#3652;&#3607;&#3618;)',
			'tj'	=> 'Tajikistan',
			'tr'	=> 'Turkish (T&uuml;rk&ccedil;e)',
			'uk'	=> 'Ukranian (&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;)',
			'vi'	=> 'Vietnamese (Ti&#7871;ng Vi&#7879;t)',
			'cy-GB' => 'Welsh/UK (Cymraeg)'
		);


	public function __construct( $tab ) {
		parent::__construct( $tab );
		$this->properties = array(
			'preview' => array(
				'title' => __( 'Real time preview', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> __( 'Click on the text box to see the Datepicker with your settings in real time. <br />As such, the Datepicker is displayed on the live page, after saving the settings. <br />Try to manually enter the date in the wrong format and you will see how the validation works.', ACF_TD )
			),
			'date_format'	=> array(
				'title' => __( 'Date format', ACF_TD ),
				'type'	=> 'radio',
				'desc'	=> __( 'You can select a predefined date format for your locale, or you can use these ready-made formats.<br />You can also specify your own date format (Custom option).', ACF_TD )
			),
			'custom_format_text' => array(
				'title' => __( 'Format Input', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> __( 'The Custom date format input.', ACF_TD )
			),
			'locale' => array(
				'title' => __( 'Localizations', ACF_TD ),
				'type'	=> 'drop-down',
				'desc'	=> __( 'Localizations of jQuery UI Datepicker. <br />Each localization contains not only language translation, but also a set of ready settings for each represented region.', ACF_TD )
			),
			'animation' => array(
				'title' => __( 'Animations', ACF_TD ),
				'type'	=> 'drop-down',
				'desc'	=> __( 'Set the name of the animation used to show/hide the datepicker.', ACF_TD )
			),
			'multi_month' => array(
				'title' => __( 'Display multiple month', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> __( 'Set how many months to show at once. The value can be a straight integer.', ACF_TD )
			),
			'button_bar' => array(
				'title' => __( 'Display button bar', ACF_TD ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Whether to show the button panel.', ACF_TD )
			),
			'menus' => array(
				'title' => __( 'Display month and year menus', ACF_TD ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Allows you to change the year and month by selecting from a drop-down list.', ACF_TD )
			),
			'maxDate' => array(
				'title' => __( 'Max date', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> '<div><div><strong>' . __( 'Type', ACF_TD ) . ': </strong>' . __( 'Date or Number or String', ACF_TD) . '</div>'
						.	'<div><strong>' . __( 'Default', ACF_TD ) . ': </strong><code>null</code></div>'
						.	'<div>' . __( 'The maximum selectable date. When set to <code>null</code>, there is no maximum.', ACF_TD ) . '</div>'
						.	__( 'Multiple types supported:', ACF_TD )
						.	'<ul>'
						.	'<li><strong>' . __( 'Date', ACF_TD ) . '</strong>: ' . __( 'A date object containing the maximum date.', ACF_TD ) . '</li>'
						.	'<li><strong>' . __( 'Number', ACF_TD ) . '</strong>: ' . __( 'A number of days from today. For example <code>2</code> represents two days from today and <code>-1</code> represents yesterday.', ACF_TD ) . '</li>'
						.	'<li><strong>' . __( 'String', ACF_TD ) . '</strong>: ' . __( 'A string in the format defined by the <code>dateFormat</code> option, or a relative date. Relative dates must contain value and period pairs; valid periods are <code>"y"</code> for years, <code>"m"</code> for months, <code>"w"</code> for weeks, and <code>"d"</code> for days. For example, <code>"+1m +7d"</code> represents one month and seven days from today.', ACF_TD ) . '</li>'
						.	'</ul></div>'
			),
			'minDate' => array(
				'title' => __( 'Min date', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> '<div><div><strong>' . __( 'Type', ACF_TD ) . ': </strong>' . __( 'Date or Number or String', ACF_TD) . '</div>'
						.	'<div><strong>' . __( 'Default', ACF_TD ) . ': </strong><code>null</code></div>'
						.	'<div>' . __( 'The minimum selectable date. When set to <code>null</code>, there is no minimum.', ACF_TD ) . '</div>'
						.	__( 'Multiple types supported:', ACF_TD )
						.	'<ul>'
						.	'<li><strong>' . __( 'Date', ACF_TD ) . '</strong>: ' . __( 'A date object containing the minimum date.', ACF_TD ) . '</li>'
						.	'<li><strong>' . __( 'Number', ACF_TD ) . '</strong>: ' . __( 'A number of days from today. For example <code>2</code> represents two days from today and <code>-1</code> represents yesterday.', ACF_TD ) . '</li>'
						.	'<li><strong>' . __( 'String', ACF_TD ) . '</strong>: ' . __( 'A string in the format defined by the <code>dateFormat</code> option, or a relative date. Relative dates must contain value and period pairs; valid periods are <code>"y"</code> for years, <code>"m"</code> for months, <code>"w"</code> for weeks, and <code>"d"</code> for days. For example, <code>"+1m +7d"</code> represents one month and seven days from today.', ACF_TD ) . '</li>'
						.	'</ul></div>'
			),
			'yearRange' => array(
				'title' => __( 'Year Range', ACF_TD ),
				'type'	=> 'text',
				'desc'	=> '<div><div><strong>' . __( 'Type', ACF_TD ) . ': </strong>' . __( 'String', ACF_TD ) . '</div>'
						.	'<div><strong>' . __( 'Default', ACF_TD ) . ': </strong><code>"c-10:c+10"</code></div>'
						.	'<div>' . __( 'The range of years displayed in the year drop-down: either relative to today\'s year (<code>"-nn:+nn"</code>), relative to the currently selected year (<code>"c-nn:c+nn"</code>), absolute (<code>"nnnn:nnnn"</code>), or combinations of these formats (<code>"nnnn:-nn"</code>). Note that this option only affects what appears in the drop-down, to restrict which dates may be selected use the <code>minDate</code> and/or <code>maxDate</code> options.', ACF_TD ) . '</div></div>'
			),
			'other_dates' => array(
				'title' => __( 'Display dates in other month', ACF_TD ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'When true days in other months shown before or after the current month are selectable.', ACF_TD )
			),
			'icon_trigger' => array(
				'title' => __( 'Display icon trigger', ACF_TD ),
				'type'	=> 'checkbox',
				'desc'	=> __( 'Set to true to place an image after the field to use as the trigger without it appearing on a button.', ACF_TD )
			),

		);
	}


	public function register_js() {
		$this->register_js_var( 'field_properties', array_keys( $this->properties ) );
		$this->register_js_var( 'buttonImage', get_template_directory_uri() . '/images/calendar.gif' );
		$msgs = get_option( 'acf_msgs' );
		$this->register_js_var( 'dateCustom_err', ( isset( $msgs['dateCustom_err'] ) ) ? $msgs['dateCustom_err'] : "Please enter date in valid format!" );

	}

	public function get_html() {
		global $acf_admin;
		$date_picker = get_option( self::OPTION_NAME );
		$properties = $this->properties;
		$acf_admin->print_subtabs( $this->subtabs ); ?>
		<table id="<?php echo self::OPTION_NAME; ?>" class="widefat">
			<thead>
				<tr>
					<th colspan="2"><span class="titletip"><?php esc_html_e( 'jQuery UI Datepicker Settings', ACF_TD ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 250px;">
						<span class="pickerowtitle titletip" tip="<?php echo esc_attr( $properties['preview']['desc'] ); ?>"><?php echo $properties['preview']['title']; ?>:</span>
					</td>
					<td>Date: <input type="text" id="datepicker" class="datevalidator" value=""/></td>

				</tr>
				<tr>
					<td>
						<span class="pickerowtitle titletip" tip="<?php echo esc_attr( $properties['date_format']['desc'] ); ?>"><?php echo $properties['date_format']['title']; ?>:</span>
					</td>
					<td>
						<fieldset class="date_formats">
							<label><input type="radio" name="date_format" class="date_format" id="date_format_0" value="" <?php checked( $date_picker['date_format'], "", true ); ?>/>&nbsp;&nbsp;&nbsp;Use default format which used in localization</label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_1" value="mm/dd/yy" <?php checked( $date_picker['date_format'], "mm/dd/yy", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "m/d/Y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(mm/dd/yy)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_2" value="dd/mm/yy" <?php checked( $date_picker['date_format'], "dd/mm/yy", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "d/m/Y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(dd/mm/yy)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_3" value="dd.mm.yy" <?php checked( $date_picker['date_format'], "dd.mm.yy", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "d.m.Y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(dd.mm.yy)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_4" value="yy-mm-dd" <?php checked( $date_picker['date_format'], "yy-mm-dd", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "Y-m-d" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(yy-mm-dd)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_5" value="d M, y" <?php checked( $date_picker['date_format'], "d M, y", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "j M, y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(d M, y)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_6" value="d MM, y" <?php checked( $date_picker['date_format'], "d MM, y", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "j F, y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(d MM, y)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_7" value="DD, d MM, yy" <?php checked( $date_picker['date_format'], "DD, d MM, yy", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo date( "l, j F, Y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">(DD, d MM, yy)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_8" value="'day' d 'of' MM 'in the year' yy" <?php checked( $date_picker['date_format'], "'day' d 'of' MM 'in the year' yy", true ); ?>/>&nbsp;&nbsp;&nbsp;<?php echo 'day ' . date( "j" ) . ' of ' . date( "F" ) . ' in the year ' . date( "Y" ); ?>&nbsp;&nbsp;&nbsp;<span class="description">('day' d 'of' MM 'in the year' yy)</span></label><br />
							<label><input type="radio" name="date_format" class="date_format" id="date_format_9" value="<?php if ( isset( $date_picker['custom_format_text'] ) ) echo esc_attr( $date_picker['custom_format_text'] ); ?>" <?php if ( isset( $date_picker['custom_format_text'] ) ) checked( $date_picker['date_format'], $date_picker['custom_format_text'], true ); ?>/>&nbsp;&nbsp;&nbsp;<span>Custom</span>&nbsp;&nbsp;&nbsp;</label>
							<input name="custom_format_text" id="custom_format_text" type="text" value="<?php if ( isset( $date_picker['custom_format_text'] ) ) echo esc_attr( $date_picker['custom_format_text'] ); ?>"/>&nbsp;&nbsp;&nbsp;<span class="description"></span>
							<br />
							<a href="http://docs.jquery.com/UI/Datepicker/formatDate" style="font-size: 11px;">(<?php esc_html_e( 'Documentation on date formatting in jQuery UI.', ACF_TD ); ?>)</a>

						</fieldset>
					</td>
				</tr>
				<tr>
					<td>
						<span class="pickerowtitle titletip" tip="<?php echo esc_attr( $properties['locale']['desc'] ); ?>"><?php echo $properties['locale']['title']; ?>:</span>
					</td>
					<td>
						<select name="locale" id="locale">
							<?php
							foreach ( $this->date_locales as $key => $date_locale ) {
								echo '<option value="' . $key . '" ' . selected( $date_picker['locale'], $key, false ) . '>' . $date_locale . '</option>';
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<span class="pickerowtitle titletip" tip="<?php echo esc_attr( $properties['animation']['desc'] ); ?>"><?php echo $properties['animation']['title']; ?>:</span>
					</td>
					<td>
						<select name="animation" id="animation">
							<option value="show" <?php selected( $date_picker['animation'], "show", true ); ?>><?php esc_html_e( 'Show (default)', ACF_TD ); ?></option>
							<option value="slideDown" <?php selected( $date_picker['animation'], "slideDown", true ); ?>><?php esc_html_e( 'Slide down', ACF_TD ); ?></option>
							<option value="fadeIn" <?php selected( $date_picker['animation'], "fadeIn", true ); ?>><?php esc_html_e( 'Fade in', ACF_TD ); ?></option>
							<option value="" <?php selected( $date_picker['animation'], "", true ); ?>><?php esc_html_e( 'None', ACF_TD ); ?></option>
						</select>
					</td>
				</tr>
				<?php
				unset( $properties['preview'] );
				unset( $properties['date_format'] );
				unset( $properties['custom_format_text'] );
				unset( $properties['animation'] );
				unset( $properties['locale'] );

				foreach ( $properties as $key => $values ) {
					$trclass = ( isset( $values['col'] ) && 0 != $values['col'] ) ? ' class="col' . $values['col'] .' dna-col"' : '';
					?>
					<tr<?php echo $trclass;?>>
						<td>
							<span class="pickerowtitle titletip" tip="<?php echo esc_attr( $values['desc'] ); ?>"><?php echo $values['title']; ?>:</span>
						</td>
						<td>
							<?php $this->get_row_html( $key, $values, $date_picker ); ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th  colspan="2"  style="text-align: right;"><a href="http://docs.jquery.com/UI/Datepicker"><?php esc_html_e( 'jQuery UI Datepicker API Documentation', ACF_TD ); ?></a></th>
				</tr>
			</tfoot>
		</table>
	<?php
	}

	private function get_row_html( $field_name = '', $field_prop = '', $option_field = '' ) {
			$input = array();
			$input['name'] = $field_name;
			$input['value'] = ( isset( $option_field[$field_name] ) ) ? $option_field[$field_name] : '';
			$input['type'] = $field_prop['type'];
			$input['id'] = $input['name'];

			if ( $input['type'] == 'drop-down' ) {
				$input['values'] = $this->get_select_values( $field_name );
				if ( $field_name != 'locale' )
					$input['pls_select'] = '<option value="">' . __( '-- Default --', APP_TD ) . '</option>';
			}

			if ( $input['type'] == 'text area' ) {
				$input['rows'] = 1;
				$input['cols'] = 70;
			}

			acf_tags_html( $input );

	}

	public function update_option(){
		$option = array();
			$properties = array_keys( $this->properties );
			foreach ( $properties as $key ) {
				if ( isset( $_POST[$key] ) )
					$option[$key] = appthemes_clean( $_POST[$key] );
			}
		if ( !empty( $option ) )
			update_option( self::OPTION_NAME, $option );
	}

}