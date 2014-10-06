<?php
/*
Template Name: Categories Template
*/
?>


<div class="content">

	<div class="content_botbg">

		<div class="content_res">

			<div id="breadcrumb">
				<?php if ( function_exists('cp_breadcrumb') ) cp_breadcrumb(); ?>
			</div>

			<div class="content_left">

				<div class="shadowblock_out">

					<div class="shadowblock">

						<h2 class="dotted"><?php _e( 'Ad Categories', APP_TD ); ?></h2>

						<div id="directory" class="directory <?php cp_display_style( 'dir_cols' ); ?>">

							<?php echo cp_create_categories_list( 'dir' ); ?>

							<div class="clr"></div>

						</div><!--/directory-->

					</div><!-- /shadowblock -->

				</div><!-- /shadowblock_out -->

			</div><!-- /content_left -->

			<?php get_sidebar(); ?>

			<div class="clr"></div>

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

</div><!-- /content -->


<div id="allCat" style="display:none"><?php _e( 'View All', APP_TD ); ?></div>

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

		jQuery(".shadowblock #directory .catcol ul .maincat ul").css("display","");

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
				jQuery(this).parent("li").children("ul").css("display","");
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