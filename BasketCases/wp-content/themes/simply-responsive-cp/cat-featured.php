<?php
/**
 * The featured slider on the cat page
 *
 */
global $cp_options;
?>

<?php if ( $cp_options->enable_featured ) : ?>

	<script type="text/javascript">
		// <![CDATA[
		/* featured listings slider */
		jQuery(document).ready(function($) {
			$('.slider').jCarouselLite({
				btnNext: '.next',
				btnPrev: '.prev',
				visible: ( $(window).width() < 870 ) ? 4 : 5,
				pause: true,
				auto: true,
				timeout: 2800,
				speed: 1100,
				easing: 'easeOutQuint' // for different types of easing, see easing.js
			});
		});
		// ]]>
	</script>

	<?php appthemes_before_loop('featured'); ?>

	<?php
		if (is_tax()) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			query_posts( array('post__in' => get_option('sticky_posts'), 'post_type' => APP_POST_TYPE, 'post_status' => 'publish', 'orderby' => 'rand', 'posts_per_page'=>'-1', 'ad_cat'=>$term->slug) );
		}
		if ( have_posts() ) :
	?>

	<?php wp_reset_query(); ?>

		<!-- featured listings -->
			<div class="shadowblockdir">

				<span class="ft_featured"><?php _e('Featured Listings', APP_TD); ?></span>

				<div class="sliderblockdir">
					<div class="prev"></div>
					<div id="sliderlist">

						<div class="slider">

							<ul>
								<?php while ( have_posts() ) : the_post(); ?>
						
									<?php appthemes_before_post('featured'); ?>

									<li>
										<span class="feat_block">
											<?php if ( $post->post_type == 'page' || $post->post_type == 'post' )
												return;
												$price = get_post_meta( $post->ID, 'cp_price', true );
												if ( !empty( $price) /* AND ( $price > 0 ) */ ) { // unless empty add the ad price field in the loop as usual
											?>
												<span class="price_sm"><?php cp_get_price($post->ID, 'cp_price'); ?></span>
											<?php } else { ?>
												<div class="pad12"></div>
											<?php } ?>
											<div class="feat_image">
												<?php if (get_post_meta($post->ID, 'cp_ad_sold', true) == 'yes') : ?>
													<span class="ft_sold"><?php _e('Sold', APP_TD); ?></span>
												<?php endif; ?>
												<?php if (get_post_meta($post->ID, 'cp_ad_pick', true) == 'yes') : ?>
													<span class="ft_pick"><?php _e('Sale Pending', APP_TD); ?></span>
												<?php endif; ?>

												<?php if ( $cp_options->ad_images ) ft_ad_featured_thumbnail(); ?>

											</div>

										</span>

										<div class="clr"></div>

										<?php appthemes_before_post_title('featured'); ?>

										<span class="title"><a href="<?php the_permalink(); ?>"><?php if ( mb_strlen( get_the_title() ) >= $cp_options->featured_trim ) echo mb_substr( get_the_title(), 0, $cp_options->featured_trim ).'...'; else the_title(); ?></a></span>

                                        <?php appthemes_after_post_title('featured'); ?>

					                    <div class="clr"></div>

										<span class="owner">by <?php the_author_posts_link(); ?></span>

                                    </li>

									<?php appthemes_after_post('featured'); ?>

								<?php endwhile; ?>

								<?php appthemes_after_endwhile('featured'); ?>

							</ul>

						</div>

					</div><!-- /slider -->

					<div class="next"></div>

					<div class="clr"></div>

				</div><!-- /sliderblock -->

			</div><!-- /shadowblock -->

	<?php endif; ?>

	<?php appthemes_after_loop('featured'); ?>

	<?php wp_reset_postdata(); ?>

<?php endif; // end feature ad slider check ?>

