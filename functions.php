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
		
	//Use the "x days ago" or "x minutes ago" date format
		define('WP_USE_TIME_AGO_DATES',false);
			
	//Hide "Comments are disabled" when comments are disabled. Implementation is in comments.php
		define('WP_HIDE_COMMENTS_DISABLED_MESSAGE',false);	
		
	//Disable admin menus for non-admin users
		define('WP_HIDE_MENU_POSTS',false);
		define('WP_HIDE_MENU_PAGES',false);
		define('WP_HIDE_MENU_COMMENTS',false);
		define('WP_HIDE_MENU_MEDIA',false);
		define('WP_HIDE_MENU_LINKS',false);
		define('WP_HIDE_MENU_APPEARANCE',false);
		define('WP_HIDE_MENU_PLUGINS',false);
		define('WP_HIDE_MENU_USERS',false);
		define('WP_HIDE_MENU_TOOLS',false);
		define('WP_HIDE_MENU_SETTINGS',false);
		
	//Disable the admin bar
		define('WP_HIDE_ADMIN_BAR',false);
		
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
		
	//Use the "x days ago" date format
		if( WP_USE_TIME_AGO_DATES ){
			function time_ago_date($date){
				return sprintf( _x("Posted %s ago",'The %s parameter is a date like "5 days" or "3 minutes"','boilerplate-barebones'), human_time_diff(get_the_time('U'), current_time('timestamp')) );
			}
			add_filter('the_date','time_ago_date');
		}

	//Remove inline CSS placed by WordPress
		function my_remove_recent_comments_style() {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		add_action( 'widgets_init', 'my_remove_recent_comments_style' );
		
//ADMIN

	//Load the site's CSS in the editor
		add_editor_style('style.css');
		
	//Hide specific admin menus from non-admin users (if activated)
		if(!current_user_can('administrator')){ //Only hide menus for non-admins
			function remove_menus () {
				global $menu; //The WordPress admin menu. Contains a multi-dimensional array
				$menus_to_hide = array(); //The array of menus to hide, really.
				
				if(WP_HIDE_MENU_POSTS) 		array_push($menus_to_hide,__('Posts'));
				if(WP_HIDE_MENU_PAGES) 		array_push($menus_to_hide,__('Pages'));
				if(WP_HIDE_MENU_COMMENTS) 	array_push($menus_to_hide,__('Comments'));
				if(WP_HIDE_MENU_MEDIA) 		array_push($menus_to_hide,__('Media'));
				if(WP_HIDE_MENU_LINKS) 		array_push($menus_to_hide,__('Links'));
				if(WP_HIDE_MENU_APPEARANCE) array_push($menus_to_hide,__('Appearance'));
				if(WP_HIDE_MENU_PLUGINS) 	array_push($menus_to_hide,__('Plugins'));
				if(WP_HIDE_MENU_USERS) 		array_push($menus_to_hide,__('Users'));
				if(WP_HIDE_MENU_TOOLS) 		array_push($menus_to_hide,__('Tools'));
				if(WP_HIDE_MENU_SETTINGS) 	array_push($menus_to_hide,__('Settings'));
				
				end ($menu);
				while (prev($menu)){
					$value = explode(' ',$menu[key($menu)][0]);
					if(in_array($value[0] != NULL?$value[0]:"" , $menus_to_hide)){unset($menu[key($menu)]);}
				}
			}
			add_action('admin_menu', 'remove_menus');
		}
		
	//Disable the admin bar for logged in users
		if(WP_HIDE_ADMIN_BAR){
			wp_deregister_script('admin-bar');
			wp_deregister_style('admin-bar');
			remove_action('wp_footer','wp_admin_bar_render',1000);
		}
	
//LOCALIZATION
	
	//Enable localization
		load_theme_textdomain('boilerplate-barebones',get_template_directory() . '/languages');
?>