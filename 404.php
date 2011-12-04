<?php get_header()?>
	<div id="content">
		<div class="post">
			<h1 class="title"><?php _e('Page not found',WP_THEME_SLUG)?></h1>
			<div class="content">
				<p><?php _e('The page you are looking for does not exist.',WP_THEME_SLUG)?></p>
				<p><a href="<?php echo site_url()?>">« <?php _e('Return to the home page',WP_THEME_SLUG)?></a></p>
			</div>
		</div>
	</div>
	<?php get_sidebar()?>
<?php get_footer()?>