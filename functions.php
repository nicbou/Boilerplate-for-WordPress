<?php

//== THEME OPTIONS ================================================================================
	//These options are only there to make the developer's work easier. Setting any of these options
	//to false will make the theme use the WordPress default behavior.

	//Select the title suffix from an array to avoid exceeding the search engines' length limit. Implementation is in functions.php
		define('WP_USE_DYNAMIC_SUFFIX',false);
		
		//Add your suffixes here
			$available_suffixes = array( //Suffixes to use
				'Short suffix',
				'A longer suffix',
				'A slightly longer suffix',
				'A suffix for short page titles',
				'A suffix for even shorter page titles',
				'I doubt this suffix will even be used, given that it exceeds the title length displayed by search engines',
			);
		//Max title length (for the entire title)
			$max_title_length = 65; //Max length displayed by most search engines
			
	//Select the description from the "description" custom field, the excerpt or the blog's description, in that order. Implementation is in functions.php
		define('WP_USE_DYNAMIC_DESCRIPTION',false);	
			
	//Hide "Comments are disabled" when comments are disabled. Implementation is in comments.php
		define('WP_HIDE_COMMENTS_DISABLED_MESSAGE',false);	
		
//=================================================================================================

//Required by WordPress
	add_theme_support( 'automatic-feed-links' );
	
	//MENU
		//Register the main menu
			if ( function_exists( 'register_nav_menu' ) ) {
				register_nav_menu( 'main-menu', __('Main menu','boilerplate-barebones') );
			}
	
	//SIDEBAR
		//Register the main sidebar
			$args = array(
				'name'          => 'Main sidebar',
				'description'   => __('The main sidebar used across most pages'),
				'before_widget' => '<li class="sidebaritem">',
				'after_widget'  => '</li>',
				'before_title'  => '<span class="title">',
				'after_title'   => '</span>' );
			if ( function_exists('register_sidebar') ){
				register_sidebar($args);
			}
	
//FILTERS

	//Output a custom title suffix based on phrase length
	//This is a feature that improves SEO by selecting a title that is as long
	//as possible, but that doesn't exceed the character limit displayed in search
	//engines. If no short enough suffix is found, the default is used (blog title)
		
		//Sort the array of suffixes by length
			function sort_suffixes($a,$b){
				return strlen($b)-strlen($a);
			}
			usort($available_suffixes,'sort_suffixes');
			
		//Return the title_suffix
			function make_title($separator = "|"){
				global $available_suffixes, $max_title_length;
				//Set the default suffix
					$suffix = get_bloginfo('title');
					
				//If it's not the frontpage and this feature is enabled, find an appropriate suffix in the array
					if(!is_front_page() && WP_USE_DYNAMIC_SUFFIX){
						foreach( $available_suffixes as $available_suffix ){
							//If the length of this suffix + title is short enough, make it the final suffix
							if ( strlen($available_suffix . wp_title(' ',false)) < $max_title_length ){
								$suffix = $available_suffix;
								break;//Keep the longest title only
							}
						}
					}
					
				echo( wp_title($separator,false,'right') . $suffix );
			}

	//Output a custom description for the meta description tag
	//If the user fills in the description post meta, then that will be used as the blog description.
	//Otherwise, if there is an excerpt for the post, that will be used. Finally, if none of these are
	//set, then the bloginfo description will be used. If disabled, the default behavior will be used.		
		function make_description(){
		//Fill the description tags with a custom description, an excerpt or the blog description
			$description = get_bloginfo('description');//Default value
			if ( WP_USE_DYNAMIC_DESCRIPTION ){
				if( get_post_meta($post->ID,'description',true) != ''){
					$description = get_post_meta($post->ID,'description',true);
				}
				elseif(is_single() && get_the_excerpt()!==''){
					$description = get_the_excerpt();
				}
			}			
			echo $description;
		}

	//Remove inline CSS placed by WordPress
		function my_remove_recent_comments_style() {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		add_action( 'widgets_init', 'my_remove_recent_comments_style' );
		
//ADMIN

	//Load the site's CSS in the editor
		add_editor_style('style.css');

	
//LOCALIZATION
	
	//Enable localization
		load_theme_textdomain('boilerplate-barebones',get_template_directory() . '/languages');
?>