<?php
// Template Name: Ads Home Template
?>

<div class="content">

	<div class="content_botbg">

		<div class="content_res">

			<?php if (function_exists('tb_slider_ultimate')) tb_slider_ultimate(); else get_template_part( 'featured' ); ?>

			<!-- left block -->
			<div class="content_left">

				<?php if ( $cp_options->home_layout == 'directory' ) { ?>

				<div class="shadowblock_out_cat">

					<div class="shadowblock_out">

						<div class="shadowblock">

							<div class="title"><h1 class="single dotted"><?php _e('Ad Categories', APP_TD); ?></h1></div>

								<div id="directory" class="directory <?php cp_display_style( 'dir_cols' ); ?>">

								<?php echo cp_create_categories_list( 'dir' ); ?>

								<div class="clr"></div>
	
							</div><!--/directory-->

						</div><!-- /shadowblock -->
	
					</div><!-- /shadowblock_out -->
				</div><!-- /shadowblock_out_cat -->

				<?php } ?>

				<div class="tabcontrol">

					<ul class="tabnavig">
						<li><a href="#block1"><span class="big"><?php _e( 'Latest', APP_TD ); ?></span></a></li>
						<li><a href="#block2"><span class="big"><?php _e( 'Popular', APP_TD ); ?></span></a></li>
						<li><a href="#block3"><span class="big"><?php _e( 'Random', APP_TD ); ?></span></a></li>
						<li><a href="#block4"><span class="big"><?php _e( 'Featured', APP_TD )?></span></a></li>
					</ul>


					<?php
						remove_action( 'appthemes_after_endwhile', 'cp_do_pagination' );
						$post_type_url = add_query_arg( array( 'paged' => 2 ), get_post_type_archive_link( APP_POST_TYPE ) );
					?>


					<!-- tab 1 -->
					<div id="block1">

						<div class="clr"></div>

						<?php
							// show all ads but make sure the sticky featured ads don't show up first
							$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
							query_posts( array( 'post_type' => APP_POST_TYPE, 'ignore_sticky_posts' => 1, 'paged' => $paged ) );
							$total_pages = max( 1, absint( $wp_query->max_num_pages ) );
						?>

						<?php get_template_part( 'loop', 'ad_listing' ); ?>

						<?php
							if ( $total_pages > 1 ) {
						?>
								<div class="paging"><a href="<?php echo $post_type_url; ?>"> <?php _e( 'View More Ads', APP_TD ); ?> </a></div>
						<?php } ?>

					</div><!-- /block1 -->




					<!-- tab 2 -->
					<div id="block2">

						<div class="clr"></div>

						<?php get_template_part( 'loop', 'popular' ); ?>

						<?php
							global $cp_has_next_page;
							if ( $cp_has_next_page ) {
								$popular_url = add_query_arg( array( 'sort' => 'popular' ), $post_type_url );
						?>
								<div class="paging"><a href="<?php echo $popular_url; ?>"> <?php _e( 'View More Ads', APP_TD ); ?> </a></div>
						<?php } ?>

						<?php wp_reset_query(); ?>

					</div><!-- /block2 -->




					<!-- tab 3 -->
					<div id="block3">

						<div class="clr"></div>

						<?php
							// show all random ads but make sure the sticky featured ads don't show up first
							$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
							query_posts( array( 'post_type' => APP_POST_TYPE, 'ignore_sticky_posts' => 1, 'paged' => $paged, 'orderby' => 'rand' ) );
							$total_pages = max( 1, absint( $wp_query->max_num_pages ) );
						?>

						<?php get_template_part( 'loop', 'ad_listing' ); ?>

						<?php
							if ( $total_pages > 1 ) {
								$random_url = add_query_arg( array( 'sort' => 'random' ), $post_type_url );
						?>
								<div class="paging"><a href="<?php echo $random_url; ?>"> <?php _e( 'View More Ads', APP_TD ); ?> </a></div>
						<?php } ?>

					</div><!-- /block3 -->


					<!-- tab 4 -->
					<div id="block4">

						<div class="clr"></div>

                				<?php // query featured ads
                 					$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                  					query_posts( array( 'post_type' => APP_POST_TYPE, 'post__in' => get_option('sticky_posts'), 'paged' => $paged ) );
                  					$total_pages = max( 1, absint( $wp_query->max_num_pages ) );
                				?>

                				<?php get_template_part( 'loop', 'ad_listing' ); ?>

						<?php
							if ( $total_pages > 1 ) {
									$featured_url = add_query_arg( array( 'featured' => '1' ), $post_type_url );
						?>
									<div class="paging"><a href="<?php echo $featured_url; ?>"> <?php _e( 'View More Ads', APP_TD ); ?> </a></div>
						<?php } ?>

            		</div><!-- /block4 -->

				</div><!-- /tabcontrol -->

			</div><!-- /content_left -->


			<?php get_sidebar(); ?>


			<div class="clr"></div>

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

<div id="allCat" style="display:none"><?php _e( 'View All', APP_TD ); ?></div>

</div><!-- /content -->

<script type="text/javascript">

/* custom categories toggle script */
    jQuery(document).ready(function($) {

		jQuery(".shadowblock #directory .catcol ul .maincat").children("a").each(function () {
			var $ftogg = jQuery(this).parent(".maincat").children(".subcat-list").html();

			try {

				if ($ftogg.indexOf("li") > 0) {
					jQuery(this).parent(".maincat").children(".subcat-list").html("<li class='allcats'><a href='"+jQuery(this).attr("href")+"'>"+jQuery('#allCat').html()+"</a></li>" + $ftogg);
					jQuery(this).attr("href","#categories");
				}
			}
			catch (err) {}
    		});	

		jQuery(".shadowblock #directory .catcol ul .maincat ul").css("display","none");

    		jQuery(".shadowblock #directory .catcol ul .maincat").each(function () {
			var sCode = jQuery(this).html();

			var longi = "";

			try {
				longi = jQuery(this).children(".subcat-list").length;
			}
			catch (err) {
					$ftog = "";
			}

			if (longi==1) { jQuery(this).html("<div class='expand'></div>" + sCode); }
			else { jQuery(this).html("<div class='expand2'></div>" + sCode); }
    		});

    		jQuery(".shadowblock #directory .catcol ul .maincat").each(function () {
			var longi = "";

			try {
				longi = jQuery(this).children(".subcat-list").length;
			}
			catch (err) {
					$ftog = "";
			}

			if (longi==0) { jQuery(this).children("div").css("background-image","none"); }
    		});

		jQuery(".shadowblock #directory .catcol ul .maincat .expand").click(function(){
			var sVisible = jQuery(this).parent("li").children("ul").css("display");

			if (sVisible == "none") {
				jQuery(this).parent("li").children("ul").css("display","");
				jQuery(this).css("background-position","0 -10px");
			}
			else {
				jQuery(this).parent("li").children("ul").css("display","none");
				jQuery(this).css("background-position","0 0px");
			}
		});

		jQuery(".shadowblock #directory .catcol ul .maincat").children("a").click(function(){
			var sVisible = jQuery(this).parent("li").children("ul").css("display");

			if (sVisible == "none") {
				jQuery(this).parent("li").children("ul").css("display","");
				jQuery(this).parent("li").children(".expand").css("background-position","0 -10px");
			}
			else {
				jQuery(this).parent("li").children("ul").css("display","none");
				jQuery(this).parent("li").children(".expand").css("background-position","0 0px");
			}
		});

		jQuery(".shadowblock .title .toggle").click(function(){
			var sVisible = "" + jQuery(".shadowblock #directory").css("display");
			if (sVisible == "none") {
				jQuery(".shadowblock #directory").css("display","");
				jQuery(this).css("background-position","0 -25px");
			}
			else {
				jQuery(".shadowblock #directory").css("display","none");
				jQuery(this).css("background-position","0 0");
			}
		});

	});
</script>