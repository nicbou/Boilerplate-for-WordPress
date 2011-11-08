<?php 
//theme-options.php
//This is the Theme Options page in the WordPress admin area. It is meant to be included by functions.php
	
//================================================================================================================

//Handle $_POST data after the form was saved

	//Kick the user out if he doesn't have the right permissions
	if (!current_user_can('manage_options'))  {
		wp_die( _e('You do not have sufficient permissions to access this page.') );
	}
	else{
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

					if ( isset($_POST['boilerplate_custom_logo_url'] ) )
						if ( is_valid_url($_POST['boilerplate_custom_logo_url']) )
							update_option('boilerplate_custom_logo_url',$_POST['boilerplate_custom_logo_url']);
						else
							echo('<div class="error"><p><strong>'. __('The logo URL is invalid. Other settings were saved.', WP_THEME_SLUG ) .'</strong></p></div>');
					else
						update_option('boilerplate_custom_logo_url','');
			}
	}
?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br/></div><h2><?php _e('Theme options',WP_THEME_SLUG)?></h2>
	<form name="form1" method="post" action="">
		<table class="form-table">
			<tbody>
				<p>
					<?php _e(
						'Use this page to customize this theme. If you want to use WordPress\' default behavior, leave the boxes unchecked.',
						WP_THEME_SLUG
					)?>
				</p>								
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('Dynamic title suffixes',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'By default, Wordpress page titles are set to <code>Blog name » Title</code>.
								For search engines to find you, you should use a more descriptive title such
								as <code>Title | Blog name | Title suffix</code>. The suffix appended to your
								post titles should describe the purpose of your website.',
								WP_THEME_SLUG
							)?>
						</p>
						<p>
							<?php _e(
								'However, only the first ~65 characters of your title are displayed in Google results, so it\'s possible
								that your title, blog name and suffix combined exceed that on some pages with longer titles.
								The solution is to have a list of different lenght suffixes that can be	used depending on the
								length of your page title.',
								WP_THEME_SLUG
							)?>
						</p>
						<p>
							<?php _e(
								'When you enable this option, the longest suffix that doesn\'t make your title exceed 65 characters
								will be selected. For example, the <code>Home</code> page can have a very long suffix, while the <code>How to fly a
								plane in 30 easy steps</code> will need a shorter suffix, as to keep the title length under 65
								characters.',
								WP_THEME_SLUG
							)?>
						</p>
						<p>
							<?php _e(
								'Here are a few suffix examples: <code>Web design and development in Granby, Québec</code>,
								<code>Web design in Granby, Québec</code>, <code>Web design services</code> and <code>Web design</code>.
								The theme will pick the best suffix for the title length.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_dynamic_suffix"><?php _e("Use dynamic title suffixes", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_dynamic_suffix" name="boilerplate_use_dynamic_suffix"
							onclick="document.getElementById(\'txt_dynamic_suffix\').disabled = !this.checked" <?php echo(get_option('boilerplate_use_dynamic_suffix',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" colspan=2>
						<label for="txt_dynamic_suffix">Available title suffixes:</label>
						<textarea cols="60" rows="6"
							id="txt_dynamic_suffix" class="large-text code" name="boilerplate_dynamic_suffixes"
							<?php(get_option('boilerplate_use_dynamic_suffix',false)==true?'':'disabled')?><?php echo(implode("\n",get_option('boilerplate_dynamic_suffixes',array(''))))?></textarea>
					</th>
				</tr>
				
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('Dynamic meta description',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'If enabled, the meta description tag will be the post/page description, the excerpt or the blog description, in that order.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_dynamic_descriptions"><?php _e("Use dynamic meta descriptions", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_dynamic_descriptions" name="boilerplate_use_dynamic_descriptions" <?php echo(get_option('boilerplate_use_dynamic_descriptions',false)==true?'checked':'')?>/>
					</td>
				</tr>
				
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('Human-readable dates',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'If enabled, the date will be displayed as <code>25 days ago</code>.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_human_dates"><?php _e("Use human-readable dates", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_human_dates" name="boilerplate_use_human_readable_dates" <?php echo(get_option('boilerplate_use_human_readable_dates',false)==true?'checked':'')?>/>
					</td>
				</tr>
				
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('User menus',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'You can disable Admin area menus for users that don\'t have administrator privileges to ensure your clients a more streamlined experience.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_top_bar"><?php _e("Hide the admin top bar on the site", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_top_bar" name="boilerplate_hide_admin_bar" <?php echo(get_option('boilerplate_hide_admin_bar',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_posts_menu"><?php _e("Hide Posts menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_posts_menu" name="boilerplate_hide_posts_menu" <?php echo(get_option('boilerplate_hide_posts_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_pages_menu"><?php _e("Hide Pages menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_pages_menu" name="boilerplate_hide_pages_menu" <?php echo(get_option('boilerplate_hide_pages_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_comments_menu"><?php _e("Hide Comments menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_comments_menu" name="boilerplate_hide_comments_menu" <?php echo(get_option('boilerplate_hide_comments_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_media_menu"><?php _e("Hide Media menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_media_menu" name="boilerplate_hide_media_menu" <?php echo(get_option('boilerplate_hide_media_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_links_menu"><?php _e("Hide Links menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_links_menu" name="boilerplate_hide_links_menu" <?php echo(get_option('boilerplate_hide_links_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_profile_menu"><?php _e("Hide Profile menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_profile_menu" name="boilerplate_hide_profile_menu" <?php echo(get_option('boilerplate_hide_profile_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_tools_menu"><?php _e("Hide Tools menu", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_tools_menu" name="boilerplate_hide_tools_menu" <?php echo(get_option('boilerplate_hide_tools_menu',false)==true?'checked':'')?>/>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('Custom login image',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'You can use your own logo for the WordPress login screen by entering a URL below. Please use transparent images that are 250-500px wide.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="txt_logo_url"><?php _e("Logo URL (leave empty to disable)", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="text" id="txt_logo_url" name="boilerplate_custom_logo_url" class="regular-text code" value="<?php echo(get_option('boilerplate_custom_logo_url',''))?>"/>
					</td>
				</tr>
				
				<tr valign="top">
					<td colspan="2">
						<h3><?php _e('"Comments disabled" message',WP_THEME_SLUG)?></h3>
						<p>
							<?php _e(
								'You can hide the "Comments are disabled for this post" message at the bottom of posts by checking this option.',
								WP_THEME_SLUG
							)?>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="chk_hide_comments_disabled"><?php _e("Hide the \"Comments disabled\" message", WP_THEME_SLUG )?></label>
					</th>
					<td>
						<input type="checkbox" id="chk_hide_comments_disabled" name="boilerplate_hide_comments_disabled" <?php echo(get_option('boilerplate_hide_comments_disabled',false)==true?'checked':'')?>/>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="saved" value="true"/>
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Changes")?>" />
		</p>
	</form>
</div>