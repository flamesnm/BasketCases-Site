<?php
/**
 * Loop for displaying most popular ads
 *
 * @package ClassiPress
 * @author AppThemes
 *
 */
global $cp_options;
?>

<?php appthemes_before_loop(); ?>

<?php if ( $query = cp_get_popular_ads() ) : ?>

    <?php while ( $query->have_posts() ) : $query->the_post(); ?>

        <?php appthemes_before_post(); ?>

		<?php if (is_sticky()){ ?>

        <div class="post-block-out-sticky <?php cp_display_style( 'featured' ); ?>">
    
		<?php } else { ?>

        <div class="post-block-out <?php cp_display_style( 'featured' ); ?>">

		<?php } ?>

            <div class="post-block">
				<?php if ( get_post_meta( $post->ID, 'cp_price_negotiable', true ) ) echo '<span class="negotiable-text">' . __( 'Price Negotiable', APP_TD ) . '</span> '; else echo ''; ?>
                <div class="post-left">

					<?php if(in_array($post->ID, get_option('sticky_posts'))) { ?>
						<span class="ft_featured"><?php _e('Featured', APP_TD); ?></span>
					<?php } ?>	
					<?php if (get_post_meta($post->ID, 'cp_ad_sold', true) == 'yes') : ?>
						<span class="ft_sold"><?php _e('Sold', APP_TD); ?></span>
					<?php endif; ?>
					<?php if (get_post_meta($post->ID, 'cp_ad_pick', true) == 'yes') : ?>
						<span class="ft_pick"><?php _e('Sale Pending', APP_TD); ?></span>
					<?php endif; ?>

					<?php if ( $cp_options->ad_images ) cp_ad_loop_thumbnail(); ?>

                </div>

                <div class="<?php cp_display_style( array( 'ad_images', 'ad_class' ) ); ?>">

                    <?php appthemes_before_post_title(); ?>

                    <h3><a href="<?php the_permalink(); ?>"><?php if ( mb_strlen( get_the_title() ) >= 75 ) echo mb_substr( get_the_title(), 0, 75 ).'...'; else the_title(); ?></a></h3>

                    <div class="clr"></div>

                    <?php appthemes_after_post_title(); ?>

                    <div class="clr"></div>

                    <?php appthemes_before_post_content(); ?>

                    <p class="post-desc"><?php echo cp_get_content_preview( 160 ); ?></p>

                    <span class="clock-ft"><span><?php echo appthemes_date_posted($post->post_date); ?></span></span>

                    <?php appthemes_after_post_content(); ?>

                    <div class="clr"></div>

                </div>

                <div class="clr"></div>

            </div><!-- /post-block -->

        </div><!-- /post-block-out -->

      <?php appthemes_after_post(); ?>

    <?php endwhile; ?>

    <?php appthemes_after_endwhile(); ?>

<?php else: ?>

    <?php appthemes_loop_else_ft(); ?>

<?php endif; ?>

<?php appthemes_after_loop(); ?>

<?php wp_reset_postdata(); ?>
