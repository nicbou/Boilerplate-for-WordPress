<?php get_header()?>
	<div id="content">
		<div class="post">
			<h1 class="title"><?php _e('Page not found','boilerplate-barebones')?></h1>
			<div class="content">
				<p><?php _e('The page you are looking for does not exist.','boilerplate-barebones')?></p>
				<p><a href="<?php bloginfo('url')?>">« <?php _e('Return to the home page','boilerplate-barebones')?></a></p>
			</div>
		</div>
	</div>
	<?php get_sidebar()?>
<?php get_footer()?>