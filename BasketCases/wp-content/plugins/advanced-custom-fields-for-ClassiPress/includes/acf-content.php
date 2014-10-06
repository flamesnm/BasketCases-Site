<?php
/**
 * Here placed all functions which prints content
 *
 *
 * @since 1.1.3
 */

add_filter( 'acf_display_profile_field', 'acf_location_filter',10, 3 );
add_filter( 'acf_display_profile_field', 'acf_private_filter',11 );
add_filter( 'acf_display_profile_field', 'acf_logged_filter',12 );
add_filter( 'acf_display_ad_field', 'acf_location_filter',10, 3 );
add_filter( 'acf_display_ad_field', 'acf_private_filter',11);
add_filter( 'acf_display_ad_field', 'acf_logged_filter',12 );
add_filter( 'acf_display_ad_field', 'acf_featured_filter',13 );


/***************************************************
 * _________________SINGLE AD PAGE_________________*
 ***************************************************/
function acf_ad_details_filter( $result, $post ) {
	global $current_user;
	$ad_fields = get_option( 'acf_ad_fields' );
	$ad_field = $ad_fields[ $result->field_name ];
	$ad_field['can_see'] = acf_u_can_see( get_the_author_meta( 'ID' ), $current_user->ID ); // used for private fields
	$ad_field['value'] = implode( ", ", get_post_meta( $post->ID, $result->field_name, false ) );
	// Allows to add an additional constraint to displaying field.
	$ad_field = apply_filters( 'acf_display_ad_field', $ad_field, $result->field_name, 'single_ad_display' );
	if ( ! $ad_field ) {
		return false;
	} else {
		global $acf_field_val;
		$acf_field_val = $ad_field['value'];
		add_filter( 'cp_ad_details_' . $result->field_name, 'acf_cp_ad_field_args' );
		return $result;
	}
}
add_filter( 'cp_ad_details_field', 'acf_ad_details_filter', 11, 2 );

function acf_cp_ad_field_args ( $args ) {
	global $acf_field_val;
	$args['value'] = $acf_field_val;
	return $args;
}


function acf_ad_content_details( $results, $post, $location ) {
	global $current_user;
	if ( 'content' !== $location )
		return;

	$ad_fields = get_option( 'acf_ad_fields' );
	$acf_u_can_see = acf_u_can_see( get_the_author_meta( 'ID' ), $current_user->ID ); // used for private fields
	$output_fields = '';
	$there_are = false;

	$output_fields .= '<div id="acf-ad-details" class="custom-text-area dotted acf-ad-details"><h3>' . esc_html( __( 'Ad Details', APP_TD ) ) . '</h3>';
	$output_fields .= '<ul id="acf-details-tbl">';

	foreach ( $results as $result ) {
		$ad_field = $ad_fields[ $result->field_name ];
		$ad_field['can_see'] = $acf_u_can_see;
		$ad_field['value'] = get_post_meta( $post->ID, $result->field_name, true );
		$ad_field['label'] = $result->field_label;

		$ad_field = apply_filters( 'acf_display_ad_field', $ad_field, $result->field_name, 'single_ad_cont' );
		if ( ! $ad_field || $ad_field['value'] == '' || $result->field_type == 'text area' )
			continue;

		$output_fields .= '<li class="acf-details-item" id="acf-' . $result->field_name . '">';
		$output_fields .= '<span class="acf-details-label">' . esc_html( translate( $ad_field['label'], APP_TD ) ) . '</span>';
		$output_fields .= '<span class="acf-details-sep">: </span>';

		if ( $result->field_type == "checkbox" ) {
			$there_are = true;
			$post_meta_val = get_post_meta( $post->ID, $result->field_name, false );
			$output_fields .= '<span class="acf-details-val">' . appthemes_make_clickable( implode( ", ", $post_meta_val ) ) . '</span>';
		} else {
			$there_are = true;
			$output_fields .= '<span class="acf-details-val">' . appthemes_make_clickable( $ad_field['value'] ) . '</span>';
		}
		$output_fields .= '</li>';
	}

	$output_fields .= '</ul>';
	$output_fields .= '</div>';

	if ( $there_are )
		echo $output_fields;

}
add_action( 'cp_action_before_ad_details', 'acf_ad_content_details',10, 3 );

function acf_profile_wrap_ad_details( $location, $option ) {
	global $current_user;
	$fields = get_option( 'acf_profile_fields' );
	$acf_u_can_see = acf_u_can_see( get_the_author_meta( 'ID' ), $current_user->ID ); // used for private fields
	foreach ( $fields as $key => $field ) {
		$field['value'] = get_the_author_meta( $key );
		$field['can_see'] = $acf_u_can_see;
		// Allows to add an additional constraint to the show field.
		$field = apply_filters( 'acf_display_profile_field', $field, $key, $option );
		if ( ! $field || $field['value'] == '' )
			continue;
		if ( $location == 'list' && $field['type'] != "text area" )
			echo '<li id="' . $key . '"><span>' . $field['title'] . ':</span> ' . appthemes_make_clickable( $field['value'] ) . '</li>'; // make_clickable is a WP function that auto hyperlinks urls
		elseif ( $location == 'content' && $field['type'] == "text area" )
			echo '<div id="' . $key . '" class="custom-text-area dotted"><h3>' . $field['title'] . '</h3>' . stripslashes( nl2br( appthemes_make_clickable( $field['value'] ) ) ) . '</div>'; // make_clickable is a WP function that auto hyperlinks urls
	}
}
function acf_profile_before_ad_details( $results, $post, $location ) {
	acf_profile_wrap_ad_details( $location, 'single_ad_display' );
}
function acf_profile_after_ad_details( $results, $post, $location ) {
	acf_profile_wrap_ad_details( $location, 'single_ad_after' );
}
add_action( 'cp_action_before_ad_details', 'acf_profile_before_ad_details',11, 3 );
add_action( 'cp_action_after_ad_details', 'acf_profile_after_ad_details',12, 3 );


/***************************************************
 * _____________________LOOP_______________________*
 ***************************************************/

/**
 * Collect Display Options
 * Uses action hook 'appthemes_before_loop'
 *
 * @since 1.0
 * @global type $acf_loop_ad_top
 * @global type $acf_loop_ad_bottom
 * @global type $acf_loop_ad_top_p
 * @global type $acf_loop_ad_bottom_p
 * @return type
 */
function acf_get_loop_fields() {
	if ( !is_archive() && !is_search() )
		return false;

	global $acf_loop_ad_top, $acf_loop_ad_bottom, $acf_loop_ad_top_p, $acf_loop_ad_bottom_p;
	$acf_loop_ad_top = array();
	$acf_loop_ad_bottom = array();
	$acf_loop_ad_top_p = array();
	$acf_loop_ad_bottom_p = array();

	$acf_ad_options = get_option( 'acf_ad_fields' );
	foreach ( $acf_ad_options as $key => $ad_field_option ) {
		if ( isset( $ad_field_option['loop_ad_top'] ) && $ad_field_option['loop_ad_top'] === 'yes' )
			$acf_loop_ad_top[$key] = $ad_field_option;
		if ( isset( $ad_field_option['loop_ad_bottom'] ) && $ad_field_option['loop_ad_bottom'] === 'yes' )
			$acf_loop_ad_bottom[$key] = $ad_field_option;
	}

	$acf_profile_options = get_option( 'acf_profile_fields' );
	foreach ( $acf_profile_options as $key => $ad_profile_option ) {
		if ( isset( $ad_profile_option['loop_ad_top'] ) && $ad_profile_option['loop_ad_top'] === 'yes' )
			$acf_loop_ad_top_p[$key] = $ad_profile_option;
		if ( isset( $ad_profile_option['loop_ad_bottom'] ) && $ad_profile_option['loop_ad_bottom'] === 'yes' )
			$acf_loop_ad_bottom_p[$key] = $ad_profile_option;
	}
}
add_action( 'appthemes_before_loop', 'acf_get_loop_fields' );


function acf_loop_fields( $option, $acf_u_can_see, $post_id ) {
	global $$option;
	if ( ! isset( $$option ) )
		return;
	$ret = '';

	if ( 'acf_loop_ad_top' === $option || 'acf_loop_ad_bottom' === $option )
		$hook = 'acf_display_ad_field';
	else
		$hook = 'acf_display_profile_field';
	if ( 'acf_loop_ad_top' === $option || 'acf_loop_ad_top_p' === $option )
		$disp_opt = 'loop_ad_top';
	else
		$disp_opt = 'loop_ad_bottom';

	foreach ( $$option as $field => $props) {
		if ( 'acf_loop_ad_top' === $option || 'acf_loop_ad_bottom' === $option )
			$post_meta_val = implode( ", ", get_post_meta( $post_id, $field, false ) );
		else
			$post_meta_val = get_the_author_meta( $field );

		$props['can_see'] = $acf_u_can_see;
		$props['value'] = $post_meta_val;

		// Allows to add an additional restriction to show field.
		$props = apply_filters( $hook, $props, $field, $disp_opt );

		if ( ! $props || $post_meta_val == '' )
			continue;

		$ret .= '<span class="loop-field-separator">&nbsp;&nbsp;|&nbsp;&nbsp;</span>';
		$ret .= '<span class="' . esc_attr( $field ) . '">';
		$ret .=  stripslashes( $props['value'] );
		$ret .= '</span>';

	}
	return $ret;
}

function acf_loop_blocks( $location = 'top' ) {
	if ( is_singular( APP_POST_TYPE ) )
		return; // don't do ad-meta on pages
	global $post, $current_user, $cp_options;
	if ( $post->post_type == 'page' )
		return;

	ob_start();

	$acf_u_can_see = false; // use for private fields
	if ( current_user_can( 'manage_options' ) || get_the_author_meta( 'ID' ) == $current_user->ID )
		$acf_u_can_see = true;

	$separator = '&nbsp;&nbsp;|&nbsp;&nbsp;';

	if ( 'top' === $location ) {
		$action = 'acf_loop_top';
		echo '<p class="post-meta">';
		$display_cat = '<span class="folder">';
		if ( get_the_category() )
			$display_cat .= the_category( ', ' );
		else
			$display_cat .= get_the_term_list( $post->ID, APP_TAX_CAT, '', ', ', '' );
		$display_cat .= '</span>';
		$display_cat .= $separator;
		echo $display_cat;
		?>
		<span class="owner">
			<?php if ( $cp_options->ad_gravatar_thumb || get_option( 'cp_ad_gravatar_thumb' ) == 'yes' ) appthemes_get_profile_pic( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_email' ), 16 ) ?>
				<?php if ( !is_author() ) the_author_posts_link(); ?>
		</span> |
		<span class="clock">
			<span>
				<?php echo appthemes_date_posted( $post->post_date ); ?>
			</span>
		</span>
		<?php
	} else {
		$action = 'acf_loop_bottom';
		echo '<p class="post-custom-meta" style="float:right; color:#AFAFAF;font-size:11px;margin:0;padding:4px 0;text-shadow:0 1px 0 #FFFFFF;border-bottom:0;">';
	}
	echo acf_loop_fields( 'acf_loop_ad_' . $location, $acf_u_can_see, $post->ID ),
		 acf_loop_fields( 'acf_loop_ad_' . $location . '_p', $acf_u_can_see, $post->ID );
	$action( $post );
	echo '</p>';

	ob_end_flush();
}


/**
 * Unhook Appthemes core functions.
 * Uses action hook 'init' and allow ACFCP to add acf_ad_loop_meta_top() istead of cp_ad_loop_meta()
 *
 * @since 1.0
 * @todo Ask AT devs to add action hooks to cp_ad_loop_meta function
 */
function acf_unhook_appthemes_functions() {
	acf_re_action( 'appthemes_after_post_title', 'cp_ad_loop_meta', 'acf_ad_loop_meta_top' );
}
add_action( 'init', 'acf_unhook_appthemes_functions' );

/**
 * Changing the design of ads in the archives before ad content.
 * Uses action hook 'appthemes_after_post_title'
 *
 * @since 1.0
 */
function acf_ad_loop_meta_top() {
	acf_loop_blocks( 'top' );
}

/**
 * Changing the design of ads in the archives after ad content.
 * Uses action hook 'appthemes_after_post_content'
 *
 * @since 1.0
 */
function acf_ad_loop_bottom() {
	acf_loop_blocks( 'bottom' );
}
add_action( 'appthemes_after_post_content', 'acf_ad_loop_bottom' );


/***************************************************
 * __________________USER INFO_____________________*
 ***************************************************/

/**
 * Add profile fields to the author page and author tab info.
 * uses action hook 'cp_author_info'
 * @since 1.0
 * @global object $current_user
 * @global type $curauth
 * @param string $location
 */
function acf_author_info( $location ) {
	global $current_user, $curauth;

	if ( !$curauth ) {
		if ( 'sidebar-user' == $location )
			$curauth = $current_user;
		if ( 'sidebar-ad' == $location )
			$curauth = get_userdata( get_the_author_meta('ID') );
		if ( 'page' == $location )
			$curauth = get_queried_object();
	}
	if ( 'page' == $location ) {
		$disp_opt = 'author_page_display';
		$class = "author-info";
	} elseif ( 'sidebar-user' == $location ) {
		$disp_opt = 'user_sidebar_display';
		$class = "user-details";
	} elseif ( 'sidebar-ad' == $location ) {
		$disp_opt = 'user_sidebar_ad_display';
		$class = "member";
	}
	else
		return false;

	$profile_fields = get_option( 'acf_profile_fields' );

	if ( ! $profile_fields )
		return false;

	$textareas = '';
	$acf_u_can_see = acf_u_can_see( $curauth->ID, $current_user->ID );

	ob_start();
	echo '<ul class="' . esc_attr( $class ) . '">';
		foreach ( $profile_fields as $field_name => $profile_field ) {
			$profile_field['value'] = $curauth->$field_name;
			$profile_field['can_see'] = $acf_u_can_see;
			// Allows to add an additional constraint to the show field.
			$profile_field = apply_filters( 'acf_display_profile_field', $profile_field, $field_name, $disp_opt );
			if ( ! $profile_field || $profile_field['value'] == '' )
				continue;

			if ( $profile_field['type'] != "text area" )
				echo '<li class="author-' . esc_attr( $field_name ) . '"><strong class="title-' . esc_attr( $field_name ) . '">' . esc_html( $profile_field['title'] ) . ':</strong> ' . stripslashes( appthemes_make_clickable( $profile_field['value'] ) ) . '</li>';
			else
				$textareas .= '<h3 class="dotted">' . esc_html( $profile_field['title'] ) . '</h3><p>' . stripslashes( nl2br( appthemes_make_clickable( $profile_field['value'] ) ) ) . '</p>'; //no html but multilines
		}
	echo '</ul>';
	// textareas section only for author page (not sidebar)
	if ( 'page' == $location ) {
		echo '</div>'; // close "author-main" div
		echo $textareas;
		echo '<div class="clear">'; //open new div to not break template
	}
	ob_end_flush();
}
add_action( 'cp_author_info', 'acf_author_info' );