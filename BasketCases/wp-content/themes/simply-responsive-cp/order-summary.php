
<div class="content">

	<div class="content_botbg">

		<div class="content_res">

			<div id="breadcrumb"><?php cp_breadcrumb(); ?></div>

			<?php do_action( 'appthemes_notices' ); ?>

			<div class="shadowblock_out">

				<div class="shadowblock">

					<div class="post">

						<h1 class="single dotted"><?php _e( 'Order Summary', APP_TD ); ?></h1>

						<div class="order-summary">

						<?php the_order_summary(); ?><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/tku.png" alt="Thank you!" width="200" height="121" class="alignright size-full wp-image-132" />
						
						<div class="pad10"></div>
						
						<h1><?php _e( 'Thank you!', APP_TD ); ?></span></h1>
						<h2><?php _e( 'Your ad listing has been submitted for review.', APP_TD ); ?></h2>
						<p><?php _e( 'You can check the status by viewing your ', APP_TD ); ?><a href="<?php echo home_url('/dashboard'); ?>"><?php _e( 'dashboard.', APP_TD ); ?></a></p>
						
						<div class="pad20"></div>

						</div>

						<div class="clr"></div>

					</div><!--/post-->

				</div><!-- /shadowblock -->

			</div><!-- /shadowblock_out -->

			<div class="clr"></div>

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

</div><!-- /content -->
