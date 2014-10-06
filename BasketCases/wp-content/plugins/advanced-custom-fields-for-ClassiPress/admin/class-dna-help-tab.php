<?php
class DNA_Help_Tab extends DNA_Tab {
	public $help_contents;

	public function __construct( $tab ) {
		parent::__construct( $tab );
		$this->help_contents = $this->help_contents();
	}

	function help_contents() {
		return array(
		1 => array(
				'title' => __( 'Browse', ACF_TD ),
				'callback' => 'browse'
			),
			array(
				'title' => __( 'Installation/Update', ACF_TD ),
				'callback' => 'install'
			),
			array(
				'title' => __( 'Localization', ACF_TD ),
				'callback' => 'languages'
			),
			array(
				'title' => __( 'Profile Fields', ACF_TD ),
				'callback' => 'profile_fields'
			),
			array(
				'title' => __( 'Ad Fields', ACF_TD ),
				'callback' => 'ad_fields'
			),
			array(
				'title' => __( 'Error Messages', ACF_TD ),
				'callback' => 'err_msgs'
			),
			array(
				'title' => __( 'Formats & Limitations', ACF_TD ),
				'callback' => 'formats'
			),
			array(
				'title' => __( 'Date Picker Settings', ACF_TD ),
				'callback' => 'date'
			),
			array(
				'title' => __( 'Export/Import/Clear Settings', ACF_TD ),
				'callback' => 'move'
			),
			array(
				'title' => __( 'ACF API Docs', ACF_TD ),
				'callback' => 'hooks'
			),
			array(
				'title' => __( 'Video Tutorials', ACF_TD ),
				'callback' => 'tuts'
			),
			array(
				'title' => __( 'Contacts', ACF_TD ),
				'callback' => 'contacts'
			),
		);
	}

	public function get_html(){
		?>
		<div id="tabs">
			<ul>
				<?php foreach ( $this->help_contents as $key => $value ) { ?>
					<li class="help-menu"><a href="#tabs-<?php echo $key; ?>"><?php echo $value['title']; ?></a></li>
				<?php } ?>
			</ul>
			<?php foreach ( $this->help_contents as $key => $value ) { ?>
				<div id="tabs-<?php echo $key; ?>" style="display: table-cell;">
					<h2><?php echo $value['title']; ?></h2>
						<?php $this->get_section( $value['callback'] ); ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	// for front-end help
	public function get_section( $callback ) {
		$callback = $callback . '_help';
		$this->$callback();
	}
	// for front-end help
	private function get_tab_class( $tab ){
		$file_name = dirname( __FILE__ ) . '/class-dna-' . $tab . '-tab.php';
		if ( file_exists( $file_name ) )
			require_once ( $file_name );
		$class_name = 'DNA_' . ucfirst( $tab ) . '_Tab';
		if ( class_exists( $class_name ) ) {
			$obj = apply_filters( 'acf_' . $tab . '_object', new $class_name( $tab ) );
			$obj->properties = apply_filters( 'acf_' . $tab . '_properties', $obj->properties );
			$obj->subtabs = apply_filters( 'acf_' . $tab . '_subtab', $obj->subtabs );
			return $obj;
		}
	}

	private function list_properties( $tab, $properties = array() ){
		?><div>
			<table class="widefat">
				<thead>
					<tr>
						<th style="width:150px;"><strong><?php _e( 'Property', ACF_TD ); ?>:</strong></th>
						<th><strong><?php _e( 'Description', ACF_TD ); ?>:</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
						$tab_obj = $this->get_tab_class( $tab );
						$properies = $tab_obj->properties;
					foreach ( $properies as $value ) { ?>
						<tr>
							<td style="width:150px;"><span class="format_name"><?php echo $value[ 'title' ]; ?>:</span></td>
							<td><?php echo $value[ 'desc' ]; ?> </td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div><?php
	}

	private function browse_help(){
		?>
		<p><?php _e( 'The Advanced Custom Fields for ClassiPress(ACFCP) plugin is a powerful tool for extending the ClassiPress theme.', ACF_TD ); ?></p>
		<p><?php _e( 'It allows you to add and completely control extra Profile fields and custom Ad fields. Also ACFCP Plugin allows the interaction of custom profile fields with custom ad fields and vice versa.', ACF_TD ); ?></p>
		<p><?php _e( 'ACF adds many of the most requested features for ClassiPress:', ACF_TD ); ?></p>
		<ul>
			<li><?php _e( 'Extra profile fields', ACF_TD ); ?></li>
			<li><?php _e( 'Different types of profile fields', ACF_TD ); ?></li>
			<li><?php _e( 'Formats of ad and profile fields (including Date Field with fully customizable Datepicker and validation of input values)', ACF_TD ); ?></li>
			<li><?php _e( 'Transform of ad and profile fields values (Capitalize, UPPERCASE, lowercase)', ACF_TD ); ?></li>
			<li><?php _e( 'Private fields (private allows only the author and administrators to see an ad or profile field while hiding it from other visitors and search engines)', ACF_TD ); ?></li>
			<li><?php _e( 'Display fields only for logged-in users', ACF_TD ); ?></li>
			<li><?php _e( 'Limit the number of input symbols, words', ACF_TD ); ?></li>
			<li><?php _e( 'Field formats validation and restrictions. <br />Double-checking of Ad fileds and Profile fields during registration and editing. <br />Checking occurs on the client side, and then on the server side. <br />This provides advanced protection for your site from uninvited guests. <br />Validation of fields on the client side allows the user to obtain accurate and structured information on the ad', ACF_TD ); ?></li>
			<li><?php _e( 'Custom validation messages (edit messages in your language and help your users fill forms properly)', ACF_TD ); ?></li>
			<li><?php _e( 'Default values for all fields', ACF_TD ); ?></li>
			<li><?php _e( 'Inherit values of the profile field in the ad field', ACF_TD ); ?></li>
			<li><?php _e( 'Control output fields on the various pages and forms:', ACF_TD ); ?>
				<ul>
					<li><?php _e( 'Registration user form', ACF_TD ); ?></li>
					<li><?php _e( 'Edit profile form', ACF_TD ); ?></li>
					<li><?php _e( 'Author’s page', ACF_TD ); ?></li>
					<li><?php _e( 'User sidebar', ACF_TD ); ?></li>
					<li><?php _e( 'Single ad page (usual field list)', ACF_TD ); ?></li>
					<li><?php _e( 'Single ad page separated area (style customizable block)', ACF_TD ); ?></li>
					<li><?php _e( 'Poster tab in Ad sidebar', ACF_TD ); ?></li>
					<li><?php _e( 'Ad Loops after title', ACF_TD ); ?></li>
					<li><?php _e( 'Ad Loop after description', ACF_TD ); ?></li>
					<li><?php _e( 'New ad form', ACF_TD ); ?></li>
					<li><?php _e( 'Edit ad form', ACF_TD ); ?></li>
				</ul>
			</li>
			<li><?php _e( 'Ability to add custom CSS styles to fields output', ACF_TD ); ?></li>
			<li><?php _e( 'Ability to add the patch CSS files to be compatible with the style of your child theme', ACF_TD ); ?></li>
			<li><?php _e( 'Plugin settings can be exported to INI file, or imported from another file', ACF_TD ); ?></li>
			<li><strong><?php _e( 'Compatible with WordPress Multisite.', ACF_TD ); ?></strong></li>
			<li><strong><?php _e( 'Has own documented API', ACF_TD ); ?></strong></li>
		</ul>
		<p><strong><?php _e( 'With this plugin you can easy create the most popular profile form fields such as:', ACF_TD ); ?></strong></p>
		<ul>
			<li><?php _e( '“First Name” and “Last Name” – core WordPress profile fields, which you can add to registration form', ACF_TD ); ?></li>
			<li><?php _e( '“Accept Terms of Service” – checkbox for your registration form', ACF_TD ); ?></li>
			<li><?php _e( '“User Tax ID” – Private profile field, which will see only Administrator (You) and the user (ad owner)', ACF_TD ); ?></li>
			<li><?php _e( '“Country” / “State” / “City” / “Phone number” – Location fields, which can automatically fill in “Add new ad” form. Members who submit a lot of ads on your site – will be happy.', ACF_TD ); ?></li>
			<li><?php _e( '“Date field” - Date of any format and location', ACF_TD ); ?></li>
		</ul>
		<p><?php _e( 'And many others …', ACF_TD ); ?></p>
		<p><strong><?php _e( 'Add any formats to the ad and profile form fields:', ACF_TD ); ?></strong></p>
		<p><?php _e( 'If you have specific ad or profile fields such as: “Phone number” or “ZipCode”, which required specific formats of values – select neccessary format from the list of proposed (there are number, digits, phone, email, url, alfanumeric, etc…)', ACF_TD ); ?></p>
		<p><strong><?php _e( 'Limit the input data from the user through forms:', ACF_TD ); ?></strong></p>
		<p><?php _e( 'You can add limits to fill numbers, lenght, words, words sets – by criteria min / max / range', ACF_TD ); ?></p>
		<p><strong><?php _e( 'Using ACF API you can add:', ACF_TD ); ?></strong></p>
		<ul>
			<li><span style="line-height: 14px"><?php _e( 'New Visibility options', ACF_TD ); ?></span></li>
			<li><?php _e( 'New Value modifiers', ACF_TD ); ?></li>
			<li><?php _e( 'New Value formats and restrictions', ACF_TD ); ?></li>
			<li><?php _e( 'New Plugin options to provide control of new created features', ACF_TD ); ?></li>
		</ul>
		<p><?php _e( 'The plugin contains a very detailed help section for each property and method. Tooltips will assist you in customizing the plugin.', ACF_TD ); ?></p>
		<p><a target="_new" href="http://forums.appthemes.com/acfcp-plugin-v1-0-help-section-36073/"><?php _e( 'Help Section', ACF_TD ); ?></a> <?php _e( 'is duplicated on', ACF_TD ); ?> <a title="Support forum" href="http://forums.appthemes.com/advanced-custom-fields/"><?php _e( 'Support forum', ACF_TD ); ?></a>, <?php _e( 'so You can read all about plugin before purchase!!!', ACF_TD ); ?></p>
		<p><a target="_new" href="http://forums.appthemes.com/advanced-custom-fields/acfcp-api-guide-50448/"><?php _e( 'ACF API Docs', ACF_TD ); ?></a> <?php _e( 'published on the Support Forum and provides detailed documentation with real life examples.', ACF_TD ); ?></p>
		<p><?php _e( 'There are plans to update the plugin for each new version of the ClassiPress and adding more new features and enhancements over time.', ACF_TD ); ?></p>
		<h3><?php _e( 'The Plugin interface', ACF_TD ); ?></h3>
			<p><?php printf( __( 'Plugin page can be accessed from the main administration menu ClassiPress-&gt; %s.', ACF_TD ), __( 'ACF Options', ACF_TD ) ); ?></p>
			<p><?php _e( 'Plugin settings is divided into tabs with tables of settings:', ACF_TD ); ?></p>
		<ul>
			<?php
			$tabs = acf_tabs_array();
			foreach ( $tabs as $tab ) {
				echo "<li><strong>{$tab['title']}</strong> - {$tab['desc']}</li>";
			} ?>
		</ul>
		<p><?php printf( __( 'To save the results click "%s".', ACF_TD ), __( 'Save Changes', ACF_TD ) ); ?><p>
		<p><?php _e( 'Detailed information about each tab read the specific Help Page.', ACF_TD ); ?><p>
		<?php
	}

	private function install_help() {
		?>
		<p><?php _e( 'The Plugin developed for a specific version of ClassiPress! After each update ClassiPress Plugin source code review. This may take some time.', ACF_TD ); ?></p>
		<p><?php _e( 'Be prepared that if you upgrade ClassiPress before the release of upgrade Plugin – the Plugin turns off (for not to cause potential errors). The Plugin resume work on the condition that his version is compatible with the version of ClassiPress.', ACF_TD ); ?></p>
		<h3><?php _e( 'Manual installation', ACF_TD ); ?>:</h3>
		<ol>
			<li><?php _e( 'Download the Plugin zip file advanced-custom-fields-for-classipress.zip', ACF_TD ); ?></li>
			<li><?php _e( 'Open WP Admin dashboard -&gt; Plugins -&gt; Add new -&gt; Upload', ACF_TD ); ?></li>
			<li><?php _e( 'Upload and activate the Plugin from Plugins page.', ACF_TD ); ?></li>
			<li><?php _e( 'Find the menu “ClassiPress” on the WordPress administration page. Select the lower point “ACF options” and proceed to configure the Plugin.', ACF_TD ); ?></li>
		</ol>

		<h3><?php _e( 'Manual update', ACF_TD ); ?>:</h3>
		<ol>
			<li><?php _e( 'Export ACF plugin settings to the INI file (just in case)', ACF_TD ); ?></li>
			<li><?php _e( 'Deactivate and Delete installed ACF plugin from plugins page', ACF_TD ); ?></li>
			<li><?php _e( 'Repeat all steps from manual install (see above)', ACF_TD ); ?></li>
		</ol>
		<?php
	}

	private function profile_fields_help() {
		?>
		<p><?php _e( 'Configure additional profile fields are presented in tabular form. Where rows - additional profile fields, and columns - the properties of these fields. Since the properties of the fields were many, I had to divide them into several groups.', ACF_TD ); ?></p>
		<p><?php printf( __( 'So you can see four groups of the properties of the field profile: "%s", "%s", "%s", "%s".', ACF_TD ), __( 'Main Properties', ACF_TD ), __( 'Formats & Limitations', ACF_TD ), __( 'Labels & Descriptions', ACF_TD ), __( 'Display Options', ACF_TD ) ); ?></p>
		<ul>
			<li><?php printf( __( 'If you want to add a new row of the field - click "%s" button at the bottom of the table.', ACF_TD ), __( 'Add field', ACF_TD ) ); ?></li>
			<li><?php _e( 'To remove an existing field, click the X in the right end of the line.', ACF_TD ); ?></li>
			<li><?php printf( __( 'To save the results click "%s".', ACF_TD ), __( 'Save Changes', ACF_TD ) ); ?></li>
		</ul>
		<p><?php _e( 'Below you will find descriptions of all additional properties of the profile field.', ACF_TD ); ?></p>
		<?php $this->list_properties( 'profile' );
	}

	private function err_msgs_help() {
		?>
		<p><?php printf( __( 'Tab "%s" allows you to edit the default validation messages.', ACF_TD ), __( 'Validation Error Messages', ACF_TD ) ); ?></p>
		<p><?php _e( 'Most of the messages was inherited from the  <a target="_new" href="http://bassistance.de/jquery-plugins/jquery-plugin-validation/">jQuery.Validation</a> plugin. Others I have written myself. <br />These messages can be translated to your language is not correctly, so I gave you an opportunity to change them.', ACF_TD ); ?></p>
			<div class="updated">
				<p><?php _e( 'Pay attention to the messages with the characters "<strong>{0}</strong>".', ACF_TD ); ?></p>
				<p><?php _e( 'I recommend not to change them, as it will be replaced in the message attribute of the method validation.', ACF_TD ); ?></p>
			</div>
		<?php
	}

	private function ad_fields_help() {
		?>
		<p><?php _e( 'Configuring ad fields represented as a table, as well as configuring profile fields. <br />Where rows - ad fields, and columns - the properties of these fields.', ACF_TD ); ?></p>
		<p><?php _e( 'Below you will find descriptions of all additional properties of the ad fields.', ACF_TD ); ?></p>
		<?php $this->list_properties( 'adfields' );
	}

	private function formats_help() {
		?>
		<p><?php echo __( 'Formats and limitations will allow you to get a more ordered information from the user.', ACF_TD ) . ' <br />' . sprintf( __( 'You can set the format for email or url, or number, or simply required etc. <br />See detailed description of formats and limitations in the Help topic "%s".', ACF_TD ), __( 'Formats & Limitations', ACF_TD ) ); ?></p>

		<h3><?php _e( 'How does it work?', ACF_TD ); ?></h3>
		<p><?php _e( 'So, what need to do to get from users only necessary information?', ACF_TD ); ?></p>
			<ol>
				<li><?php _e( 'Set the format of the field (such as numeric, text, date, etc.).', ACF_TD ); ?></li>
				<li><?php _e( 'Limit data entry to certain criteria (for example, set the maximum number of characters, words, or range of numbers, etc.).', ACF_TD ); ?></li>
				<li><?php _e( 'Mark the required fields.', ACF_TD ); ?></li>
				<li><?php _e( 'And most importantly: Have the ability to programmatically check on all of these criteria is that user enters! And if necessary, tell user what he did wrong.', ACF_TD ); ?></li>
			</ol>
		<p><?php _e( 'ACF plugin functional can solve all of these tasks.', ACF_TD ); ?></p>
		<p><?php _e( 'So, all in order.', ACF_TD ); ?></p>
			<ol>
				<li><h4><?php _e( 'Formats', ACF_TD ); ?>:</h4>
					<p><?php printf( __( 'You can choose the format of the field using the drop-down list "%s".', ACF_TD ), __( 'Format Of Values', ACF_TD ) ); ?></p>
					<p><?php _e( 'This can be: <br />The default format (or nothing or anything, except the bad characters and tags); <br />Any of the suggested format (the user can enter a value in the selected format, or leave blank); <br />Required field (requires the user to enter anything, no matter what).', ACF_TD ); ?></p>
				</li>
				<li><h4><?php _e( 'Limitations', ACF_TD ); ?>:</h4>
					<p><?php _e( 'You can limit entered words, symbols, collocations or numbers.', ACF_TD ); ?></p>
					<p><?php printf( __( 'You can choose the limitation of the field using the drop-down list "%s", setting out the attribute of the limitation in text field "%s".', ACF_TD ), __( 'Limitations Of Values', ACF_TD ), __( 'Limitations Attributes', ACF_TD ) ); ?><br />
						<?php _e( 'Methods of limiting are divided into three types: <br />Maximum - Sets the upper limit of the input data. The user can enter data is not above the specified limits, including leave blank; <br />Minimum - Sets the lower limit of the input data. The user can enter data is not below the specified limits; <br />Range - Sets the range, ie upper and lower limits (safest option).', ACF_TD ); ?></p>
				</li>
				<li><h4><?php _e( 'Appointment required for input fields', ACF_TD ); ?>:</h4>
					<p><?php _e( 'Available in 2 variants: <br /> If you choose a format of the field (but not "required") - you can specify input limitations such as "rangelength" with attributes "1,100". Then user must enter at least one character or more but less than 100. <br />If you do not have special format for the field, but this field is required, then select the format "required" or, as in the previous case, use restrictions.', ACF_TD ); ?></p>
				</li>
				<li><h4><?php _e( 'Validation', ACF_TD ); ?>:</h4>
					<p><?php _e( 'Each format or limitation is the specific method of verification fields. If the user enters data in the wrong format, then pressing the Save button - displays a message about incorrect entry. In this case sending data to the server will not happen.', ACF_TD ); ?></p>
					<p><?php _e( 'Verification of fields is performed on the client side and server side. <br />On the client side validation works in real time and the user immediately sees what he has done wrong. <br />This feature is provided by plug-jQuery.Validation. This is very handy, but it has some drawbacks. <br />For example, if a user will prevent execution of JavaScript in their browser, the client-side validation does not happen at all. <br />In order to avoid this vulnerability and maximally protect the registration page and edit profile page, I added to the plugin the same verification methods, but running on the server side. <br />Therefore, if the user disables javascript, validation will still be done (you can try).', ACF_TD ); ?></p>
					<p><?php _e( 'After validation, in any case, all data is cleaned on the server side before saving them to the site database.', ACF_TD ); ?></p>
				</li>
			</ol>
		<p><?php _e( 'Below is a list of all methods of data validation. <br />This list is open, you can send a request to the method that you want and I\'ll add it (if it\'s real :)).', ACF_TD ); ?></p>
		<p class="updated"><?php _e( 'I recommend to read carefully the description of the methods before adding them to the fields of forms.', ACF_TD ); ?></p>
			<div>
				<table class="widefat">
					<thead>
						<tr>
							<th style="width:125px;">
								<strong><?php _e( 'Method name', ACF_TD ); ?>:</strong>
							</th>
							<th><strong><?php _e( 'Description', ACF_TD ); ?>:</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$formats = $this->get_field_formats();
						foreach ( $formats as $key => $value ) { ?>
							<tr>
								<td style="width:125px;"><span class="format_name"><?php echo $key; ?>:</span></td>
								<td><?php echo $value[ 'desc' ]; ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php
	}

	private function move_help() {
		echo '<p>' . __( 'This section will help you manage your configuration settings of this Plugin.', ACF_TD ) . '</p>';
		$this->list_properties( 'move' );
	}

	private function date_help() {
		?>
		<p><?php printf( __( 'The <a target="_new" href="%s">jQuery UI Datepicker</a> is a highly configurable plugin that adds datepicker functionality to your ad and profile fields. <br />You can customize the date format and language, restrict the selectable date ranges and add in buttons and other navigation options easily.', ACF_TD ), 'http://docs.jquery.com/UI/Datepicker' ); ?></p>
		<p><?php _e( 'All you need to do - is to set up Datepicker according to your needs and select format "dateCustom" for desired profile field, or ad field.', ACF_TD ); ?></p>
		<p><?php _e( 'Below is a list of customizable properties of Datepicker.', ACF_TD ); ?></p>
		<?php  $this->list_properties( 'datepicker' );
	}

	private function contacts_help() {
		?>
		<p><?php _e( 'Hello!', ACF_TD ); ?></p>
		<p><?php _e( 'My name is Artem Frolov and I am developer of this plugin.', ACF_TD ); ?> <br />
			<?php printf( __( 'You can find me on the AppThemes forum under the nickname - <a target="_new" href="%s">dikiyforester</a>.', ACF_TD ), 'http://forums.appthemes.com/members/dikiyforester/' ); ?></p>
		<p><?php printf( __( 'Welcome to <a target="_new" href="%s">my circles on Google+</a>.', ACF_TD ), 'https://plus.google.com/104376609295439996587/' ); ?></p>
		<p><?php printf( __( 'If you have any questions or suggestions on the use of plugin, or you find an error, write me on the <a target="_new" href="%s">Support Forum</a>.', ACF_TD ), 'http://forums.appthemes.com/advanced-custom-fields/' ); ?></p>
		<?php
	}

	private function tuts_help() {
		?>
		<p><?php _e( 'As you already know, ACF Plugin is not easy to set up, in spite of the fact that I was trying to make it more simple.', ACF_TD ); ?></p>
		<p><?php _e( 'I\'m starting to record video tutorials on working with plug-in.', ACF_TD ); ?></p>
		<p><?php printf( __( 'Find all tutorials you can on this page <a target="_new" href="%s">Video Tuts for installing and using ACFCP Plugin</a>.', ACF_TD ), 'http://forums.appthemes.com/video-tuts-installing-using-acfcp-plugin-36003/' ); ?></p>
		<p><?php _e( 'Here I will show examples of setting plug and major features.', ACF_TD ); ?></p>
		<?php
	}

	private function languages_help() {
		?>
		<p><?php _e( 'Starting with plugin version 2.3, was implemented internationalization system of plugin settings content.', ACF_TD ); ?></p>
		<p><?php printf( __( 'Language Packs and Portable Object Template are located in the folder <code>%s</code>', ACF_TD ), '/wp-content/plugins/advanced-custom-fields-for-ClassiPress/languages/' ); ?></p>
		<h3><?php _e( 'Installing Language Packs', ACF_TD ); ?></h3>
		<ol>
			<li><?php printf( __( 'Open folder <code>%s</code>', ACF_TD ), '/wp-content/plugins/advanced-custom-fields-for-ClassiPress/languages/' ); ?></li>
			<li><?php _e( 'Find Pack file for your language with name <code>advanced-custom-fields-for-ClassiPress-[LANGUAGE CODE]_[COUNTRY CODE].mo</code>', ACF_TD ); ?>*. <br />
				<?php printf( __( 'See the complete list of <a target="_new" href="%s">language codes</a> and <a target="_new" href="%s">country codes</a> to find your exact locale.', ACF_TD ), 'http://www.gnu.org/software/gettext/manual/html_chapter/gettext_16.html#Language-Codes', 'http://www.gnu.org/software/gettext/manual/html_chapter/gettext_16.html#Country-Codes' ); ?>
			</li>
			<li><?php printf( __( 'Copy this file to WordPress common localization folder <code>%s</code>', ACF_TD ), '/wp-content/languages/plugins/' );?></li>
			<li><?php printf( __( 'Open "%s" page and start to work with plugin.', ACF_TD ), __( 'ACF Options', ACF_TD ) );?></li>
		</ol>
		<p><em>* <?php printf( __( 'Note: If a language pack doesn’t exist for your language, you’ll have to create it yourself. AppThemes "<a target="_new" href="%s">How to Translate a WordPress Theme</a>" tutorial will explain how to do it.', ACF_TD ), 'http://www.appthemes.com/blog/how-to-translate-a-wordpress-theme/' );?></em></p>
		<p><em><?php _e( 'If you have translated plugin yourself, you can contact me and send the language pack. I’ll include your files in the plugin package for further uses.', ACF_TD ); ?></em></p>
		<?php
	}

	private function hooks_help() {
		echo '<p>' . sprintf( __( 'Detailed ACF API Documentation you can find on Support Forum in <a target="_new" title="Help Section" href="%s">this</a> thread.', ACF_TD ), 'http://forums.appthemes.com/advanced-custom-fields/acfcp-api-guide-50448/' ) . '</p>';
	}

	public function __call( $name, $arguments ) {
		return false;
	}
}