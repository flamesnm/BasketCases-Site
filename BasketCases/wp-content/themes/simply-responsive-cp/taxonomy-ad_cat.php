<div class="content">

    <div class="content_botbg">

      <div class="content_res">
        <div id="breadcrumb">
          <?php if ( function_exists('cp_breadcrumb') ) cp_breadcrumb(); ?>
        </div>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/js/easing.js?ver=1.3"></script>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/includes/js/jcarousellite.min.js?ver=1.8.3"></script>
		<?php
			if ( file_exists(STYLESHEETPATH . '/cat-featured.php') )
			include_once(STYLESHEETPATH . '/cat-featured.php');
		?>
			<div class="content_left">
	
				<?php $term = get_queried_object(); ?>
	
				<div class="shadowblock_top">
	
					<div class="shadowblock">
	
					  <div id="catrss"><a href="<?php echo get_term_feed_link($term->term_id, $taxonomy); ?>" title="<?php printf( __( '%s RSS Feed', APP_TD ), $term->name ); ?>"><?php printf( __( '%s RSS Feed', APP_TD ), $term->name ); ?></a></div>
					  <h2 class="listing"><?php _e( 'Total Listings for', APP_TD ); ?> <?php echo $term->name; ?> (<?php echo $wp_query->found_posts; ?>)</h2>
	
					  <?php echo $term->description; ?>
	
					</div><!-- /shadowblock -->
	
				</div><!-- /shadowblock_out -->

 			<?php wp_reset_query();?>
 			
 			<?php get_template_part( 'loop', 'ad_listing' ); ?>
	
			</div><!-- /content_left -->

				<?php get_sidebar(); ?>

			<div class="clr"></div>

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

</div><!-- /content -->