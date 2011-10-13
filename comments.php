<?php
// Do not delete these lines, Wordpress requires them
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Eum.');

	if ( post_password_required() ) { ?>
		<p><?php _e("This post is password-protected.",'boilerplate-barebones')?></p>
	<?php
		return;
	}
?>


<div id="comments">
	<?php if ( have_comments() ) : ?>
		<ol class="commentlist">
			<?php wp_list_comments()?>
		</ol>
	 <?php else : // this is displayed if there are no comments so far ?>
		<?php if (!comments_open() ) : ?>
			<p><?php _e("Comments are disabled for this post.","boilerplate-barebones")?></p>
		<?php endif; ?>
	<?php endif?>
	<?php paginate_comments_links() ?>
	
	<?php comment_form(); ?>
</div>