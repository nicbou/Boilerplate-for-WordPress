<?php

//== DEVELOPMENT OPTIONS ==========================================================================

	//Set the theme's slug/textdomain (used in __() and _e() functions)
		define('WP_THEME_SLUG','boilerplate-barebones');
		

//=================================================================================================

//Required by WordPress
	add_theme_support( 'automatic-feed-links' );
	
	//MENU
		//Register the main menu
			if ( function_exists( 'register_nav_menu' ) ) {
				register_nav_menu( 'main-menu', __('Main menu',WP_THEME_SLUG) );
			}
	
	//SIDEBAR
		//Register the main sidebar
			$args = array(
				'name'          => 'Main sidebar',
				'description'   => __('The main sidebar used across most pages',WP_THEME_SLUG),
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
			
		//Return the title_suffix
			function make_title($separator = "|"){
				$available_suffixes = get_option('boilerplate_dynamic_suffixes',array(get_bloginfo('description')));
				$max_title_length = 65; //The maximum length of the title.
				
				usort($available_suffixes,'sort_suffixes');
				
				//Set the default suffix (the blog's name alone)
					$suffix = ' ' . get_bloginfo('name');
					
				//If it's not the frontpage and this feature is enabled, find an appropriate suffix in the array
					if(!is_front_page() && get_option('boilerplate_use_dynamic_suffix',false)){
						foreach( $available_suffixes as $available_suffix ){
							//If the length of this suffix + title is short enough, make it the final suffix
							if ( strlen(utf8_decode((wp_title($separator,false,'right') . get_bloginfo('name') . ' | ' . $available_suffix))) <= $max_title_length ){
								$suffix = $available_suffix;
								break;//Keep the longest title only
							}
						}
					}
					
				echo( wp_title($separator,false,'right') . get_bloginfo('name') . ' | ' . $suffix );
			}

	//Output a custom description for the meta description tag
	//If the user fills in the description post meta, then that will be used as the blog description.
	//Otherwise, if there is an excerpt for the post, that will be used. Finally, if none of these are
	//set, then the bloginfo description will be used. If disabled, the default behavior will be used.		
		function make_description(){
		//Fill the description tags with a custom description, an excerpt or the blog description
			$description = get_bloginfo('description');//Default value
			if ( get_option('boilerplate_use_dynamic_descriptions',false) ){
				if( isset($post) && get_post_meta($post->ID,'description',true) != ''){
					$description = get_post_meta($post->ID,'description',true);
				}
				elseif(is_single() && get_the_excerpt()!==''){
					$description = get_the_excerpt();
				}
			}			
			echo $description;
		}
		
	//Use the "x days ago" date format
		if( get_option('boilerplate_use_human_readable_dates',false) ){
			function time_ago_date($date){
				return sprintf( _x("Posted %s ago",'The %s parameter is a date like "5 days" or "3 minutes"',WP_THEME_SLUG), human_time_diff(get_the_time('U'), current_time('timestamp')) );
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
				
				if(get_option('boilerplate_hide_posts_menu',false)) 		array_push($menus_to_hide,__('Posts'));
				if(get_option('boilerplate_hide_pages_menu',false)) 		array_push($menus_to_hide,__('Pages'));
				if(get_option('boilerplate_hide_comments_menu',false)) 		array_push($menus_to_hide,__('Comments'));
				if(get_option('boilerplate_hide_media_menu',false)) 		array_push($menus_to_hide,__('Media'));
				if(get_option('boilerplate_hide_links_menu',false)) 		array_push($menus_to_hide,__('Links'));
				if(get_option('boilerplate_hide_profile_menu',false)) 		array_push($menus_to_hide,__('Profile'));
				if(get_option('boilerplate_hide_tools_menu',false)) 		array_push($menus_to_hide,__('Tools'));
				
				end ($menu);
				while (prev($menu)){
					$value = explode(' ',$menu[key($menu)][0]);
					if(in_array($value[0] != NULL?$value[0]:"" , $menus_to_hide)){unset($menu[key($menu)]);}
				}
			}
			add_action('admin_menu', 'remove_menus');
		}
		
	//Disable the admin bar for logged in users
		if(get_option('boilerplate_hide_admin_bar',false)==true){
			add_filter( 'show_admin_bar', '__return_false' ); 
		}
		
	//Add the plugin options page
		add_action('admin_menu', 'boilerplate_barebones_menu');
		function boilerplate_barebones_menu() {
			add_theme_page('Theme options', 'Theme options', 'manage_options', WP_THEME_SLUG, 'boilerplate_theme_options');
		}
		function boilerplate_theme_options() {			
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			//If POST data was submitted, begin saving the options
				if ( isset($_POST['saved']) ){
					//Echo a confirmation message
						echo('<div class="updated"><p><strong>'. __('Your theme settings were saved.', WP_THEME_SLUG ) .'</strong></p></div>');
					
					//Save the theme options
						//Dynamic title suffixes
							if ( isset($_POST['boilerplate_use_dynamic_suffix'] ))
								update_option('boilerplate_use_dynamic_suffix',true);
							else
								update_option('boilerplate_use_dynamic_suffix',false);
							
						//The suffixes to use
							if ( isset($_POST['boilerplate_dynamic_suffixes'] ))
								update_option('boilerplate_dynamic_suffixes',explode("\n", $_POST['boilerplate_dynamic_suffixes'])); //Split the suffixes by line
						
						//Dynamic descriptions
							if ( isset($_POST['boilerplate_use_dynamic_descriptions'] ))
								update_option('boilerplate_use_dynamic_descriptions',true);
							else
								update_option('boilerplate_use_dynamic_descriptions',false);
							
						//Human-readable dates
							if ( isset($_POST['boilerplate_use_human_readable_dates'] ))
								update_option('boilerplate_use_human_readable_dates',true);
							else
								update_option('boilerplate_use_human_readable_dates',false);
								
						//Hide menus
							if ( isset($_POST['boilerplate_hide_admin_bar'] ))
								update_option('boilerplate_hide_admin_bar',true);
							else
								update_option('boilerplate_hide_admin_bar',false);

							if ( isset($_POST['boilerplate_hide_posts_menu'] ))
								update_option('boilerplate_hide_posts_menu',true);
							else
								update_option('boilerplate_hide_posts_menu',false);
								
							if ( isset($_POST['boilerplate_hide_pages_menu'] ))
								update_option('boilerplate_hide_pages_menu',true);
							else
								update_option('boilerplate_hide_pages_menu',false);
								
							if ( isset($_POST['boilerplate_hide_media_menu'] ))
								update_option('boilerplate_hide_media_menu',true);
							else
								update_option('boilerplate_hide_media_menu',false);
								
							if ( isset($_POST['boilerplate_hide_links_menu'] ))
								update_option('boilerplate_hide_links_menu',true);
							else
								update_option('boilerplate_hide_links_menu',false);
								
							if ( isset($_POST['boilerplate_hide_comments_menu'] ))
								update_option('boilerplate_hide_comments_menu',true);
							else
								update_option('boilerplate_hide_comments_menu',false);
								
							if ( isset($_POST['boilerplate_hide_profile_menu'] ))
								update_option('boilerplate_hide_profile_menu',true);
							else
								update_option('boilerplate_hide_profile_menu',false);
								
							if ( isset($_POST['boilerplate_hide_tools_menu'] ))
								update_option('boilerplate_hide_tools_menu',true);
							else
								update_option('boilerplate_hide_tools_menu',false);

							if ( isset($_POST['boilerplate_hide_comments_disabled'] ))
								update_option('boilerplate_hide_comments_disabled',true);
							else
								update_option('boilerplate_hide_comments_disabled',false);								
				}

			//Display the theme options
				echo('
					<div class="wrap">
						<div id="icon-themes" class="icon32"><br/></div><h2>'. __('Theme options',WP_THEME_SLUG) .'</h2>
						<form name="form1" method="post" action="">
							<table class="form-table">
								<tbody>
									<p>
										'.__(
											'Use this page to customize this theme. If you want to use WordPress\' default behavior, leave the boxes unchecked.',
											WP_THEME_SLUG
										).'
									</p>								
									<tr valign="top">
										<td colspan="2">
											<h3>'.__('Dynamic title suffixes',WP_THEME_SLUG).'</h3>
											<p>
												'.__(
													'By default, Wordpress page titles are set to <code>Blog name » Title</code>.
													For search engines to find you, you should use a more descriptive title such
													as <code>Title | Blog name | Title suffix</code>. The suffix appended to your
													post titles should describe the purpose of your website.',
													WP_THEME_SLUG
												).'
											</p>
											<p>
												'.__(
													'However, only the first ~65 characters of your title are displayed in Google results, so it\'s possible
													that your title, blog name and suffix combined exceed that on some pages with longer titles.
													The solution is to have a list of different lenght suffixes that can be	used depending on the
													length of your page title.',
													WP_THEME_SLUG
												).'
											</p>
											<p>
												'.__(
													'When you enable this option, the longest suffix that doesn\'t make your title exceed 65 characters
													will be selected. For example, the <code>Home</code> page can have a very long suffix, while the <code>How to fly a
													plane in 30 easy steps</code> will need a shorter suffix, as to keep the title length under 65
													characters.',
													WP_THEME_SLUG
												).'
											</p>
											<p>
												'.__(
													'Here are a few suffix examples: <code>Web design and development in Granby, Québec</code>,
													<code>Web design in Granby, Québec</code>, <code>Web design services</code> and <code>Web design</code>.
													The theme will pick the best suffix for the title length.',
													WP_THEME_SLUG
												).'
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_dynamic_suffix">'.__("Use dynamic title suffixes", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_dynamic_suffix" name="boilerplate_use_dynamic_suffix"
												onclick="document.getElementById(\'txt_dynamic_suffix\').disabled = !this.checked" '. (get_option('boilerplate_use_dynamic_suffix',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row" colspan=2>
											<label for="txt_dynamic_suffix">Available title suffixes:</label>
											<textarea cols="60" rows="6"
												id="txt_dynamic_suffix" class="large-text code" name="boilerplate_dynamic_suffixes"
												'.(get_option('boilerplate_use_dynamic_suffix',false)==true?'':'disabled').'>'. implode("\n",get_option('boilerplate_dynamic_suffixes',array('cat','dog'))) .'</textarea>
										</th>
									</tr>
									
									<tr valign="top">
										<td colspan="2">
											<h3>'.__('Dynamic meta description',WP_THEME_SLUG).'</h3>
											<p>
												'.__(
													'If enabled, the meta description tag will be the post/page description, the excerpt or the blog description, in that order.',
													WP_THEME_SLUG
												).'
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_dynamic_descriptions">'.__("Use dynamic meta descriptions", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_dynamic_descriptions" name="boilerplate_use_dynamic_descriptions" '. (get_option('boilerplate_use_dynamic_descriptions',false)==true?'checked':'') .'/>
										</td>
									</tr>
									
									<tr valign="top">
										<td colspan="2">
											<h3>'.__('Human-readable dates',WP_THEME_SLUG).'</h3>
											<p>
												'.__(
													'If enabled, the date will be displayed as <code>25 days ago</code>.',
													WP_THEME_SLUG
												).'
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_human_dates">'.__("Use human-readable dates", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_human_dates" name="boilerplate_use_human_readable_dates" '. (get_option('boilerplate_use_human_readable_dates',false)==true?'checked':'') .'/>
										</td>
									</tr>
									
									<tr valign="top">
										<td colspan="2">
											<h3>'.__('User menus',WP_THEME_SLUG).'</h3>
											<p>
												'.__(
													'You can disable Admin area menus for users that don\'t have administrator privileges to ensure your clients a more streamlined experience.',
													WP_THEME_SLUG
												).'
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_top_bar">'.__("Hide the admin top bar on the site", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_top_bar" name="boilerplate_hide_admin_bar" '. (get_option('boilerplate_hide_admin_bar',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_posts_menu">'.__("Hide Posts menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_posts_menu" name="boilerplate_hide_posts_menu" '. (get_option('boilerplate_hide_posts_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_pages_menu">'.__("Hide Pages menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_pages_menu" name="boilerplate_hide_pages_menu" '. (get_option('boilerplate_hide_pages_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_comments_menu">'.__("Hide Comments menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_comments_menu" name="boilerplate_hide_comments_menu" '. (get_option('boilerplate_hide_comments_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_media_menu">'.__("Hide Media menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_media_menu" name="boilerplate_hide_media_menu" '. (get_option('boilerplate_hide_media_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_links_menu">'.__("Hide Links menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_links_menu" name="boilerplate_hide_links_menu" '. (get_option('boilerplate_hide_links_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_profile_menu">'.__("Hide Profile menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_profile_menu" name="boilerplate_hide_profile_menu" '. (get_option('boilerplate_hide_profile_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_tools_menu">'.__("Hide Tools menu", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_tools_menu" name="boilerplate_hide_tools_menu" '. (get_option('boilerplate_hide_tools_menu',false)==true?'checked':'') .'/>
										</td>
									</tr>
									
									<tr valign="top">
										<td colspan="2">
											<h3>'.__('"Comments disabled" message',WP_THEME_SLUG).'</h3>
											<p>
												'.__(
													'You can hide the "Comments are disabled for this post" message at the bottom of posts by checking this option.',
													WP_THEME_SLUG
												).'
											</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="chk_hide_comments_disabled">'.__("Hide the \"Comments disabled\" message", WP_THEME_SLUG ).'</label>
										</th>
										<td>
											<input type="checkbox" id="chk_hide_comments_disabled" name="boilerplate_hide_comments_disabled" '. (get_option('boilerplate_hide_comments_disabled',false)==true?'checked':'') .'/>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit">
								<input type="hidden" name="saved" />
								<input type="submit" name="Submit" class="button-primary" value="'.esc_attr__("Save Changes").'" />
							</p>
						</form>
					</div>
				');
		}

	
//LOCALIZATION
	
	//Enable localization
		load_theme_textdomain(WP_THEME_SLUG,get_template_directory() . '/languages');
?>