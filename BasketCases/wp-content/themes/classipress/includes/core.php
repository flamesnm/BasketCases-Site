<?php

/**
 * Register custom post type for ads
 */
function cp_register_post_types() {
	global $cp_options;

	// register post type for ads
	$labels = array(
		'name' => __( 'Ads', APP_TD ),
		'singular_name' => __( 'Ad', APP_TD ),
		'add_new' => __( 'Add New', APP_TD ),
		'add_new_item' => __( 'Create New Ad', APP_TD ),
		'edit' => __( 'Edit', APP_TD ),
		'edit_item' => __( 'Edit Ad', APP_TD ),
		'new_item' => __( 'New Ad', APP_TD ),
		'view' => __( 'View Ads', APP_TD ),
		'view_item' => __( 'View Ad', APP_TD ),
		'search_items' => __( 'Search Ads', APP_TD ),
		'not_found' => __( 'No ads found', APP_TD ),
		'not_found_in_trash' => __( 'No ads found in trash', APP_TD ),
		'parent' => __( 'Parent Ad', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'description' => __( 'This is where you can create new classified ads on your site.', APP_TD ),
		'public' => true,
		'show_ui' => true,
		'has_archive' => true,
		'capability_type' => 'post',
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'menu_position' => 8,
		'menu_icon' => appthemes_locate_template_uri( 'images/admin-icon.png' ),
		'hierarchical' => false,
		'rewrite' => array( 'slug' => $cp_options->post_type_permalink, 'with_front' => false, 'feeds' => true ),
		'query_var' => true,
		'supports' => array( 'title', 'editor', 'author', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky' ),
	);

	register_post_type( APP_POST_TYPE, $args );

}
add_action( 'init', 'cp_register_post_types', 9 );


/**
 * Register taxonomies for ads
 */
function cp_register_taxonomies() {
	global $cp_options;

	// register the category taxonomy for ads
	$labels = array(
		'name' => __( 'Ad Categories', APP_TD ),
		'singular_name' => __( 'Ad Category', APP_TD ),
		'search_items' => __( 'Search Ad Categories', APP_TD ),
		'all_items' => __( 'All Ad Categories', APP_TD ),
		'parent_item' => __( 'Parent Ad Category', APP_TD ),
		'parent_item_colon' => __( 'Parent Ad Category:', APP_TD ),
		'edit_item' => __( 'Edit Ad Category', APP_TD ),
		'update_item' => __( 'Update Ad Category', APP_TD ),
		'add_new_item' => __( 'Add New Ad Category', APP_TD ),
		'new_item_name' => __( 'New Ad Category Name', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_ui' => true,
		'query_var' => true,
		'update_count_callback' => '_update_post_term_count',
		'rewrite' => array( 'slug' => $cp_options->ad_cat_tax_permalink, 'with_front' => false, 'hierarchical' => true ),
	);

	register_taxonomy( APP_TAX_CAT, APP_POST_TYPE, $args );

	// register the tag taxonomy for ads
	$labels = array(
		'name' => __( 'Ad Tags', APP_TD ),
		'singular_name' => __( 'Ad Tag', APP_TD ),
		'search_items' => __( 'Search Ad Tags', APP_TD ),
		'all_items' => __( 'All Ad Tags', APP_TD ),
		'parent_item' => __( 'Parent Ad Tag', APP_TD ),
		'parent_item_colon' => __( 'Parent Ad Tag:', APP_TD ),
		'edit_item' => __( 'Edit Ad Tag', APP_TD ),
		'update_item' => __( 'Update Ad Tag', APP_TD ),
		'add_new_item' => __( 'Add New Ad Tag', APP_TD ),
		'new_item_name' => __( 'New Ad Tag Name', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'show_ui' => true,
		'query_var' => true,
		'update_count_callback' => '_update_post_term_count',
		'rewrite' => array( 'slug' => $cp_options->ad_tag_tax_permalink, 'with_front' => false ),
	);

	register_taxonomy( APP_TAX_TAG, APP_POST_TYPE, $args );

}
add_action( 'init', 'cp_register_taxonomies', 8 );


/**
 * Register menus
 */
function cp_register_menus() {

	register_nav_menu( 'primary', __( 'Primary Navigation', APP_TD ) );
	register_nav_menu( 'secondary', __( 'Footer Navigation', APP_TD ) );
	register_nav_menu( 'theme_dashboard', __( 'User Dashboard', APP_TD ) );

}
add_action( 'after_setup_theme', 'cp_register_menus' );


/**
 * Register sidebars
 */
function cp_register_sidebars() {

	// Home Page
	register_sidebar( array(
		'name' => __( 'Main Sidebar', APP_TD ),
		'id' => 'sidebar_main',
		'description' => __( 'This is your main ClassiPress sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// Page
	register_sidebar( array(
		'name' => __( 'Page Sidebar', APP_TD ),
		'id' => 'sidebar_page',
		'description' => __( 'This is your ClassiPress page sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// Blog
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', APP_TD ),
		'id' => 'sidebar_blog',
		'description' => __( 'This is your ClassiPress blog sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// Ad
	register_sidebar( array(
		'name' => __( 'Ad Sidebar', APP_TD ),
		'id' => 'sidebar_listing',
		'description' => __( 'This is your ClassiPress single ad listing sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// Author
	register_sidebar( array(
		'name' => __( 'Author Sidebar', APP_TD ),
		'id' => 'sidebar_author',
		'description' => __( 'This is your ClassiPress author sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// User
	register_sidebar( array(
		'name' => __( 'User Sidebar', APP_TD ),
		'id' => 'sidebar_user',
		'description' => __( 'This is your ClassiPress user dashboard sidebar.', APP_TD ),
		'before_widget' => '<div class="shadowblock_out %2$s" id="%1$s"><div class="shadowblock">',
		'after_widget' => '</div><!-- /shadowblock --></div><!-- /shadowblock_out -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

	// Footer
	register_sidebar( array(
		'name' => __( 'Footer', APP_TD ),
		'id' => 'sidebar_footer',
		'description' => __( 'This is your ClassiPress footer. You can have up to four items which will display in the footer from left to right.', APP_TD ),
		'before_widget' => '<div class="column %2$s" id="%1$s">',
		'after_widget' => '</div><!-- /column -->',
		'before_title' => '<h2 class="dotted">',
		'after_title' => '</h2>',
	) );

}
add_action( 'after_setup_theme', 'cp_register_sidebars' );

