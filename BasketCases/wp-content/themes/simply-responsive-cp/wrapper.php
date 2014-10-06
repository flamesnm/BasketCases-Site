<?php global $app_abbr, $cp_options; ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns:fb="http://ogp.me/ns/fb#" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if (is_single()) { ?>
	<meta property="fb:app_id" content="594911647187742" />
    <meta property="og:url" content="<?php the_permalink(); ?>" />
    <meta property="og:title" content="<?php the_title(); ?>" />
    <meta property="og:description" content="<?php echo cp_get_content_preview( 170 ); ?>" />
    <meta property="og:image:url" content="<?php the_permalink(); ?>" />
    <meta property="og:image" content="<?php if ( $cp_options->ad_images ) cp_get_single_image_url('noresize', true); ?>" />
    <meta property="og:image" content="<?php if ( $cp_options->ad_images ) cp_get_single_image_url($post->ID, 'thumbnail'); ?>" />
    <meta property="og:image" content="http://www.fabtalent.co.uk/wp-content/uploads/2013/04/simply.png" />
    <meta property="og:type" content="website" />
<?php } ?>
	<title><?php wp_title(''); ?></title>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo appthemes_get_feed_url(); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<!--[if lt IE 9]>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!--[if lte IE 7]>
    <link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_directory'); ?>/ie7.css" media="screen" type="text/css" />
<![endif]-->
<!--[if IE 6]>
	<meta http-equiv="refresh" content="0; url=http://www.microsoft.com/windows/internet-explorer/default.aspx" />
	<script type="text/javascript">
	/* <![CDATA[ */ window.top.location ='http://www.microsoft.com/windows/internet-explorer/default.aspx'; /* ]]> */
    <link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_directory'); ?>/ie7.css" media="screen" type="text/css" />
    <style type="text/css">
	body{ behavior: url("http://www.rlmseo.com/wp-content/themes/u-design/scripts/csshover3.htc"); }
    </style>
<![endif]-->
	<?php if ( is_singular() && get_option('thread_comments') ) wp_enqueue_script('comment-reply'); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php appthemes_before(); ?>

	<div class="container">

		<?php if ( $cp_options->debug_mode ) { ?><div class="debug"><h3><?php _e( 'Debug Mode On', APP_TD ); ?></h3><?php print_r( $wp_query->query_vars ); ?></div><?php } ?>

		<?php appthemes_before_header(); ?>
		<?php get_header( app_template_base() ); ?>
		<?php appthemes_after_header(); ?>

		<?php get_template_part( 'includes/theme', 'searchbar' ); ?>

		<?php load_template( app_template_path() ); ?>

		<?php appthemes_before_footer(); ?>
		<?php get_footer( app_template_base() ); ?>
		<?php appthemes_after_footer(); ?>

	</div><!-- /container -->

  <?php wp_footer(); ?>

	<?php appthemes_after(); ?>

</body>

</html>