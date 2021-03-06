<?php
/*
 * Template Name: User Dashboard
 *
 * This template must be assigned to a page
 * in order for it to work correctly
 *
*/

$current_user = wp_get_current_user(); // grabs the user info and puts into vars
$display_user_name = cp_get_user_name();
?>


<div class="content user-dashboard">

	<div class="content_botbg">

		<div class="content_res">

			<!-- left block -->
			<div class="content_left">

				<div class="shadowblock_out">

					<div class="shadowblock">

						<h2 class="single dotted"><?php printf( __( "%s's Dashboard", APP_TD ), $display_user_name ); ?></h2>

						<?php do_action( 'appthemes_notices' ); ?>

						<?php
							// setup the pagination and query
							$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
							query_posts( array( 'posts_per_page' => 10, 'post_type' => APP_POST_TYPE, 'post_status' => 'publish, pending, draft', 'author' => $current_user->ID, 'paged' => $paged ) );

							// build the row counter depending on what page we're on
							if ( $paged == 1 ) $i = 0; else $i = $paged * 10 - 10;
						?>

						<?php if ( have_posts() ) : ?>

						<p><?php _e( 'Below you will find a listing of all your classified ads. Click on one of the options to perform a specific task. If you have any questions, please contact the site administrator.', APP_TD ); ?></p>

						<table border="0" cellpadding="4" cellspacing="1" class="tblwide footable">
							<thead>
								<tr>
									<th class="no-th" width="4px" data-hide="phone">&nbsp;</th>
									<th class="text-left">&nbsp;<?php _e( 'Title', APP_TD ); ?></th>
									<th class="views" width="30px" data-hide="phone"><?php _e( 'Views', APP_TD ); ?></th>
									<th class="status" width="60px" data-hide="phone"><?php _e( 'Status', APP_TD ); ?></th>
									<th width="90px"><div style="text-align: center;"><?php _e( 'Options', APP_TD ); ?></div></th>
								</tr>
							</thead>
							<tbody>

							<?php while( have_posts() ) : the_post(); $i++; ?>

								<?php
									$expire_time = strtotime( get_post_meta($post->ID, 'cp_sys_expire_date', true) );
									$expire_date = appthemes_display_date( $expire_time );

									$total_cost = get_post_meta($post->ID, 'cp_sys_total_ad_cost', true);

									if (get_post_meta($post->ID, 'cp_total_count', true))
										$ad_views = number_format(get_post_meta($post->ID, 'cp_total_count', true));
									else
										$ad_views = '-';


									// now let's figure out what the ad status and options should be
									// it's a live and published ad
									if ( $post->post_status == 'publish' ) {

										$post_status = 'live';
										$post_status_name = __( 'Live Until', APP_TD ) . '<br /><p class="small">(' . $expire_date . ')</p>';
										$fontcolor = '#666666';
										$postimage = 'pause.png';
										$postalt =  __( 'Pause Ad', APP_TD );
										$postaction = 'pause';

									// it's a pending ad which gives us several possibilities
									} elseif ( $post->post_status == 'pending' ) {

										if ( cp_have_pending_payment( $post->ID ) ) {
											$post_status = 'pending_payment';
											$post_status_name = __( 'Awaiting payment', APP_TD );
											$fontcolor = '#b22222';
											$postimage = '';
											$postalt = '';
											$postaction = 'pending';
										} else {
											$post_status = 'pending';
											$post_status_name = __( 'Awaiting approval', APP_TD );
											$fontcolor = '#b22222';
											$postimage = '';
											$postalt = '';
											$postaction = 'pending';
										}

									} elseif ( $post->post_status == 'draft' ) {

										// current date is past the expires date so mark ad ended
										if ( current_time('timestamp') > $expire_time ) {
											$post_status = 'ended';
											$post_status_name = __( 'Ended', APP_TD ) . '<br /><p class="small">(' . $expire_date . ')</p>';
											$fontcolor = '#666666';
											$postimage = '';
											$postalt = '';
											$postaction = 'ended';

										// ad has been paused by ad owner
										} else {
											$post_status = 'offline';
											$post_status_name = __( 'Offline', APP_TD );
											$fontcolor = '#bbbbbb';
											$postimage = 'start-blue.png';
											$postalt = __( 'Restart ad', APP_TD );
											$postaction = 'restart';
										}

									} else {
										$post_status = '&mdash;';
									}
								?>


								<tr class="even">
									<td class="text-right no-th"><?php echo $i; ?>.</td>

									<td>
										<h3>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<div class="metad"><span class="folder"> &nbsp;</span><?php echo get_the_term_list(get_the_id(), APP_TAX_CAT, '', ', ', ''); ?> | <span class="clock"><span><?php echo appthemes_display_date( $post->post_date, 'date' ); ?></span></span></div>
									</td>

									<td class="text-center views"><?php echo $ad_views; ?></td>

									<td class="text-center status"><span style="color:<?php echo $fontcolor; ?>;"><?php echo $post_status_name; ?></span></td>

									<td class="text-center">

										<?php 
											// edit
											if ( in_array( $post_status, array( 'pending', 'pending_payment' ) ) ) {
												if ( $cp_options->ad_edit && $cp_options->moderate_edited_ads ) {
													$edit_url = add_query_arg( array( 'aid' => $post->ID ), CP_EDIT_URL );
													$edit_img = html( 'img', array( 'src' => get_stylesheet_directory_uri() . '/images/pencil.png', 'border' => '0', 'title' => __( 'Edit Ad', APP_TD ), 'alt' => __( 'Edit Ad', APP_TD ) ) );
													echo html( 'a', array( 'href' => $edit_url, 'title' => __( 'Edit Ad', APP_TD ) ), $edit_img ) . ' ';
												}
											} else if ( in_array( $post_status, array( 'live', 'offline' ) ) ) {
												if ( $cp_options->ad_edit ) {
													$edit_url = add_query_arg( array( 'aid' => $post->ID ), CP_EDIT_URL );
													$edit_img = html( 'img', array( 'src' => get_stylesheet_directory_uri() . '/images/pencil.png', 'border' => '0', 'title' => __( 'Edit Ad', APP_TD ), 'alt' => __( 'Edit Ad', APP_TD ) ) );
													echo html( 'a', array( 'href' => $edit_url, 'title' => __( 'Edit Ad', APP_TD ) ), $edit_img ) . ' ';
												}
											}

											// delete
											$delete_url = add_query_arg( array( 'aid' => $post->ID, 'action' => 'delete' ), CP_DASHBOARD_URL );
											$delete_img = html( 'img', array( 'src' => get_stylesheet_directory_uri() . '/images/cross.png', 'border' => '0', 'title' => __( 'Delete Ad', APP_TD ), 'alt' => __( 'Delete Ad', APP_TD ) ) );
											echo html( 'a', array( 'href' => $delete_url, 'onclick' => 'return confirmBeforeDeleteAd();', 'title' => __( 'Delete Ad', APP_TD ) ), $delete_img ) . ' ';

											// pause/restart, sold
											if ( in_array( $post_status, array( 'live', 'offline' ) ) ) {
												$postaction_url = add_query_arg( array( 'aid' => $post->ID, 'action' => $postaction ), CP_DASHBOARD_URL );
												$postaction_img = html( 'img', array( 'src' => get_stylesheet_directory_uri() . '/images/' . $postimage, 'border' => '0', 'title' => $postalt, 'alt' => $postalt ) );
												echo html( 'a', array( 'href' => $postaction_url, 'title' => $postalt ), $postaction_img ) . ' ';

												if ( get_post_meta($post->ID, 'cp_ad_sold', true) != 'yes' ) {
													$setSold_url = add_query_arg( array( 'aid' => $post->ID, 'action' => 'setSold' ), CP_DASHBOARD_URL );
													echo '<br />' . html( 'a', array( 'href' => $setSold_url, 'title' => __( 'Mark sold', APP_TD ) ), __( 'Mark Sold', APP_TD ) );
												} else {
													$unsetSold_url = add_query_arg( array( 'aid' => $post->ID, 'action' => 'unsetSold' ), CP_DASHBOARD_URL );
													echo '<br />' . html( 'a', array( 'href' => $unsetSold_url, 'title' => __( 'Unmark sold', APP_TD ) ), __( 'Unmark Sold', APP_TD ) );
												}
												if ( get_post_meta($post->ID, 'cp_ad_pick', true) != 'yes' ) {
													$setPick_url = add_query_arg( array( 'aid' => $post->ID, 'action' => 'setPick' ), CP_DASHBOARD_URL );
													echo '<br />' . html( 'a', array( 'href' => $setPick_url, 'title' => __( 'Mark pending', APP_TD ) ), __( 'Mark Pending', APP_TD ) );
												} else {
													$unsetPick_url = add_query_arg( array( 'aid' => $post->ID, 'action' => 'unsetPick' ), CP_DASHBOARD_URL );
													echo '<br />' . html( 'a', array( 'href' => $unsetPick_url, 'title' => __( 'Unmark pending', APP_TD ) ), __( 'Unmark Pending', APP_TD ) );
												}
											}

											// relist
											if ( $cp_options->allow_relist && $post_status == 'ended' ) {
												$relist_url = add_query_arg( array( 'renew' => $post->ID ), CP_ADD_NEW_URL );
												echo '<br />' . html( 'a', array( 'href' => $relist_url, 'title' => __( 'Relist Ad', APP_TD ) ), __( 'Relist Ad', APP_TD ) );
											}

											// pay, reset gateway
											if ( $post_status == 'pending_payment' ) {
												$order_url = cp_get_order_permalink( $post->ID );
												echo '<br />' . html( 'a', array( 'href' => $order_url ), __( 'Pay now', APP_TD ) );
												echo '<br />' . html( 'a', array( 'href' => add_query_arg( 'cancel', true, $order_url ), 'title' => __( 'Reset Payment Gateway', APP_TD ) ), __( 'Reset Gateway', APP_TD ) );
											}

										?>

									</td>

								</tr>

							<?php endwhile; ?>
							</tbody>
						</table>
						<?php if(function_exists('appthemes_pagination')) appthemes_pagination(); ?>

						<?php else : ?>

							<div class="pad10"></div>
							<p class="text-center"><?php _e( 'You currently have no classified ads.', APP_TD ); ?></p>
							<div class="pad25"></div>

						<?php endif; ?>

						<?php wp_reset_query(); ?>

					</div><!-- /shadowblock -->

				</div><!-- /shadowblock_out -->

			</div><!-- /content_left -->

			<?php get_sidebar( 'user' ); ?>

			<div class="clr"></div>

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

</div><!-- /content -->
