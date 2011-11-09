<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html <?php language_attributes('xhtml'); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="content-type" content="<?php bloginfo('html_type')?>;charset=<?php bloginfo('charset'); ?>"/>
	
	<title><?php make_title()?></title>
	<meta name="description" content="<?php make_description()?>"/>
	<meta name="keywords" content="<?php if (is_single() && get_tags()) { foreach(get_tags() as $tag){ echo $tag->name . ', '; }}?>"/>
	
	<meta name="language" content="<?php bloginfo('language')?>"/>
	<meta name="robots" content="<?php if(!is_404()){echo "index,follow";}else{echo "noindex,follow";}?>"/>
	<link rel="profile" href="http://microformats.org/profile/hcard"/>
	
	<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/style.<?php echo(get_option('boilerplate_compress_css',false)?'php':'css')?>" type="text/css" media="screen"/>
	<link rel="icon" type="image/x-icon" href="<?php bloginfo( 'template_directory' ); ?>/images/favicon.ico"/>
	
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head()?>
</head>
<body <?php body_class()?>>
	<div id="wrapper">
		<div id="header">
			<div id="banner">
				<a href="<?php bloginfo('url')?>" title="<?php bloginfo('name')?>"><?php bloginfo('name')?></a>	
			</div>
			<div id="nav">
				<?php $args = array(
					'theme_location'  => 'main-menu',
					'container'       => 'ul',
					'menu_class'      => 'menu', 
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'show_home'		=> true,
					'depth'           => 0 );
					wp_nav_menu( $args );
				?>
			</div>
		</div>
		<div id="main">	