<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="container">
	<?php the_post(); ?>
	<div <?php post_class()?>>
		<h1 class="title"><?php the_title()?></h1>
		<p><?php the_date()?></p>
		<div class="content">
			<?php the_content()?>
			
			<?php get_search_form(); ?>
			
			<h2>Archives by Month</h2>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
			
			<h2>Archives by Subject</h2>
			<ul>
				 <?php wp_list_categories(); ?>
			</ul>
		</div>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>