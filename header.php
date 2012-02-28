<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html <?php language_attributes('xhtml'); ?> xmlns="http://www.w3.org/1999/xhtml/">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="content-type" content="<?php bloginfo('html_type')?>;charset=<?php bloginfo('charset'); ?>"/>
	
	<title><?php make_title()//Uses wp_title by default?></title>
	<meta name="description" content="<?php make_description()?>"/>
	<meta name="keywords" content="<?php if (is_single() && get_tags()) { foreach(get_tags() as $tag){ echo $tag->name . ', '; }}?>"/>
	
	<meta name="language" content="<?php bloginfo('language')?>"/>
	<meta name="robots" content="<?php if(!is_404()){echo "index,follow";}else{echo "noindex,follow";}?>"/>
	<link rel="profile" href="http://microformats.org/profile/hcard"/>
	
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri() ?>" type="text/css" media="screen"/>
	<link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicon.ico"/>

	<?php wp_head()?>
</head>
<body <?php body_class()?>>
	<div id="wrapper">
		<div id="header">
			<div id="banner">
				<a href="<?php echo site_url()?>" title="<?php bloginfo('name')?>"><?php bloginfo('name')?></a>	
			</div>
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
		<div id="main">	