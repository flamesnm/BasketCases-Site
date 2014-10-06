<?php
/********************************************************************
 * ================= DNA FRAMEWORK CONFIGURATION ====================
 * ******************************************************************/
/**
 * DNA Framework object
 */
$acf_admin = new DNA_Framework( ACF_FILE );
$acf_admin->set_textdomain( ACF_TD, plugin_basename( ACF_DIR ) );
$acf_admin->init_action = 'appthemes_add_submenu_page';
$acf_admin->form = array(
		'id'		=> 'acf_options_form',
		'name'		=> 'acf_options_form',
		'class'		=> 'acf_options_form',
		'method'	=> 'post',
		'action'	=> '',
		'enctype'	=> 'multipart/form-data',
	);
$acf_admin->submenu = true;
$acf_admin->parent_slug	= ( '3.2.1' === get_option( 'cp_version' ) || '3.2' === get_option( 'cp_version' ) )
	? 'admin-options.php'
	: 'app-dashboard';

$acf_admin->menu_slug	= 'acf-options';

//put off until the time of the action "init", to provide a translation
function acf_load_titles() {
	global $acf_admin;
	$acf_admin->page_title	= __( 'Advanced Custom Fields for ClassiPress', ACF_TD );
	$acf_admin->menu_title	= __( 'ACF Options', ACF_TD );
}
add_action( 'init', 'acf_load_titles');



/********************************************************************
 * ======================= TABS DEFINITIONS =========================
 * ******************************************************************/
function acf_tabs_array() {
	return apply_filters( 'acf_tabs_array', array(
		'profile'	=> array(
			'title'	=> __( 'Profile Fields', ACF_TD ),
			'desc'	=> __( 'Add, delete, redefine and modify the fields of profiles.', ACF_TD )
		),
		'adfields'	=> array(
			'title'	=> __( 'Ad Fields', ACF_TD ),
			'desc'	=> __( 'Manage ClassiPress Ad Custom Fields', ACF_TD )
		),
		'msgs'		=> array(
			'title'	=> __( 'Validation Error Messages', ACF_TD ),
			'desc'	=> __( 'Edit and Translate Validation Error messages', ACF_TD )
		),
		'datepicker'=> array(
			'title'	=> __( 'Date Picker Settings', ACF_TD ),
			'desc'	=> __( 'Customize DatePicker for your needs', ACF_TD )
		),
		'move'		=> array(
			'title'	=> __( 'Export/Import/Clear Settings', ACF_TD ),
			'desc'	=> __( 'Manage plugin options', ACF_TD )
		),
		'help'		=> array(
			'title'	=> __( 'Help', ACF_TD ),
			'desc'	=> __( 'Help Section.', ACF_TD )
		)
	) );
}
$acf_admin->add_filter( 'tabs_array', 'acf_tabs_array' );

/********************************************************************
 * ======================== VERSIONS CHECK ==========================
 * ******************************************************************/
function acf_self_deactivation( $abort ) {
	global $acf_parent_vers, $acf_admin;
	if ( ! in_array( get_option( 'cp_version' ), $acf_parent_vers ) )
		$abort = sprintf( __( '<h3>Don\'t panic!</h3><p>You are about using plugin <strong>%1$s v%2$s</strong> with <strong>ClassiPress v%3$s</strong>! <br />Versions of plugin and ClassiPress are not compatible. <br />Plugin disabled for security reasons. <br />This is all for your own good :) <br /><br />Please, install compatible version of the %1$s plugin!</p>' ), $acf_admin->page_title, ACF_VER, get_option( 'cp_version' ) );
	return $abort;
}
$acf_admin->add_filter( 'self_deactivation', 'acf_self_deactivation' );


/********************************************************************
 * ========================= OPTIONS DATA ===========================
 * ******************************************************************/
function acf_default_options() {
	require_once ( dirname( __FILE__ ) . '/class-dna-options.php' );
	return DNA_Options::get_default_config();
}
$acf_admin->add_filter( 'default_options', 'acf_default_options' );

function acf_delete_options() {
	$delete = array();
	if ( 'delete' === get_option( 'acf_delete' ) ) {
		require_once ( dirname( __FILE__ ) . '/class-dna-options.php' );
		$delete = array_keys( DNA_Options::$default_config );
	}
	return $delete;
}
$acf_admin->add_filter( 'delete_options', 'acf_delete_options' );

function acf_export_options() {
	global $acf_admin;
	if ( isset( $_GET['dna_action'] ) && 'export' === $_GET['dna_action'] ) {
		require_once ( dirname( __FILE__ ) . '/class-dna-options.php' );
		$acf_admin->export_options( array_keys( DNA_Options::$default_config ) );
	}
}
$acf_admin->add_action( 'dna_viewer', 'acf_export_options' );

/********************************************************************
 * ======================= DNA AJAX ACTIONS =========================
 * ******************************************************************/
function acf_get_option_tab_html( $tab ) {
	$tab_class = acf_get_tab_class( $tab );
	if ( $tab_class ) {
		ob_start();
		$tab_class->register_js();
		$tab_class->print_js_var();
		$tab_class->get_html();
		ob_get_flush();
	}
}
$acf_admin->tab_content( 'acf_get_option_tab_html' );

function acf_update_options( $tab ) {
	global $acf_admin;
	if ( isset( $_FILES['importSettings'] ) && '' !== $_FILES['importSettings']['name'] ) {
		require_once ( dirname( __FILE__ ) . '/class-dna-options.php' );
		$import = array_keys( DNA_Options::$default_config );
		$acf_admin->import_options( $import );
	} elseif ( isset( $_POST['clearSettings'] ) ) {
		$acf_admin->clear_options();
	} else {
		$tab_class = acf_get_tab_class( $tab );
		if ( $tab_class )
			$tab_class->update_option();
	}
}
$acf_admin->submit( 'acf_update_options' );

function acf_get_tab_class( $tab ) {
	require_once ( dirname( __FILE__ ) . '/class-dna-tab.php' );
	$file_name = apply_filters( 'acf_' . $tab . '_tab_path', dirname( __FILE__ ) . '/class-dna-' . $tab . '-tab.php' );
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


/********************************************************************
 * =============== ENQUEUE PLUGIN STYLES & SCRIPTS ==================
 * ******************************************************************/
function acf_enqueue_admin_scripts( $hook ) {
	global $acf_admin;
	if ( $hook !== $acf_admin->hook )
		return;

	 //Enqueue styles, which will loaded for all tabs at header of the options page.
	$acf_admin->add_style( 'acf-admin-style', plugin_dir_url( __FILE__ ) . 'css/acf-admin-style.css', array( 'dna-style' ) );
	//Enqueue scripts, which will loaded for all tabs at header of the options page.
	//If need load script for certain tab - use method register_script() in tab class file.
	$acf_admin->add_script( 'datepicker_locales', ACF_URL . '/js/jquery-ui-i18n.min.js', array('jquery') );

	if ( ! defined( 'CP_VERSION' ) )
		$acf_admin->add_script( 'morevalidate', get_template_directory_uri() . '/includes/js/validate/additional-methods.js', array('validate') );
	else
		$acf_admin->add_script( 'morevalidate', get_template_directory_uri() . '/framework/js/validate/additional-methods.js', array('validate') );

	$acf_admin->add_script( 'ValidateFix_script', ACF_URL . '/js/ValidateFix.js', array('morevalidate') );
	$acf_admin->add_script( 'acf-admin-script', plugin_dir_url( __FILE__ ) . 'js/acf-admin-script.js', array( 'dna-script' ) );
}
add_action( 'admin_enqueue_scripts', 'acf_enqueue_admin_scripts' );

/********************************************************************
 * ========================== VIEWERS ===============================
 * ******************************************************************/
function acf_new_ad_field(){
	if ( isset( $_GET['page'] ) && 'fields' === $_GET['page'] ) {
		if ( isset( $_GET['action'] ) && 'addfield' === $_GET['action'] ) {
			if ( isset( $_POST['submitted'] ) ) {
			   $fldname = cp_make_custom_name( $_POST['field_label'] );
				$acf_ad_fields = get_option( 'acf_ad_fields' );
				$ad_fields_class = acf_get_tab_class( 'adfields' );
				$ad_field_props = array_keys( $ad_fields_class->properties );

				foreach ( $ad_field_props as $ad_field_prop ) {
					$optvar = '';
					switch ( $ad_field_prop ) {
						case 'name':
							continue 2;
							break;
						case 'new_ad_display':
						case 'edit_ad_display':
						case 'single_ad_display':
							$optvar = 'yes';
							break;
						default:
							break;
					}
					$acf_ad_fields[ $fldname ][ $ad_field_prop ] = $optvar;
				}
				update_option( 'acf_ad_fields', $acf_ad_fields );
			}
		}
	}
}
$acf_admin->add_action( 'dna_viewer', 'acf_new_ad_field' );