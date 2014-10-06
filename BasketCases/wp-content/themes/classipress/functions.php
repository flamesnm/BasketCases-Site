<?php
/**
 * Theme functions file
 *
 * DO NOT MODIFY THIS FILE. Make a child theme instead: http://codex.wordpress.org/Child_Themes
 *
 * @package ClassiPress
 * @author AppThemes
 */

// Constants
define( 'CP_VERSION', '3.3.3' );
define( 'CP_DB_VERSION', '2103' );

define( 'APP_POST_TYPE', 'ad_listing' );
define( 'APP_TAX_CAT', 'ad_cat' );
define( 'APP_TAX_TAG', 'ad_tag' );

define( 'CP_ITEM_LISTING', 'ad-listing' );
define( 'CP_ITEM_MEMBERSHIP', 'membership-pack' );

define( 'APP_TD', 'classipress' );

global $cp_options;

// Legacy variables - some plugins rely on them
$app_theme = 'ClassiPress';
$app_abbr = 'cp';
$app_version = '3.3.3';
$app_db_version = 2103;
$app_edition = 'Ultimate Edition';


// Framework
require_once( dirname( __FILE__ ) . '/framework/load.php' );
require_once( APP_FRAMEWORK_DIR . '/includes/stats.php' );
require_once( APP_FRAMEWORK_DIR . '/admin/class-meta-box.php' );

APP_Mail_From::init();

// define the transients we use
$app_transients = array( 'cp_cat_menu' );

// define the db tables we use
$app_db_tables = array( 'cp_ad_fields', 'cp_ad_forms', 'cp_ad_geocodes', 'cp_ad_meta', 'cp_ad_packs', 'cp_ad_pop_daily', 'cp_ad_pop_total', 'cp_coupons', 'cp_order_info' );

// register the db tables
foreach ( $app_db_tables as $app_db_table ) {
	scb_register_table( $app_db_table );
}
scb_register_table( 'app_pop_daily', 'cp_ad_pop_daily' );
scb_register_table( 'app_pop_total', 'cp_ad_pop_total' );


$load_files = array(
	'payments/load.php',
	'options.php',
	'appthemes-functions.php',
	'actions.php',
	'comments.php',
	'core.php',
	'cron.php',
	'deprecated.php',
	'enqueue.php',
	'emails.php',
	'functions.php',
	'hooks.php',
	'payments.php',
	'profile.php',
	'search.php',
	'security.php',
	'stats.php',
	'views.php',
	'widgets.php',
);
appthemes_load_files( dirname( __FILE__ ) . '/includes/', $load_files );

$load_classes = array(
	'CP_Blog_Archive',
	'CP_Ads_Home',
	'CP_Ads_Categories',
	'CP_Add_New',
	'CP_Ad_Single',
	'CP_Edit_Item',
	'CP_Order_Summary',
	'CP_Membership',
	'CP_User_Dashboard',
	'CP_User_Profile',
);
appthemes_add_instance( $load_classes );


// Admin only
if ( is_admin() ) {
	require_once( APP_FRAMEWORK_DIR . '/admin/importer.php' );

	$load_files = array(
		'admin.php',
		'dashboard.php',
		'enqueue.php',
		'install.php',
		'importer.php',
		'options.php',
		'settings.php',
		'system-info.php',
		'updates.php',
	);
	appthemes_load_files( dirname( __FILE__ ) . '/includes/admin/', $load_files );

	$load_classes = array(
		'CP_Theme_Dashboard',
		'CP_Theme_Settings_General' => $cp_options,
		'CP_Theme_Settings_Emails' => $cp_options,
		'CP_Theme_Settings_Pricing' => $cp_options,
		'CP_Theme_System_Info',
	);
	appthemes_add_instance( $load_classes );
}


// Frontend only
if ( ! is_admin() ) {

	cp_load_all_page_templates();
}

// Constants
define( 'CP_DASHBOARD_URL', get_permalink( CP_User_Dashboard::get_id() ) );
define( 'CP_PROFILE_URL', get_permalink( CP_User_Profile::get_id() ) );
define( 'CP_EDIT_URL', get_permalink( CP_Edit_Item::get_id() ) );
define( 'CP_ADD_NEW_URL', get_permalink( CP_Add_New::get_id() ) );
define( 'CP_MEMBERSHIP_PURCHASE_URL', get_permalink( CP_Membership::get_id() ) );


// Theme supports
add_theme_support( 'app-versions', array(
	'update_page' => 'admin.php?page=app-settings&firstrun=1',
	'current_version' => CP_VERSION,
	'option_key' => 'cp_version',
) );

add_theme_support( 'app-wrapping' );

add_theme_support( 'app-login', array(
	'login' => 'tpl-login.php',
	'register' => 'tpl-registration.php',
	'recover' => 'tpl-password-recovery.php',
	'reset' => 'tpl-password-reset.php',
	'redirect' => $cp_options->disable_wp_login,
	'settings_page' => 'admin.php?page=app-settings&tab=advanced',
) );

add_theme_support( 'app-feed', array(
	'post_type' => APP_POST_TYPE,
	'blog_template' => 'index.php',
	'alternate_feed_url' => $cp_options->feedburner_url,
) );

add_theme_support( 'app-open-graph', array(
	'default_image' => ! empty( $cp_options->logo ) ? $cp_options->logo : appthemes_locate_template_uri( 'images/cp_logo_black.png' ),
) );

add_theme_support( 'app-payments', array(
	'items' => array(
		array(
			'type' => CP_ITEM_LISTING,
			'title' => __( 'Listing', APP_TD ),
			'meta' => array(),
		),
		array(
			'type' => CP_ITEM_MEMBERSHIP,
			'title' => __( 'Membership', APP_TD ),
			'meta' => array(),
		),
	),
	'items_post_types' => array( APP_POST_TYPE ),
	'options' => $cp_options,
) );

add_theme_support( 'app-price-format', array(
	'currency_default' => $cp_options->currency_code,
	'currency_identifier' => $cp_options->currency_identifier,
	'currency_position' => $cp_options->currency_position,
	'thousands_separator' => $cp_options->thousands_separator,
	'decimal_separator' => $cp_options->decimal_separator,
	'hide_decimals' => $cp_options->hide_decimals,
) );

add_theme_support( 'app-plupload', array(
	'max_file_size' => $cp_options->max_image_size,
	'allowed_files' => $cp_options->num_images,
	'disable_switch' => false,
) );

add_theme_support( 'app-stats', array(
	'cache' => 'today',
	'table_daily' => 'cp_ad_pop_daily',
	'table_total' => 'cp_ad_pop_total',
	'meta_daily' => 'cp_daily_count',
	'meta_total' => 'cp_total_count',
) );

add_theme_support( 'post-thumbnails' );

add_theme_support( 'automatic-feed-links' );

// AJAX
add_action( 'wp_ajax_nopriv_ajax-tag-search-front', 'cp_suggest' );
add_action( 'wp_ajax_ajax-tag-search-front', 'cp_suggest' );

add_action( 'wp_ajax_nopriv_dropdown-child-categories', 'cp_addnew_dropdown_child_categories' );
add_action( 'wp_ajax_dropdown-child-categories', 'cp_addnew_dropdown_child_categories' );


// Image sizes
set_post_thumbnail_size( 100, 100 ); // normal post thumbnails
add_image_size( 'blog-thumbnail', 150, 150 ); // blog post thumbnail size
add_image_size( 'sidebar-thumbnail', 50, 50, true ); // sidebar blog thumbnail size
add_image_size( 'ad-thumb', 75, 75, true );
add_image_size( 'ad-small', 100, 100, true );
add_image_size( 'ad-medium', 250, 250, true );
//add_image_size( 'ad-large', 500, 500 );


// Set the content width based on the theme's design and stylesheet.
// Used to set the width of images and content. Should be equal to the width the theme
// is designed for, generally via the style.css stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 500;


appthemes_init();
