<?php get_header()?>
	<div id="content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); //The Loop?>
			<div <?php post_class()?>>
				<h2 class="title"><a href="<?php the_permalink()?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title()?></a></h2>
				<p><?php the_date()?></p>
				<div class="page-content"><?php the_content()?></div>
			</div>
			<?php posts_nav_link() //Previous and Next page buttons?>
		<?php endwhile; else: //When there are no posts to list (do not confuse with 404.php)?>
			<div class="post">
				<h1 class="title"><?php _e('No posts found','building-blocks')?></h1>
				<div class="page-content"><p><?php _e('There are no posts to display here.','building-blocks')?></p></div>
			</div>
		<?php endif; ?>
	</div>
	<?php get_sidebar()?>
<?php get_footer()?>