<?php get_header()?>
	<div id="content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); //The Loop?>
		<div <?php post_class()?>>
			<h1 class="title"><?php the_title()?></h1>
			<p><?php the_date()?></p>
			<div class="content"><?php the_content()?></div>
			<?php if(has_tag()){the_tags( _e('Keywords','building-blocks') . ': ', ', ');}?>
			<?php wp_link_pages() //Page buttons for multi-page posts?>
		</div>
		<?php comments_template()?>
		<?php endwhile;endif;?>
	</div>
	<?php get_sidebar()?>
<?php get_footer()?>