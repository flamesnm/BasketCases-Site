<?php

// add/remove meta boxes on the listing edit admin page
function cp_setup_meta_box() {

	add_meta_box( 'ad-meta-box', __( 'Ad Meta Fields', APP_TD ), 'cp_custom_fields_meta_box', APP_POST_TYPE, 'normal', 'high' );
	add_meta_box( 'images-meta-box', __( 'Ad Images', APP_TD ), 'cp_custom_images_meta_box', APP_POST_TYPE, 'normal', 'high' );

	remove_meta_box( 'postexcerpt', APP_POST_TYPE, 'normal' );
	remove_meta_box( 'authordiv', APP_POST_TYPE, 'normal' );

}
add_action( 'admin_menu', 'cp_setup_meta_box' );


// show the ad meta fields in a custom meta box
function cp_custom_fields_meta_box() {
	global $wpdb, $post, $meta_boxes;

	// use nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'ad_meta_wpnonce', false, true );

	// get the ad category id
	$ad_cat_id = appthemes_get_custom_taxonomy( $post->ID, APP_TAX_CAT, 'term_id' );

	// get the form id
	$fid = cp_get_form_id( $ad_cat_id );


	// if there's no form id it must mean the default form is being used so let's go grab those fields
	if ( ! $fid ) {

		// use this if there's no custom form being used and give us the default form
		$sql = "SELECT field_label, field_name, field_type, field_values, field_tooltip, field_req FROM $wpdb->cp_ad_fields WHERE field_core = '1' ORDER BY field_id asc";

	} else {

		// now we should have the formid so show the form layout based on the category selected
		$sql = $wpdb->prepare( "SELECT f.field_label, f.field_name, f.field_type, f.field_values, f.field_perm, f.field_tooltip, m.meta_id, m.field_pos, m.field_req, m.form_id "
			 . "FROM $wpdb->cp_ad_fields f "
			 . "INNER JOIN $wpdb->cp_ad_meta m "
			 . "ON f.field_id = m.field_id "
			 . "WHERE m.form_id = %s "
			 . "ORDER BY m.field_pos asc",
        $fid);

	}

	$results = $wpdb->get_results( $sql );

	// display the write panel for the custom fields
	if ($results) :
	?>

	<script type="text/javascript">
		//<![CDATA[	
		/* initialize the datepicker feature */
		jQuery(document).ready(function($) {
			$('table input#datepicker').datetimepicker({
				showSecond: true,
				timeFormat: 'hh:mm:ss',
				showOn: 'button',
				dateFormat: 'yy-mm-dd',
				minDate: 0,
				buttonImageOnly: true,
				buttonText: '',
				buttonImage: '../wp-includes/images/blank.gif' // calling the real calendar image in the admin.css. need a blank placeholder image b/c of IE.
			});
		});
		//]]>
	</script>

		<table class="form-table ad-meta-table">

			<tr>
				<th style="width:20%"><label for="cp_sys_ad_conf_id"><?php _e( 'Ad Info', APP_TD ); ?>:</label></th>
				<td class="ad-conf-id">
					<div id="ad-id"><div id="keyico"></div><?php _e( 'Ad ID', APP_TD ); ?>: <span><?php echo esc_html( get_post_meta($post->ID, 'cp_sys_ad_conf_id', true) ); ?></span></div>
					<div id="ad-stats"><div id="statsico"></div><?php _e( 'Views Today', APP_TD ); ?>: <strong><?php echo esc_html( get_post_meta($post->ID, 'cp_daily_count', true) ); ?></strong> | <?php _e( 'Views Total:', APP_TD ); ?> <strong><?php echo esc_html( get_post_meta($post->ID, 'cp_total_count', true) ); ?></strong></div>
				</td>
			</tr>

			<tr>
				<th style="width:20%"><label for="cp_sys_ad_conf_id"><?php _e( 'Submitted By', APP_TD ); ?>:</label></th>
				<td style="line-height:3.4em;">
					<?php
						// show the gravatar for the author
						echo get_avatar( $post->post_author, $size = '48', $default = '' );

						// show the author drop-down box 
						wp_dropdown_users( array(
							'who' => 'authors',
							'name' => 'post_author_override',
							'selected' => empty($post->ID) ? $user_ID : $post->post_author,
							'include_selected' => true,
						));

						// display the author display name
						$author = get_userdata( $post->post_author );
						echo '<br/><a href="user-edit.php?user_id=' . $author->ID . '">' . $author->display_name . '</a>';

					?>
				</td>
			</tr>

			<?php if ( cp_payments_is_enabled() ) { ?>
				<?php $total_cost = (float) get_post_meta( $post->ID, 'cp_sys_total_ad_cost', true ); ?>
				<tr>
					<th style="width:20%"><label for="cp_sys_total_ad_cost"><?php _e( 'Ad Terms', APP_TD ); ?>:</label></th>
					<td><?php printf( __( '%1$s for %2$s days', APP_TD ), appthemes_get_price( $total_cost ), get_post_meta( $post->ID, 'cp_sys_ad_duration', true ) ); ?></td>
				</tr>
			<?php } else { ?>
				<tr>
					<th style="width:20%"><label for="cp_sys_ad_duration"><?php _e( 'Duration', APP_TD ); ?>:</label></th>
					<td><?php printf( __( 'Listed for %s days', APP_TD ), get_post_meta( $post->ID, 'cp_sys_ad_duration', true ) ); ?></td>
				</tr>
			<?php } ?>

			<tr>
				<th style="width:20%"><label for="cp_sys_expire_date"><?php _e( 'Ad Expires', APP_TD ); ?>:</label></th>
				<td><input readonly type="text" name="cp_sys_expire_date" class="text" id="datepicker" value="<?php echo esc_attr( get_post_meta($post->ID, 'cp_sys_expire_date', true) ); ?>" /></td>
			</tr>

			<tr>
				<th colspan="2" style="padding:0px;">&nbsp;</th>
			</tr>

			<?php cp_edit_ad_fields( $results, $post->ID ); // build the edit ad meta box ?>


			<tr>
				<th style="width:20%"><label for="cp_sys_userIP"><?php _e( 'Submitted from IP', APP_TD ); ?>:</label></th>
				<td><?php echo esc_html( get_post_meta($post->ID, 'cp_sys_userIP', true) ); ?></td>
			</tr>

		</table>

	<?php
	endif;
	?>

<?php
}


// show the ad meta fields in a custom meta box
function cp_custom_images_meta_box() {
	global $post;

	echo cp_edit_ad_images( $post->ID ); // pull in the ad images

}


// builds the ad custom fields write panel
function cp_edit_ad_fields( $results, $post_id ) {
	global $wpdb;

	// add cp_sys fields to the array before adding cp_ custom fields
	$custom_fields_array = array('cp_sys_expire_date');

    foreach ( $results as $result ) :

        // get all the custom fields on the post and put into an array
        $custom_field_keys = get_post_custom_keys($post_id);

        if ( !$custom_field_keys ) continue;
            // wp_die('Error: There are no custom fields');

        // we only want key values that match the field_name in the custom field table .
        if ( in_array($result->field_name, $custom_field_keys) || $result->field_type == 'checkbox' ) :

			// add each custom field name to an array so we can save them correctly later
			$custom_fields_array[] = $result->field_name;

            // we found a match so go fetch the custom field value
            $post_meta_val = get_post_meta( $post_id, $result->field_name, true );

            // now loop through the form builder and make the proper field and display the value
            switch ( $result->field_type ) {

            case 'text box':
            ?>
				<tr>
					<th style="width:20%"><label for="<?php echo $result->field_name; ?>"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:</label></th>
					<td><input type="text" name="<?php echo $result->field_name; ?>" class="text" value="<?php echo esc_html($post_meta_val); ?>" /></td>
				</tr>

            <?php
            break;

            case 'drop-down':
            ?>

				<tr>
					<th style="width:20%"><label for="<?php echo $result->field_name; ?>"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:</label></th>
					<td>

						<select name="<?php echo $result->field_name; ?>" class="dropdownlist">

							<?php if ( !$result->field_req ) : ?><option value=""><?php _e( '-- Select --', APP_TD ); ?></option><?php endif; ?>
							<?php
							$options = explode( ',', $result->field_values );

							foreach ( $options as $option ) {
							?>
								<option style="min-width:177px" <?php if ($post_meta_val == trim($option)) { echo 'selected="yes"';} ?> value="<?php echo trim($option); ?>"><?php echo trim($option);?></option>
							<?php } ?>

						</select>

					</td>
				</tr>

            <?php
            break;

            case 'text area':

            ?>
				<tr>
					<th style="width:20%"><label for="<?php echo $result->field_name; ?>"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:</label></th>
					<td><textarea rows="4" cols="23" class="" name="<?php echo $result->field_name; ?>" id="<?php echo $result->field_name; ?>"><?php echo esc_html($post_meta_val); ?></textarea></td>
				</tr>

            <?php
            break;

			case 'radio':
				$options = explode( ',', $result->field_values );
			?>
				<tr>
					<th style="width:20%"><label for="<?php echo $result->field_name; ?>"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:</label></th>
					<td>
						<div class="scrollbox">
							<ol class="radios">	

								<?php foreach ( $options as $option ) { ?>
									<li>
										<input type="radio" name="<?php echo $result->field_name; ?>" id="<?php echo $result->field_name; ?>" value="<?php echo $option; ?>" class="radiolist" <?php if( trim($post_meta_val) == trim($option) ) { echo 'checked="checked"'; } ?>>&nbsp;&nbsp;<?php echo trim($option); ?>
									</li> <!-- #radio-button -->
								<?php } ?>

							</ol>
						</div>
					</td>
				</tr>

			<?php
			break;

			case 'checkbox':
				$options = explode( ',', $result->field_values );
        // fetch the custom field values as array
        $post_meta_val = get_post_meta( $post_id, $result->field_name, false );
			?>
				<tr>
					<th style="width:20%"><label for="<?php echo $result->field_name; ?>"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:</label></th>
					<td>
						<div class="scrollbox">
							<ol class="checkboxes">

							<?php foreach ( $options as $option ) { ?>
								<li>
									<input type="checkbox" name="<?php echo $result->field_name; ?>[]" value="<?php echo trim($option); ?>" class="checkboxlist" <?php if(is_array($post_meta_val) && in_array(trim($option), $post_meta_val)) { echo 'checked="checked"'; } ?> />&nbsp;&nbsp;&nbsp;<?php echo trim($option); ?>
								</li> <!-- #checkbox -->
							<?php } ?>

							</ol>
						</div>
					</td>
				</tr>


			<?php
			break;

            }

        endif;

    endforeach;

	// put all the custom field names into an hidden field so we can process them on save
	$custom_fields_vals = implode( ',', $custom_fields_array );
	?>

	<input type="hidden" name="custom_fields_vals" value="<?php echo $custom_fields_vals; ?>" />

<?php
// print_r($custom_fields_array);

}


// get the images associated with the ad
function cp_edit_ad_images( $ad_id ) {
  global $post;

	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $ad_id, 'order' => 'ASC', 'orderby' => 'ID', 'no_found_rows' => true );

	// get all the images associated to this ad
	$images = get_posts( $args );

	// print_r($images); // for debugging
	?>

	<script type="text/javascript">
	//<![CDATA[	
	jQuery(document).ready(function() {

		var formfield;

		/* upload an ad image */
		jQuery('input#upload_image_button').click(function() {
			formfield = jQuery(this).attr('rel');
			tb_show('', 'media-upload.php?post_id=<?php echo $post->ID; ?>&amp;type=image&amp;TB_iframe=true');
			return false;
		});

		window.original_send_to_editor = window.send_to_editor;

		/* send the uploaded image url to the field */
		window.send_to_editor = function(html) {
			if(formfield){
				var s = jQuery('img',html).attr('class'); // get the class with the image id
				var imageID = parseInt(/wp-image-(\d+)/.exec(s)[1], 10); // now grab the image id from the wp-image class
				var imgurl = jQuery('img',html).attr('src'); // get the image url
				var imgoutput = '<a href="' + imgurl + '" target="_blank"><img src="' + imgurl + '" /></a>'; //get the html to output for the image preview

				jQuery('#cp_print_url').val(imgurl); // return the image url to the field
				jQuery('input[name=new_ad_image_id]').val(imageID); // return the image url to the field
				jQuery('#cp_print_url').siblings('.upload_image_preview').slideDown().html(imgoutput); // display the uploaded image thumbnail
				tb_remove();
				formfield = null;
			}else{
				window.original_send_to_editor(html);
			}
		}

	});
	//]]>
	</script>

	<table class="form-table ad-meta-table">

	<?php
	// make sure we have images associated to the ad
	if ( $images ) :
		$i = 1;

		foreach ( $images as $image ) :

			// go get the width and height fields since they are stored in meta data
			$meta = wp_get_attachment_metadata( $image->ID );
			if ( is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta) )
				$media_dims = "<span id='media-dims-".$image->ID."'>{$meta['width']}&nbsp;&times;&nbsp;{$meta['height']}</span> ";
		?>

		<tr>
			<th style="width:20%"><label><?php _e( 'Image', APP_TD ); ?> <?php echo $i ?>:</label></th>

			<td>
				<div class="thumb-wrap-edit">
					<?php echo cp_get_attachment_link( $image->ID ); ?>
				</div>

				<div class="image-meta">
					<input class="checkbox" type="checkbox" name="image[]" value="<?php echo $image->ID; ?>">&nbsp;<?php _e( 'Delete Image', APP_TD ); ?><br />
					<strong><?php _e( 'Upload Date:', APP_TD ); ?></strong> <?php echo appthemes_display_date( $image->post_date, 'date' ); ?><br />
					<strong><?php _e( 'File Info:', APP_TD ); ?></strong> <?php echo $media_dims; ?> (<?php echo $image->post_mime_type; ?>)<br />
				</div>

				<div class="clr"></div>

				<?php $alt = get_post_meta( $image->ID, '_wp_attachment_image_alt', true ); // get the alt text and print out the field  ?>

				<p class="alt-text">
					<div class="labelwrapper">
						<label><?php _e( 'Alt Text:', APP_TD ); ?></label>
					</div>
					<input type="text" class="text" name="attachments[<?php echo $image->ID; ?>][image_alt]" id="image_alt" value="<?php if ( count($alt) ) echo esc_attr( stripslashes($alt) ); ?>" />
				</p>

			</td>

		</tr>

		<?php
		$i++;
		endforeach;

	endif;
	?>

		<tr>
			<th style="width:20%"><label><?php _e( 'New Image', APP_TD ); ?>:</label></th>

			<td>
				<input style="display:none;" type="text" readonly name="cp_print_url" id="cp_print_url" class="upload_image_url text" value="" />
				<input id="upload_image_button" class="upload_button button" rel="cp_print_url" type="button" value="<?php _e( 'Add Image', APP_TD ); ?>" />
				<br />
				<div class="upload_image_preview"></div>
				<input type="text" class="hide" id="imageid" name="new_ad_image_id" value="" />
			</td>

		</tr>

	</table>

	<?php

}



// save all meta values on the ad listing
function cp_save_meta_box( $post_id ) {
	global $wpdb, $post, $cp_options;

	// make sure something has been submitted from our nonce
	if ( ! isset( $_POST['ad_meta_wpnonce'] ) )
		return $post_id;

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( ! wp_verify_nonce( $_POST['ad_meta_wpnonce'], basename( __FILE__ ) ) )
		return $post_id;

	// verify if this is an auto save routine.
	// if it is our form and it has not been submitted, dont want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

	// lastly check to make sure this user has permissions to save post fields
	if ( !current_user_can('edit_post', $post_id) )
		return $post_id;


	// delete any images checked
    if ( !empty($_POST['image']) )
        cp_delete_image();

    // update the image alt text
	if ( !empty($_POST['attachments']) )
		cp_update_alt_text();

	// add a new image if one has been detected
	if ( $attach_id = $_POST['new_ad_image_id'] )
		wp_update_post( array( 'ID' => $attach_id, 'post_parent' => $post_id ) );


	// assemble the comma separated hidden fields back into an array so we can save them.
	$metafields = explode( ',', $_POST['custom_fields_vals'] );

	// loop through all custom meta fields and update values
	foreach ( $metafields as $name ) {

		if ( !isset($_POST[$name]) ) {
      delete_post_meta($post_id, $name);
    } else if ( is_array($_POST[$name]) ) {
  		delete_post_meta($post_id, $name);
      foreach ( $_POST[$name] as $checkbox_value )
        add_post_meta( $post_id, $name, $checkbox_value );
    } else {
  		update_post_meta( $post_id, $name, $_POST[$name] );
    }
	}


	// give the ad a unique ID if it's a new ad listing
	if ( !$cp_id = get_post_meta($post->ID, 'cp_sys_ad_conf_id', true) ) {
		$cp_item_id = cp_generate_id();
		add_post_meta( $post_id, 'cp_sys_ad_conf_id', $cp_item_id, true );
	}

	// save the IP address if it's a new ad listing
	if ( !$cp_ip = get_post_meta($post->ID, 'cp_sys_userIP', true) ) {
		add_post_meta( $post_id, 'cp_sys_userIP', appthemes_get_ip(), true );
	}

	// set stats to zero so we at least have some data
	if ( !$cp_dcount = get_post_meta($post->ID, 'cp_daily_count', true) ) {
		add_post_meta( $post_id, 'cp_daily_count', '0', true );
	}

	if ( !$cp_tcount = get_post_meta( $post->ID, 'cp_total_count', true) ) {
		add_post_meta( $post_id, 'cp_total_count', '0', true );
	}

  // set default ad duration, will need it to renew
	if ( !$cp_ad_duration = get_post_meta( $post->ID, 'cp_sys_ad_duration', true) ) {
    $ad_length = $cp_options->prun_period;
		add_post_meta( $post_id, 'cp_sys_ad_duration', $ad_length, true );
	}

  // set ad cost to zero, will need it for free renew
	if ( !$cp_tcost = get_post_meta( $post->ID, 'cp_sys_total_ad_cost', true) ) {
		add_post_meta( $post_id, 'cp_sys_total_ad_cost', '0.00', true );
	}

}
add_action( 'save_post', 'cp_save_meta_box' );


// add the custom edit ads page columns
function cp_edit_ad_columns( $columns ) {
	$columns = array(
		'cb' => "<input type=\"checkbox\" />",
		'title' => __( 'Title', APP_TD ),
		'author' => __( 'Author', APP_TD ),
		APP_TAX_CAT => __( 'Category', APP_TD ),
		APP_TAX_TAG => __( 'Tags', APP_TD ),
		'cp_price' => __( 'Price', APP_TD ),
		'cp_daily_count' => __( 'Views Today', APP_TD ),
		'cp_total_count' => __( 'Views Total', APP_TD ),
		'cp_sys_expire_date' => __( 'Expires', APP_TD ),
		'comments' => '<div class="vers"><img src="' . esc_url( admin_url( 'images/comment-grey-bubble.png' ) ) . '" /></div>',
		'date' => __( 'Date', APP_TD ),
	);

	return $columns;
}
add_filter( 'manage_' . APP_POST_TYPE . '_posts_columns', 'cp_edit_ad_columns' );


// register the columns as sortable
function cp_ad_column_sortable( $columns ) {
	$columns['cp_price'] = 'cp_price';
	$columns['cp_daily_count'] = 'cp_daily_count';
	$columns['cp_total_count'] = 'cp_total_count';
	$columns['cp_sys_expire_date'] = 'cp_sys_expire_date';

	return $columns;
}
add_filter( 'manage_edit-' . APP_POST_TYPE . '_sortable_columns', 'cp_ad_column_sortable' );


// how the custom columns should sort
function cp_ad_column_orderby( $vars ) {

	if ( isset( $vars['orderby'] ) && 'cp_price' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'cp_price',
			'orderby' => 'meta_value_num',
		) );
	}

	if ( isset( $vars['orderby'] ) && 'cp_daily_count' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'cp_daily_count',
			'orderby' => 'meta_value_num',
		) );
	}

	if ( isset( $vars['orderby'] ) && 'cp_total_count' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'cp_total_count',
			'orderby' => 'meta_value_num',
		) );
	}

	return $vars;
}
add_filter( 'request', 'cp_ad_column_orderby' );


// add the custom edit ads page column values
function cp_custom_columns( $column ) {
	global $post;
	$custom = get_post_custom();

	switch ( $column ) :

		case 'cp_sys_expire_date':
			if ( isset( $custom['cp_sys_expire_date'][0] ) && ! empty( $custom['cp_sys_expire_date'][0] ) )
				echo appthemes_display_date( $custom['cp_sys_expire_date'][0] );
		break;

		case 'cp_price':
			cp_get_price( $post->ID, 'cp_price' );
		break;
		
		case 'cp_daily_count':
			if ( isset($custom['cp_daily_count'][0]) && !empty($custom['cp_daily_count'][0]) )
				echo $custom['cp_daily_count'][0];
		break;
		
		case 'cp_total_count':
			if ( isset($custom['cp_total_count'][0]) && !empty($custom['cp_total_count'][0]) )
				echo $custom['cp_total_count'][0];
		break;

		case APP_TAX_TAG :
			echo get_the_term_list($post->ID, APP_TAX_TAG, '', ', ','');
		break;

		case APP_TAX_CAT :
			echo get_the_term_list($post->ID, APP_TAX_CAT, '', ', ','');
		break;

	endswitch;
}
add_action( 'manage_posts_custom_column', 'cp_custom_columns' );


// add the custom edit ad categories page columns
function cp_edit_ad_cats_columns( $columns ) {
	$columns = array(
		'cb' => "<input type=\"checkbox\" />",
		'name' => __( 'Name', APP_TD ),
		'description' => __( 'Description', APP_TD ),
		'slug' => __( 'Slug', APP_TD ),
		'num' => __( 'Ads', APP_TD ),
	);

	return $columns;
}
// don't enable this yet. see wp-admin function _tag_row for main code
//add_filter( 'manage_' . APP_TAX_CAT . '_posts_columns', 'cp_edit_ad_cats_columns' );


// add a sticky option to the edit ad submit box
function cp_sticky_option_submit_box() {
	global $post;

	if ( $post->post_type != APP_POST_TYPE )
		return;

	if ( current_user_can( 'edit_others_posts' ) ) {
?>
		<div class="misc-pub-section misc-pub-section-last sticky-ad">
			<span id="sticky"><input id="sticky" name="sticky" type="checkbox" value="sticky" <?php checked( is_sticky( $post->ID ) ); ?> tabindex="4" />
			<label for="sticky" class="selectit"><?php _e( 'Featured Ad (sticky)', APP_TD ); ?></label><br /></span>
		</div>
<?php
	} elseif ( is_sticky( $post->ID ) ) {
		echo html( 'input', array( 'name' => 'sticky', 'type' => 'hidden', 'value' => 'sticky' ) );
	}
}
add_action( 'post_submitbox_misc_actions', 'cp_sticky_option_submit_box' );


// hack until WP supports custom post type sticky feature
// add the sticky option to the quick edit area
function cp_sticky_option_quick_edit() {
	global $post;

	//if post is a custom post type and only during the first execution of the action quick_edit_custom_box
	if ( $post->post_type != APP_POST_TYPE || did_action( 'quick_edit_custom_box' ) !== 1 )
		return;
?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="alignleft">
				<input type="checkbox" name="sticky" value="sticky" />
				<span class="checkbox-title"><?php _e( 'Featured Ad (sticky)', APP_TD ); ?></span>
			</label>
		</div>
	</fieldset>
<?php
}
add_action( 'quick_edit_custom_box', 'cp_sticky_option_quick_edit' );


// custom user page columns
function cp_manage_users_columns( $columns ) {
	$newcol = array_slice( $columns, 0, 1 );
	$newcol = array_merge( $newcol, array( 'id' => __( 'Id', APP_TD ) ) );
	$columns = array_merge( $newcol, array_slice( $columns, 1 ) );

	$columns['cp_ads_count'] = __( 'Ads', APP_TD );
	$columns['last_login'] = __( 'Last Login', APP_TD );
	$columns['registered'] = __( 'Registered', APP_TD );

	return $columns;
}
add_action( 'manage_users_columns', 'cp_manage_users_columns' );


// register the columns as sortable
function cp_users_column_sortable( $columns ) {
	$columns['id'] = 'id';

	return $columns;
}
add_filter( 'manage_users_sortable_columns', 'cp_users_column_sortable' );


// display the coumn values for each user
function cp_manage_users_custom_column( $r, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'cp_ads_count' :
			global $cp_counts;

			if ( !isset( $cp_counts ) )
				$cp_counts = cp_count_ads();

			if ( !array_key_exists( $user_id, $cp_counts ) )
				$cp_counts = cp_count_ads();

			if ( $cp_counts[$user_id] > 0 ) {
				$r .= "<a href='edit.php?post_type=" . APP_POST_TYPE . "&author=$user_id' title='" . esc_attr__( 'View ads by this author', APP_TD ) . "' class='edit'>";
				$r .= $cp_counts[$user_id];
				$r .= '</a>';
			} else {
				$r .= 0;
			}
		break;
	
		case 'last_login' :
			$r = get_user_meta($user_id, 'last_login', true);
			if ( ! empty( $r ) )
				$r = appthemes_display_date( $r );
		break;

		case 'registered' :
			$user_info = get_userdata($user_id);
			$r = $user_info->user_registered;
			if ( ! empty( $r ) )
				$r = appthemes_display_date( $r, 'datetime', true );
		break;

		case 'id' :
			$r = $user_id;
	}

	return $r;
}
add_action( 'manage_users_custom_column', 'cp_manage_users_custom_column', 10, 3 );


// adds the thumbnail column to the WP Posts Edit SubPanel
function cp_thumbnail_column( $cols ) {
	$cols['thumbnail'] = __( 'Image', APP_TD );
	return $cols;
}
add_filter( 'manage_post_posts_columns', 'cp_thumbnail_column', 11 );
add_filter( 'manage_' . APP_POST_TYPE . '_posts_columns', 'cp_thumbnail_column', 11 );


function cp_thumbnail_value( $column_name, $post_id ) {
	$thumb = false;
	$width = 50;
	$height = 50;

	if ( 'thumbnail' == $column_name ) {
		// thumbnail of WP 2.9
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		// image from gallery
		$attachments = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true );
		} elseif ( $attachments ) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array( $width, $height ), true );
			}
		}

		if ( $thumb )
			echo $thumb;

	}
}
add_action( 'manage_post_posts_custom_column', 'cp_thumbnail_value', 11, 2 );
add_action( 'manage_' . APP_POST_TYPE . '_posts_custom_column', 'cp_thumbnail_value', 11, 2 );


/**
 * Removes 'From URL' tab in media uploader, need local image for ads.
 *
 * @param array $tabs
 *
 * @return array
 */
function cp_remove_media_from_url_tab( $tabs ) {
	if ( isset( $_REQUEST['post_id'] ) ) {
		$post_type = get_post_type( $_REQUEST['post_id'] );
		if ( APP_POST_TYPE == $post_type ) {
			unset( $tabs['type_url'] );
		}
	}

	return $tabs;
}
add_filter( 'media_upload_tabs', 'cp_remove_media_from_url_tab' );


// count the number of ad listings for the user
function cp_count_ads() {
	global $wpdb, $wp_list_table;

	$users = array_keys( $wp_list_table->items );
	$userlist = implode( ',', $users );
	$result = $wpdb->get_results( "SELECT post_author, COUNT(*) FROM $wpdb->posts WHERE post_type = '" . APP_POST_TYPE . "' AND post_author IN ($userlist) GROUP BY post_author", ARRAY_N );
	foreach ( $result as $row ) {
		$count[ $row[0] ] = $row[1];
	}

	foreach ( $users as $id ) {
		if ( ! isset( $count[ $id ] ) )
			$count[ $id ] = 0;
	}

	return $count;
}

