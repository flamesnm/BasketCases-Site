<?php

// BEFORE USING: Move the classiPress-child theme into the /themes/ folder.
//
// You can add you own actions, filters and code below.
//
// Remove below actions, "includes" folder and "tpl-featured-ads-home.php" file from your child theme if you don't wish to have that homepage.


global $app_version;
$app_version = '';

// execute theme actions on theme activation
function ft_first_run() {
	if ( isset($_GET['firstrun']) )
		do_action('ft_first_run');
}
add_action('admin_notices', 'ft_first_run', 9999);

// admin-only functions
if ( is_admin() ) :
	//include_once(STYLESHEETPATH.'/includes/install-script.php'); // needs to be above admin-options otherwise install/upgrade script doesn't work correctly
endif;

add_action( 'appthemes_first_run', 'ft_child_init_image_sizes', 11 ); //this hook fires on activation of simply theme
function ft_child_init_image_sizes() {
	// uncheck the crop thumbnail image checkbox
	update_option('thumbnail_crop', true);
	update_option('thumbnail_size_w', 90);
	update_option('thumbnail_size_h', 90);
	update_option('medium_size_w', 250);
	update_option('medium_size_h', 250);
	update_option('large_size_w', 593);
	update_option('large_size_h', 593);
}

//  Create Special Size For The Ads
function simply_add_new_image_size() {
	add_image_size('ad-simply', 250, 250, true);
	add_image_size('featured-simply', 250, 250, true); // sidebar blog thumbnail size, box crop mode
}
add_action( 'appthemes_init', 'simply_add_new_image_size' );

// setup image sizes
function child_theme_setup() {
    add_image_size( 'ad-small', 90, 90, true );
    add_image_size( 'ad-medium', 250, 250, true );
    add_image_size( 'ad-large', 593, 593, true );
}
add_action( 'after_setup_theme', 'child_theme_setup', 20 );

if (!isset($content_width))
	$content_width = 593;

// Image Size Update Notice
if ( isset( $_GET['activated'] ) && 'themes.php' == $GLOBALS['pagenow'] )
	add_option('simply_images_update', 'yes');

function simply_display_image_size_notice() {
	if ( !current_user_can('manage_options') || !get_option('simply_images_update') )
		return;

	$ignore_url = ( version_compare( $GLOBALS['app_version'], '3.3', '<' ) ) ? 'admin.php?page=settings&simply_nag_ignore=1' : 'admin.php?page=app-settings&simply_nag_ignore=1';
	echo scb_admin_notice( sprintf( __( 'Image thumbnails need to be updated to use with Simply Responsive Child Theme. Please use the <a href="%1$s">Force Regenerate Thumbnails</a> plugin for this purpose.', APP_TD ), 'http://wordpress.org/plugins/force-regenerate-thumbnails/' ) . ' | <a href="' . admin_url( $ignore_url ) . '">' . __( 'Hide Notice', APP_TD ) . '</a>' );
}
add_action( 'admin_notices', 'simply_display_image_size_notice' );


function simply_ignore_image_size_notice() {
	if ( isset($_GET['simply_nag_ignore']) )
		delete_option('simply_images_update');
}
add_action( 'admin_init', 'simply_ignore_image_size_notice' );


//allow redirection, even if my theme starts to send output to the browser
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}

// Setup New Child Views
function child_setup_new_views_template() {
	require_once dirname( __FILE__ ) . '/includes/child-views.php';
	new Child_User_Dashboard;
}
add_action( 'appthemes_init', 'child_setup_new_views_template' );


// load alternative enqueue file
	require_once( get_stylesheet_directory() . '/includes/ft-enqueue.php' );


// ========== Change Featured Images Size ==========

// processes the entire ad thumbnail logic within the loop
if ( !function_exists('cp_ad_loop_thumbnail') ) :
	function cp_ad_loop_thumbnail() {
		global $post, $cp_options;

		// go see if any images are associated with the ad
		$image_id = cp_get_featured_image_id($post->ID);

		// set the class based on if the hover preview option is set to "yes"
		$prevclass = ( $cp_options->ad_image_preview ) ? 'preview' : 'nopreview';

		if ( $image_id > 0 ) {

			// get 75x75 v3.0.5+ image size
			$adthumbarray = wp_get_attachment_image( $image_id, 'ad-simply' );

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src( $image_id, 'large' );
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if ( $adthumbarray ) {
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" data-rel="'.$img_large_url_raw.'">'.$adthumbarray.'</a>';

			// maybe a v3.0 legacy ad
			} else {
				$adthumblegarray = wp_get_attachment_image_src($image_id, 'thumbnail');
				$img_thumbleg_url_raw = $adthumblegarray[0];
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" data-rel="'.$img_large_url_raw.'">'.$adthumblegarray.'</a>';
			}

		// no image so return the placeholder thumbnail
		} else {
			echo '<a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '"><img class="attachment-medium" alt="no-image" title="" src="' . appthemes_locate_template_uri('images/no-thumb-250.jpg') . '" /></a>';
		}

	}
endif;


// processes the entire ad thumbnail logic for featured ads
if ( !function_exists('ft_ad_featured_thumbnail') ) :
	function ft_ad_featured_thumbnail() {
		global $post, $cp_options;

		// go see if any images are associated with the ad
		$image_id = cp_get_featured_image_id($post->ID);

		// set the class based on if the hover preview option is set to "yes"
		$prevclass = ( $cp_options->ad_image_preview ) ? 'preview' : 'nopreview';

		if ( $image_id > 0 ) {

			// get 50x50 v3.0.5+ image size
			$adthumbarray = wp_get_attachment_image($image_id, 'featured-simply');

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src($image_id, 'large');
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if($adthumbarray) {
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" data-rel="'.$img_large_url_raw.'">'.$adthumbarray.'</a>';

			// maybe a v3.0 legacy ad
			} else {
				$adthumblegarray = wp_get_attachment_image_src($image_id, 'thumbnail');
				$img_thumbleg_url_raw = $adthumblegarray[0];
				echo '<a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" class="'.$prevclass.'" data-rel="'.$img_large_url_raw.'">'.$adthumblegarray.'</a>';
			}

		// no image so return the placeholder thumbnail
		} else {
			echo '<a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '"><img class="attachment-featured-simply" alt="no-image" title="" src="' . appthemes_locate_template_uri('images/no-thumb-250.jpg') . '" /></a>';
		}

	}
endif;


// get the main image associated to the ad used on the single page
if (!function_exists('cp_get_image_url')) {
	function cp_get_image_url() {
		global $post, $wpdb;

		// go see if any images are associated with the ad
		$images = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'numberposts' => 1, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'ID') );

		if ($images) {

			// move over bacon
			$image = array_shift($images);
			$alt = get_post_meta( $image->ID, '_wp_attachment_image_alt', true );
			// see if this v3.0.5+ image size exists
			//$adthumbarray = wp_get_attachment_image($image->ID, 'medium');
			$adthumbarray = wp_get_attachment_image_src($image->ID, 'medium');
			$img_medium_url_raw = $adthumbarray[0];

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src($image->ID, 'large');
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if($adthumbarray)
				echo '<a href="'. $img_large_url_raw .'" class="img-main" data-rel="colorbox" title="'. the_title_attribute('echo=0') .'"><img src="'. $img_medium_url_raw .'" title="'. $alt .'" alt="'. $alt .'" /></a>';

		// no image so return the placeholder thumbnail
		} else {
			echo '<img class="attachment-medium" alt="" title="" src="' . appthemes_locate_template_uri('images/no-thumb-250.jpg') . '" />';
		}

	}
}

// EXTRA FUNCTION USED FOR SHARING THE SINGLE AD IMAGE
// get the main image associated to the ad used on the single page
if (!function_exists('cp_get_single_image_url')) {
	function cp_get_single_image_url() {
		global $post, $wpdb;

		// go see if any images are associated with the ad
		$images = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'numberposts' => 1, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'ID') );

		if ($images) {

			// move over bacon
			$image = array_shift($images);
			$alt = get_post_meta( $image->ID, '_wp_attachment_image_alt', true );
			// see if this v3.0.5+ image size exists
			//$adthumbarray = wp_get_attachment_image($image->ID, 'medium');
			$adthumbarray = wp_get_attachment_image_src($image->ID, 'medium');
			$img_medium_url_raw = $adthumbarray[0];

			// grab the large image for onhover preview
			$adlargearray = wp_get_attachment_image_src($image->ID, 'large');
			$img_large_url_raw = $adlargearray[0];

			// must be a v3.0.5+ created ad
			if($adthumbarray)
				echo ''. $img_large_url_raw .'';

		// no image so return the placeholder thumbnail
		} else {
			echo ''. $img_medium_url_raw .'';
		}

	}
}

add_filter('the_content', 'make_clickable');
add_filter('the_excerpt', 'make_clickable');


// Rewrite the actual action
function ft_ad_loop_meta() {
	global $post, $cp_options;
	if ( is_singular( APP_POST_TYPE ) )
		return;
?>
	<p class="post-meta">
        <span class="folder">&nbsp;</span><?php if ( $post->post_type == 'post' ) the_category(', '); else echo get_the_term_list( $post->ID, APP_TAX_CAT, '', ' - ', '' ); ?>| <span class="owner"><?php if ( $cp_options->ad_gravatar_thumb ) appthemes_get_profile_pic( get_the_author_meta('ID'), get_the_author_meta('user_email'), 16 ); ?><?php the_author_posts_link(); ?></span><?php if(get_post_meta($post->ID,  'cp_city', true)) { ?><span  style="color:#58595B;float:right;margin-right:10px;font-size:13px;"><?php  echo(get_post_meta($post->ID, 'cp_city', true));  ?></span><?php } ?>
	</p>
<?php
}

// De-register the action, and add some new ones instead
function ft_modify_actions() {
	remove_action( 'appthemes_after_post_title', 'cp_ad_loop_meta' );
	add_action( 'appthemes_after_post_title', 'ft_ad_loop_meta' );
}
add_action( 'appthemes_init', 'ft_modify_actions' );


//fix for Artems Advanced Custom Fields double meta information showing in the loop - On main Page.
remove_action( 'init', 'acf_unhook_appthemes_functions' );
remove_action( 'appthemes_after_post_title', 'acf_ad_loop_meta_top' );



/**
 * NEW blog post meta hook
 */
function appthemes_after_blog_post_title_ft() {
	do_action( 'appthemes_after_blog_post_title_ft' );
}
/**
 * add the post meta after the blog post title
 * @since 3.1
 */
function cp_blog_post_meta_ft() {
	if ( is_page() ) return; // don't do post-meta on pages
	global $post;
?>
	<p class="meta dotted"><span class="user"><?php the_author_posts_link(); ?></span> <span class="folderb"><?php the_category(', '); ?></span> <span class="clockb"><span><?php echo appthemes_date_posted( $post->post_date ); ?></span></span></p>
<?php
}
add_action('appthemes_after_blog_post_title_ft', 'cp_blog_post_meta_ft');



/**
 * NEW loop related else Hook
 */
function appthemes_loop_related_else( $type = '' ) {
	if ( $type ) $type .= '_';
	do_action( 'appthemes_' . $type . 'loop_related_else' );
}

/**
 * add the no ads found message after the related ads
 */
function cp_ad_loop_related_else() {
?>
    <div class="shadowblock_else">
		<div class="shadowblock">

			<div class="pad2"></div>
			<p class="msg"><?php _e('Sorry, there are no other related ads in this category at this time.', APP_TD); ?></p>
		</div><!-- /shadowblock -->
	</div><!-- /shadowblock_out -->

<?php
}
add_action('appthemes_loop_related_else', 'cp_ad_loop_related_else');


/**
 * NEW loop related else Hook
 */
function appthemes_loop_else_ft( $type = '' ) {
	if ( $type ) $type .= '_';
	do_action( 'appthemes_' . $type . 'loop_else_ft' );
}

/**
 * add the no ads found message
 * @since 3.1
 */
function cp_ad_loop_else_ft() {
?>
    <div class="shadowblock_none">
		<div class="shadowblock">

			<div class="pad10"></div>
			<p class="msg"><?php _e( 'Sorry, no listings were found.', APP_TD ); ?></p>
			<div class="pad10"></div>
				<span class="back"><a href="javascript:history.back()"><?php _e( '&larr; Go Back', APP_TD); ?></a></span>
			<div class="pad25"></div>
		</div><!-- /shadowblock -->
	</div><!-- /shadowblock_out -->
<?php
}
add_action('appthemes_loop_else_ft', 'cp_ad_loop_else_ft');



// custom sidebar 320x250 ads widget
class AppThemes_Widget_300_Ads extends WP_Widget {

	function AppThemes_Widget_300_Ads() {
		$widget_ops = array( 'description' => __( 'Places an ad space in the sidebar for 300x250 ads', APP_TD ) );
		$control_ops = array('width' => 500, 'height' => 350);
		$this->WP_Widget( 'cp_300_ads', __( 'CP 300x250 Ads', APP_TD ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract($args);

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Sponsored Ads', APP_TD ) : $instance['title'] );
		//$title = apply_filters('widget_title', $instance['title'] );
		$newin = isset( $instance['newin'] ) ? $instance['newin'] : false;


		if (isset($instance['ads'])) :

			// separate the ad line items into an array
			$ads = explode("\n", $instance['ads']);

			if (sizeof($ads)>0) :

				echo $before_widget;

				if ($title) echo $before_title . $title . $after_title;
				if ($newin) $newin = 'target="_blank"';
			?>

				<ul class="ads300">
				<?php
					$alt = 1;
					foreach ($ads as $ad) :
						if ($ad && strstr($ad, '|')) {
							$alt = $alt*-1;
							$this_ad = explode('|', $ad);
							echo '<li class="';
							if ($alt==1) echo 'alt';
							echo '"><a href="'.$this_ad[0].'" rel="'.$this_ad[3].'" '.$newin.'><img src="'.$this_ad[1].'" width="300" height="250" alt="'.$this_ad[2].'" /></a></li>';
						}
					endforeach;
				?>
				</ul>

				<?php
				echo $after_widget;

			endif;

		endif;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ads'] = strip_tags( $new_instance['ads'] );
		$instance['newin'] = $new_instance['newin'];

		return $instance;
	}

	function form( $instance ) {

		// load up the default values
		$default_ads = get_bloginfo('url')."|".get_bloginfo('stylesheet_directory')."/images/ad300a.gif|Ad 1|nofollow\n";
		$defaults = array( 'title' => __( 'Sponsored Ads', APP_TD ), 'ads' => $default_ads, 'rel' => true, 'newin' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label><?php _e( 'Title:', APP_TD ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label><?php _e( 'Ads:', APP_TD ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('ads'); ?>" cols="5" rows="5"><?php echo $instance['ads']; ?></textarea>
			<?php _e( 'Enter one ad entry per line in the following format:', APP_TD ); ?><br />
			<code><?php _e( 'URL|Image URL|Image Alt Text|rel', APP_TD ); ?></code><br />
			<?php _e( '<strong>Note:</strong> You must hit your &quot;enter/return&quot; key after each ad entry otherwise the ads will not display properly.', APP_TD ); ?>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['newin'], 'on'); ?> id="<?php echo $this->get_field_id('newin'); ?>" name="<?php echo $this->get_field_name('newin'); ?>" />
			<label><?php _e( 'Open ads in a new window?', APP_TD ); ?></label>
		</p>
<?php
	}
}

// register the custom sidebar widgets for 300x250 ads
function ft_widgetsnew_init() {
	register_widget('AppThemes_Widget_300_Ads');

}
add_action( 'widgets_init', 'ft_widgetsnew_init' );


// correctly load all the jquery scripts so they don't conflict with plugins
if ( !function_exists('ft_load_scripts') ) :
function ft_load_scripts() {
	global $cp_options;

	wp_enqueue_script( 'simply_responsive_js', get_stylesheet_directory_uri() . '/includes/js/respond.js', true );
	wp_enqueue_script( 'orientation_fix_js', get_stylesheet_directory_uri() . '/includes/js/ios-orientationchange-fix.js', true );
	wp_enqueue_script( 'theme-scripts', get_stylesheet_directory_uri() . '/includes/js/theme-scripts.js', array( 'jquery' ), '3.3' );
}
endif;
if ( !is_admin() ) {
add_action( 'wp_enqueue_scripts', 'ft_load_scripts' );
}

























//this is Vienna's mod to create Navigation NEXT & PREV links for single ads pages within the same category
function get_adjacent_post_plus($r, $previous = true ) {
	global $post, $wpdb;

	extract( $r, EXTR_SKIP );

	if ( empty( $post ) )
		return null;

//	Sanitize $order_by, since we are going to use it in the SQL query. Default to 'post_date'.
	if ( in_array($order_by, array('post_date', 'post_title', 'post_excerpt', 'post_name', 'post_modified')) ) {
		$order_format = '%s';
	} elseif ( in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order', 'comment_count')) ) {
		$order_format = '%d';
	} elseif ( $order_by == 'custom' && !empty($meta_key) ) { // Don't allow a custom sort if meta_key is empty.
		$order_format = '%s';
	} else {
		$order_by = 'post_date';
		$order_format = '%s';
	}

//	Sanitize $order_2nd. Only columns containing unique values are allowed here. Default to 'post_date'.
	if ( in_array($order_2nd, array('post_date', 'post_title', 'post_modified')) ) {
		$order_format2 = '%s';
	} elseif ( in_array($order_2nd, array('ID')) ) {
		$order_format2 = '%d';
	} else {
		$order_2nd = 'post_date';
		$order_format2 = '%s';
	}

//	Sanitize num_results (non-integer or negative values trigger SQL errors)
	$num_results = intval($num_results) < 2 ? 1 : intval($num_results);

//	Sorting on custom fields requires an extra table join
	if ( $order_by == 'custom' ) {
		$current_post = get_post_meta($post->ID, $meta_key, TRUE);
		$order_by = 'm.meta_value';
		$meta_join = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key );
	} else {
		$current_post = $post->$order_by;
		$order_by = 'p.' . $order_by;
		$meta_join = '';
	}

//	Get the current post value for the second sort column
	$current_post2 = $post->$order_2nd;
	$order_2nd = 'p.' . $order_2nd;

//	Get the list of post types. Default to current post type
	if ( empty($post_type) )
		$post_type = "'$post->post_type'";

//	Put this section in a do-while loop to enable the loop-to-first-post option
	do {
		$join = $meta_join;
		$excluded_categories = $ex_cats;
		$excluded_posts = $ex_posts;
		$included_posts = $in_posts;
		$in_same_term_sql = $in_same_author_sql = $ex_cats_sql = $ex_posts_sql = $in_posts_sql = '';

//		Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
		$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );

		if ( ($in_same_cat || $in_same_tax || $in_same_format || !empty($excluded_categories)) && !empty($taxonomies) ) {
			$cat_array = $tax_array = $format_array = array();

			if ( $in_same_cat ) {
				$cat_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
			}
			if ( $in_same_tax && !$in_same_cat ) {
				if ( $in_same_tax === true ) {
					if ( $taxonomies != array('category') )
						$taxonomies = array_diff($taxonomies, array('category'));
				} else
					$taxonomies = (array) $in_same_tax;
				$tax_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
			}
			if ( $in_same_format ) {
				$taxonomies[] = 'post_format';
				$format_array = wp_get_object_terms($post->ID, 'post_format', array('fields' => 'ids'));
			}

			$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode('", "', $taxonomies) . "\")";

			$term_array = array_unique( array_merge( $cat_array, $tax_array, $format_array ) );
			if ( !empty($term_array) )
				$in_same_term_sql = "AND tt.term_id IN (" . implode(',', $term_array) . ")";

			if ( !empty($excluded_categories) ) {
				$delimiter = ( strpos($excluded_categories, ',') !== false ) ? ',' : 'and';
				$excluded_categories = array_map( 'intval', explode($delimiter, $excluded_categories) );
				if ( $ex_cats_method === 'strong' ) {
					$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
					if ( function_exists('get_post_format') )
						$taxonomies[] = 'post_format';
					$ex_cats_posts = get_objects_in_term( $excluded_categories, $taxonomies );
					if ( !empty($ex_cats_posts) )
						$ex_cats_sql = "AND p.ID NOT IN (" . implode($ex_cats_posts, ',') . ")";
				} else {
					if ( !empty($term_array) && !in_array($ex_cats_method, array('diff', 'differential')) )
						$excluded_categories = array_diff($excluded_categories, $term_array);
					if ( !empty($excluded_categories) )
						$ex_cats_sql = "AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
				}
			}
		}

//		Optionally restrict next/previous links to same author
		if ( $in_same_author )
			$in_same_author_sql = $wpdb->prepare("AND p.post_author = %d", $post->post_author );

//		Optionally exclude individual post IDs
		if ( !empty($excluded_posts) ) {
			$excluded_posts = array_map( 'intval', explode(',', $excluded_posts) );
			$ex_posts_sql = " AND p.ID NOT IN (" . implode(',', $excluded_posts) . ")";
		}

//		Optionally include individual post IDs
		if ( !empty($included_posts) ) {
			$included_posts = array_map( 'intval', explode(',', $included_posts) );
			$in_posts_sql = " AND p.ID IN (" . implode(',', $included_posts) . ")";
		}

		$adjacent = $previous ? 'previous' : 'next';
		$order = $previous ? 'DESC' : 'ASC';
		$op = $previous ? '<' : '>';

//		Optionally get the first/last post. Disable looping and return only one result.
		if ( $end_post ) {
			$order = $previous ? 'ASC' : 'DESC';
			$num_results = 1;
			$loop = false;
			if ( $end_post === 'fixed' ) // display the end post link even when it is the current post
				$op = $previous ? '<=' : '>=';
		}

//		If there is no next/previous post, loop back around to the first/last post.
		if ( $loop && isset($result) ) {
			$op = $previous ? '>=' : '<=';
			$loop = false; // prevent an infinite loop if no first/last post is found
		}
		$join  = apply_filters( "get_{$adjacent}_post_plus_join", $join, $r );

//		In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
//		This prevents posts from being skipped when they have, for example, the same menu_order.
		$where = apply_filters( "get_{$adjacent}_post_plus_where", $wpdb->prepare("WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $ex_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post), $r );

		$sort  = apply_filters( "get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r );

		$query = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
		$query_key = 'adjacent_post_' . md5($query);
		$result = wp_cache_get($query_key);
		if ( false !== $result )
			return $result;

//		Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
//		Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
		$result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort");
		if ( null === $result )
			$result = '';

	} while ( !$result && $loop );

	wp_cache_set($query_key, $result);
	return $result;
}

// Display previous post link that is adjacent to the current post.
function previous_post_link_plus($args = '') {
	return adjacent_post_link_plus($args, '%link', true);
}

// Display next post link that is adjacent to the current post.
function next_post_link_plus($args = '') {
	return adjacent_post_link_plus($args, '%link', false);
}

// Display adjacent post link. Can be either next post link or previous.
function adjacent_post_link_plus($args = '', $format = '%link &raquo;', $previous = true) {
	$defaults = array(
		'order_by' => 'post_date', 'order_2nd' => 'post_date', 'meta_key' => '', 'post_type' => '',
		'loop' => false, 'end_post' => false, 'thumb' => false, 'max_length' => 0,
		'format' => '', 'link' => '%title', 'tooltip' => '%title',
		'in_same_cat' => false, 'in_same_tax' => false, 'in_same_format' => false, 'in_same_author' => false,
		'ex_cats' => '', 'ex_cats_method' => 'weak', 'ex_posts' => '', 'in_posts' => '',
		'before' => '', 'after' => '', 'num_results' => 1, 'echo' => true
	);

	$r = wp_parse_args( $args, $defaults );
	if ( !$r['format'] )
		$r['format'] = $format;
	if ( !function_exists('get_post_format') )
		$r['in_same_format'] = false;

	if ( $previous && is_attachment() ) {
		$posts = array();
		$posts[] = & get_post($GLOBALS['post']->post_parent);
	} else
		$posts = get_adjacent_post_plus($r, $previous);

//	If there is no next/previous post, return false so themes may conditionally display inactive link text.
	if ( !$posts )
		return false;

	$output = $r['before'];

//	When num_results > 1, multiple adjacent posts may be returned. Use foreach to display each adjacent post.
//	If sorting by date, display posts in reverse chronological order. Otherwise display in alpha/numeric order.
	if ( ($previous && $r['order_by'] != 'post_date') || (!$previous && $r['order_by'] == 'post_date') )
		$posts = array_reverse( $posts, true );

	foreach ( $posts as $post ) {
		$title = $post->post_title;
		if ( empty($post->post_title) )
			$title = $previous ? __('Previous Post') : __('Next Post');

		$title = apply_filters('the_title', $title, $post->ID);
		$date = mysql2date(get_option('date_format'), $post->post_date);
		$author = get_the_author_meta('display_name', $post->post_author);

//		Set anchor title attribute to long post title or custom tooltip text. Supports variable replacement in custom tooltip.
		if ( $r['tooltip'] ) {
			$tooltip = str_replace('%title', $title, $r['tooltip']);
			$tooltip = str_replace('%date', $date, $tooltip);
			$tooltip = str_replace('%author', $author, $tooltip);
			$tooltip = ' title="' . esc_attr($tooltip) . '"';
		} else
			$tooltip = '';

//		Truncate the link title to nearest whole word under the length specified.
		$max_length = intval($r['max_length']) < 1 ? 9999 : intval($r['max_length']);
		if ( strlen($title) > $max_length )
			$title = substr( $title, 0, strrpos(substr($title, 0, $max_length), ' ') ) . '...';

		$rel = $previous ? 'prev' : 'next';

		$anchor = '<a href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>';
		$link = str_replace('%title', $title, $r['link']);
		$link = str_replace('%date', $date, $link);
		$link = $anchor . $link . '</a>';

		$format = str_replace('%link', $link, $r['format']);
		$format = str_replace('%date', $date, $format);
		$format = str_replace('%author', $author, $format);
		if ( $r['order_by'] == 'custom' && !empty($r['meta_key']) ) {
			$meta = get_post_meta($post->ID, $r['meta_key'], true);
			$format = str_replace('%meta', $meta, $format);
		}

//		Get the category list, including custom taxonomies (only if the %category variable has been used).
		if ( (strpos($format, '%category') !== false) && version_compare(PHP_VERSION, '5.0.0', '>=') ) {
			$term_list = '';
			$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
			if ( $r['in_same_format'] && get_post_format($post->ID) )
				$taxonomies[] = 'post_format';
			foreach ( $taxonomies as &$taxonomy ) {
				if ( $next_term = get_the_term_list($post->ID, $taxonomy, '', ', ', '') ) {
					$term_list .= $next_term;
					if ( current($taxonomies) ) $term_list .= ', ';
				}
			}
			$format = str_replace('%category', $term_list, $format);
		}

//		Optionally add the post thumbnail to the link. Wrap the link in a span to aid CSS styling.
		if ( $r['thumb'] && has_post_thumbnail($post->ID) ) {
			if ( $r['thumb'] === true ) // use 'post-thumbnail' as the default size
				$r['thumb'] = 'post-thumbnail';
			$thumbnail = '<a class="post-thumbnail" href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>' . get_the_post_thumbnail( $post->ID, $r['thumb'] ) . '</a>';
			$format = $thumbnail . '<span class="post-link">' . $format . '</span>';
		}

//		If more than one link is returned, wrap them in <li> tags
		if ( intval($r['num_results']) > 1 )
			$format = '<li>' . $format . '</li>';

		$output .= $format;
	}

	$output .= $r['after'];

	//	If echo is false, don't display anything. Return the link as a PHP string.
	if ( !$r['echo'] )
		return $output;

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link_plus", $output, $r );

	return true;

}

function ft_vienna_mod($postID) {
$thispost = $postID;
	function ft_get_category_count($input = '') {
    global $wpdb;
    if($input == '') {
        $category = get_the_category();
        return $category[0]->category_count;
    } elseif(is_numeric($input)) {
        $SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->term_taxonomy.term_id=$input";
        return $wpdb->get_var($SQL);
    } else {
        $SQL = "SELECT $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND $wpdb->terms.slug='$input'";
        return $wpdb->get_var($SQL);
    }
} ?>


<div class="nav-category">
<?php
$cat_id = appthemes_get_custom_taxonomy( $thispost, APP_TAX_CAT, 'term_id');
$catname = appthemes_get_custom_taxonomy( $thispost, APP_TAX_CAT, 'name' );
	if (isset($cat_id)) {
	if (ft_get_category_count($cat_id)> 1) {
		echo "";
		echo "<span class='tabview'>There are ".ft_get_category_count($cat_id)." others ads listed under $catname</span>&nbsp&nbsp";
		} else {
		echo "<span class='tabview'>" . __( 'Sorry, there is only ', APP_TD ) . ft_get_category_count($cat_id) . __( ' ad listed under ', APP_TD) . $catname . __( ' at this time.', APP_TD) . "</span>";
		}
	}
?>
</div>
<br />
<span class="nav-previous">
<?php
if (ft_get_category_count($cat_id)> 1)  {
	previous_post_link_plus( array('order_by' => 'post_title','in_same_cat' => 'true&$cat_id', 'end_post' => true, 'link' => '<button class="btn_grey" type="submit">&laquo;</button>',) ); ?>&nbsp;
	<?php previous_post_link_plus( array('order_by' => 'post_title','in_same_cat' => 'true&$cat_id', 'end_post' => false, 'link' => '<button class="btn_link" type="submit">' . __( '&larr; PREV AD', APP_TD ) . '</button> ',) );
}
?>
</span>
<span class="nav-next">
<?php 
if (ft_get_category_count($cat_id)> 1)  {
	next_post_link_plus( array('order_by' => 'post_title','in_same_cat' => 'true&$cat_id', 'end_post' => true, 'link' => ' <button class="mbtn btn_link" type="submit">' . __( 'NEXT AD &rarr;', APP_TD ) . '</button>',) ); ?>&nbsp;
	<?php next_post_link_plus( array('order_by' => 'post_title','in_same_cat' => 'true&$cat_id', 'end_post' => false, 'link' => '<button class="mbtn btn_grey" type="submit">&raquo;</button>',) );
}
else { }; ?>
</span>
<?php
}


// Register the Responsive Video shortcode to wrap html around the content
function responsive_video_shortcode( $atts ) {
	extract( shortcode_atts( array (
		'identifier' => ''
	), $atts ) );
	return '<div class="video-container"><iframe src="//www.youtube.com/embed/' . $identifier . '?showinfo=0&rel=0&autoplay=0" frameborder="0" allowfullscreen></iframe></div><!--video-container-->';
}
add_shortcode ('responsive-video', 'responsive_video_shortcode');



// Register the Responsive Video shortcode to wrap html around the content
function responsive_vimeo_shortcode( $atts ) {
	extract( shortcode_atts( array (
		'identifier' => ''
	), $atts ) );
	return '<div class="video-container"><iframe src="//player.vimeo.com/video/' . $identifier . '?autoplay=0" frameborder="0" allowfullscreen></iframe></div><!--video-container-->';
}
add_shortcode ('responsive-vimeo', 'responsive_vimeo_shortcode');



// Add images to rss
function embed_rss_image($content) {
global $post, $cp_options;
	if( is_feed() ) {

if( get_post_meta( $post->ID, 'images', true ) ) $thumb_image = cp_single_image_legacy( $post->ID, get_option( 'ad-small_size_w' ), get_option( 'ad-small_size_h' )); else $thumb_image = cp_get_image( $post->ID, 'ad-small', 1 );

	$content = $thumb_image . "<br />" . $content;
	}
	return $content;
}

//add_filter('the_excerpt_rss', 'embed_rss_image');
add_filter( 'the_content', 'embed_rss_image' );


/* 
// Sticky post on top of category and search results
add_filter('posts_clauses_request', 'ft_sticky_on_top' );
function ft_sticky_on_top( $sql ){
 	if ( is_tax ( APP_TAX_CAT) || is_search() ){
		global $wpdb;
		$sticky_posts = get_option ('sticky_posts');
		if ( !$sticky_posts ) return $sql;
		$sql['fields'] = $sql['fields'].", IF( $wpdb->posts.ID IN ( ".implode ( ',',$sticky_posts )."), 1, 0) AS featured";
		$sql['orderby'] = "featured DESC, ". $sql['orderby'];
	}
	return $sql;
}
*/

// unregister the main cp price action, and add a new one
function ft_modify_price_action() {
	remove_action( 'appthemes_before_post_title', 'cp_ad_loop_price' );
	add_action( 'appthemes_before_post_title', 'ft_ad_loop_price' );
}
add_action( 'appthemes_init', 'ft_modify_price_action' );


// Remove cp_price from loop when no price is input (* price must NOT be set as required in the add new forms!)
function ft_ad_loop_price() {
	global $post, $cp_options;

	if ( $post->post_type == 'page' || $post->post_type == 'post' )
		return;
		$price = get_post_meta( $post->ID, 'cp_price', true );

	if ( !empty( $price) /* AND ( $price > 0 ) */ ) { // unless empty add the ad price field in the loop as usual
	?>

		<div class="price-wrap">
			<span class="tag-head">&nbsp;</span><p class="post-price"><?php if ( get_post_meta( $post->ID, 'price', true ) ) cp_get_price_legacy( $post->ID ); else cp_get_price( $post->ID, 'cp_price' ); ?></p>
		</div>

	<?php } else {
		// do not display empty price field
	}
}