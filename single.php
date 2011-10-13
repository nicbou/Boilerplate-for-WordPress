<?php get_header()?>
	<div id="content">
		<div <?php post_class()?>>
			<h1 class="title"><?php the_title()?></h1>
			<div class="content"><?php the_content()?></div>
			<?php the_tags( '<p id="tags">' . _e('Tags:','boilerplate-barebones') . ' ', ', ', '</p>');?>
			<?php wp_link_pages() //Page buttons for multi-page posts?>
		</div>
		<?php comments_template()?>
	</div>
	<?php get_sidebar()?>
<?php get_footer()?>