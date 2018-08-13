<?php
/**
 * Plugin Name: bbPress Login Register Links On Forum Topic Pages
 * Plugin URI:  http://tomas.zhu.bz/bbpress-plugin-bbpress-login-register-links-on-forum-page-and-topic.html/
 * Description: Add bbpress login link, register links, on forum pages or topic pages so users can use our forums more easier. Any feature request is welcome at our <a href='http://tomas.zhu.bz/forums/forum/bbpress-notification-plugin-support/'>Support Forum</a>, like our plugin? <a href='https://wordpress.org/support/view/plugin-reviews/bbpress-login-register-links-on-forum-topic-pages/'>Submit a review</a>
 * Author:      Tomas Zhu
 * Author URI:  http://tomas.zhu.bz
 * Version:     2.2.5
 * Text Domain: tomas-bbpress-custom
 * Domain Path: /languages
 * License: GPLv3 or later
   */
if (!defined('ABSPATH'))
{
	exit;
}

add_action('plugins_loaded','tomas_load_bbpress_login_url_textdomain');

function tomas_load_bbpress_login_url_textdomain()
{
	load_plugin_textdomain('tomas-bbpress-custom', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

function bbpressLoginRegisterLinksOnForumPage()
{

	echo '<div class="bbpressloginlinks">';
	$tomas_bbpress_custom_links_login = get_option('tomas_bbpress_custom_links_login');;
	
	$tomas_trim_bbpress_custom_links_login = trim($tomas_bbpress_custom_links_login);
	if (!(empty($tomas_trim_bbpress_custom_links_login)))
	{
	if ( !is_user_logged_in() )
	{
		$login_url = get_option('siteurl').'/'.$tomas_bbpress_custom_links_login;
		echo "<a href='$login_url' class='bbpressloginurl'>".__('Log In','tomas-bbpress-custom').'</a> ';
	
		$register_url = get_option('siteurl').'/'.$tomas_bbpress_custom_links_login.'?action=register';
		echo " <a href='$register_url' class='bbpressregisterurl'>".__('Register','tomas-bbpress-custom') .'</a> ';
	
		$lost_password_url = get_option('siteurl').'/'.$tomas_bbpress_custom_links_login.'?action=lostpassword';
		echo " <a href='$lost_password_url' class='bbpresslostpasswordurl'>". __('Lost Password','tomas-bbpress-custom').'</a> ';
	}
	else
	{
		$logout_url = wp_logout_url( get_permalink() );
		echo "<a href='$logout_url' class='bbpresslogouturl'>".__('Log Out','tomas-bbpress-custom') .'</a> ';
	}
	}
	else
	{
		if ( !is_user_logged_in() )
		{
			$login_url = site_url( 'wp-login.php' );
			echo "<a href='$login_url' class='bbpressloginurl'>".__('Log In','tomas-bbpress-custom').'</a> ';
		
			$register_url = site_url( 'wp-login.php?action=register' );
			echo " <a href='$register_url' class='bbpressregisterurl'>".__('Register','tomas-bbpress-custom') .'</a> ';
		
			$lost_password_url = site_url( 'wp-login.php?action=lostpassword' );
			echo " <a href='$lost_password_url' class='bbpresslostpasswordurl'>". __('Lost Password','tomas-bbpress-custom').'</a> ';
		}
		else
		{
			$logout_url = wp_logout_url( get_permalink() );
			echo "<a href='$logout_url' class='bbpresslogouturl'>".__('Log Out','tomas-bbpress-custom') .'</a> ';
		}		
	}
	echo '</div>'; // class of "bbpressloginlinks"
	
}

function bbpressProMenuPanel()
{
	add_menu_page(__('bbPress Custom', 'tomas-bbpress-custom'), __('bbPress Custom', 'tomas-bbpress-custom'), 10, 'bbPressCustom', 'bbPressCustomMenu');
	add_submenu_page('bbPressCustom', __('bbPress Custom','tomas-bbpress-custom'), __('bbPress Custom','tomas-bbpress-custom'), 10, 'bbPressCustom', 'bbPressCustomMenu');
	add_submenu_page('bbPressCustom', __('Login Admin Bar','tomas-bbpress-custom'), __('Login Admin Bar','tomas-bbpress-custom'), 10, 'bbploginbarsettings', 'tomas_bbPressLoginAdminBar');
	add_submenu_page('bbPressCustom', __('Custom Login Links','tomas-bbpress-custom'), __('Custom Login Links','tomas-bbpress-custom'), 10, 'bbplogincustomloginlinks', 'tomas_bbPressCustomLoginLinks');
}

function bbPressCustomMenu()
{
	global $wpdb;

	if (isset($_POST['bpoptionsettinspanelsubmit']))
	{
		$bbpressCustomCSS = get_option('bbpresscustomcss');		
		if (isset($_POST['bbpresscustomcss']))
		{
			$bbpressCustomCSS = $wpdb->escape($_POST['bbpresscustomcss']);
			update_option('bbpresscustomcss',$bbpressCustomCSS);
		}
		else
		{
			delete_option('bbpresscustomcss');
		}

		$tomas_bbPressMessageString =  __( 'Your changes has been saved.', 'tomas-bbpress-custom' );
		tomas_bbPressCustomMessage($tomas_bbPressMessageString);
	}
	echo "<br />";
	?>

<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/bbpress-login-register-links-on-forum-topic-pages/images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'>bbPress Custom Settings:</div>
</div>
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body"  style="width:60%;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:98%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px; !important'>
									<span>
									<?php 
											echo  __( 'bbPress Style Settings Panel :', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
										<form id="bpmoform" name="bpmoform" action="" method="POST">
										<table id="bpmotable" width="100%">
										
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<?php 
										$bbpressCustomCSS = get_option('bbpresscustomcss');

										if (empty($bbpressCustomCSS))
										{
											$bbpressCustomCSS = 
'.bbpressloginlinks{float:right;padding-right:20px;}
.bbpressregisterurl{margin-left:20px;}
.bbpresslostpasswordurl{margin-left:20px;}';
										}
										
										if (!(empty($bbpressCustomCSS)))
										{
											
										}
										else
										{
											$bbpressCustomCSS = '';
										}
										?>
<textarea id="bbpress-custom-css-box" rows="30" name="bbpresscustomcss" style="width:95%;">
<?php echo $bbpressCustomCSS;?>
</textarea>
										<p><font color="Gray"><i>
										<?php 
											echo  __( 'Please enter your css codes in here', 'tomas-bbpress-custom' );
										?>
										</i></p>
										
										<p><font color="Gray"><i>
										<?php 
											echo  __( 'Need more guide? Check ', 'tomas-bbpress-custom' ). '<a href="https://tomas.zhu.bz/forums/forum/bbpress-login-register-links-on-forum-page-and-topic-plugin-support/" target="_blank">' .__( 'support form for examples', 'tomas-bbpress-custom' ) . '</a>' ;
										?>
										</i></p>										

										</td>
										</tr>
										</table>
										<br />
										<input type="submit" id="bpoptionsettinspanelsubmit" name="bpoptionsettinspanelsubmit" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<?php 
					echo tomas_bbpress_admin_sidebar_about();
					?>

					<div style='clear:both'></div>
		    	</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<br />

		
		
		<?php
}

function tomas_bbpress_admin_sidebar_about($place = '')
{
?>

					<div id="post-body"  style="width:40%; float:right;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">

								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'bbPress Login Pro Features', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
									<div class="inside">
									<ul>
										<li>
										* <a class="" target="_blank" href="https://www.bbp.design/features/">Login and Logout Auto Dedirect Based on User Roles</a>
										<br /><i>For example, redirect to referer URL, or redirect to a certain URL which you enter in setitng panel for any different user roles.</i> 
										</li>
										<li>
											* <a class=""  target="_blank" href="https://www.bbp.design/features/">Brute Force Protection</a>
											<br /><i>Options to enable Google reCAPTCHA in bbPress Login Page, Registration Page, New Topic Form, New Reply Form</i> 
										</li>
										<li>								
											* <a class=""  target="_blank" href="https://www.bbp.design/features/">Anti Proxy Spammer Open Login / Register Page</a>
											<br /><i>In the current time, we have stopped 23 types of proxy spammer</i> 
										</li>
										<li>								
											* <a class=""  target="_blank" href="https://www.bbp.design/features/">Customize Logo of Login / Register Page</a>
											<br /><i>Customize login Logo image, logo title, logo URL...</i> 
										</li>
										<li>								
											* <a class=""  target="_blank" href="https://www.bbp.design/features/">Pretty Background images on Login / Register Page</a>
											<br /><i>12 preset pretty background image </i> 
										</li>										
										<li>								
											* <a class=""  target="_blank" href="https://www.bbp.design/shop/">Only $9, Lifetime Upgrades, Unlimited Download, Ticket Support</a>
										</li>
									</ul>
									</div>									
									
									</div>
								</div>
								
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'bbPress Members Only Pro Features', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
									<div class="inside">
									<ul>
										<li>
										# <a class="" target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts Your bbPress and WordPress to logged in/registered members only</a>
										</li>
										<li>
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts your bbPress forums to Logged in/Registered members only, you can choose which sub forum will open to guest user, or which sub forum will only opened to logged in users</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts your bbPress topics to Logged in/Registered members only.</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts your bbPress forums based on user roles.</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts your bbPress topics based on user roles.</a>
										</li>										
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Restricts your bbPress replies based on user roles.</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Options to enable / disable restriction of your bbPress Topics, bbPress Replies, Wordpress Pages / Posts.</a>
										</li>										
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">In bbPress Topic editor, bbPress reply editor, post / page editor,  you can choose setting it as a members only page based on user roles.</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Supported https and websocket</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Options to enable "Only Protect My bbPress Pages", if you enable this option, just one click, all other sections on Your site will be opened to guest automatically.and "opened Page URLs" setting in Opened Pages Panel will be ignored, also option "Enable Page Level Protect" option in Optional Settings Panel will be ignored</a>
										</li>
										<li>								
											# <a class=""  target="_blank" href="https://www.bbp.design/features-of-bbpress-members-only-pro-plugin/">Login and Logout auto redirect based on user roles</a>
										</li>
									</ul>
									</div>									
									</div>
								</div>
																
								
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'bbPress Wordpress Tips Feed:', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
						<?php 
							wp_widget_rss_output('https://tomas.zhu.bz/feed/', array(
							'items' => 3, 
							'show_summary' => 0, 
							'show_author' => 0, 
							'show_date' => 1)
							);
						?>
										<br />
									</div>
								</div>
							</div>
						</div>
											
					</div>
<?php
}
function tomas_bbpress_custom_css(){
	$bbpressCustomCSS = get_option('bbpresscustomcss');
	
	if (empty($bbpressCustomCSS))
	{
		$bbpressCustomCSS =
'.bbpressloginlinks{float:right;padding-right:20px;}
.bbpressregisterurl{margin-left:20px;}
.bbpresslostpasswordurl{margin-left:20px;}';
	}
	
	?>
        <style type="text/css">
			<?php echo $bbpressCustomCSS;?>
		</style>
        <?php
	}

function tomas_bbPressCustomMessage($p_message)
{
	
		echo "<div id='message' class='updated fade' style='padding: 10px;'>";
	
		echo $p_message;
	
		echo "</div>";
	
}

function tomas_bbPressLoginAdminBar()
{

	if ((isset($_POST['tomas_bbpress_submit_admin_bar'])) && (!(empty($_POST['tomas_bbpress_submit_admin_bar']))))
	{
		if ((isset($_POST['tomas_bbpress_login_admin_bar'])) && (!(empty($_POST['tomas_bbpress_login_admin_bar']))))
		{
			$tomas_bbpress_login_admin_bar = $_POST['tomas_bbpress_login_admin_bar'];
			update_option('bbpress_login_admin_bar',$tomas_bbpress_login_admin_bar);
			$tomas_bbpress_MessageString =  __( 'Your changes of "Login Admin Bar" has been saved.', 'tomas-bbpress-custom' );
			tomas_bbPressCustomMessage($tomas_bbpress_MessageString);
		}
	}

	$bbpress_login_admin_bar = get_option('bbpress_login_admin_bar');
	?>
 
<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/bbpress-login-register-links-on-forum-topic-pages/images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'>bbPress Login Top Admin Bar Settings:</div>
</div>
<div style='clear:both'></div>

		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body" style="width:60%;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:98%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px; !important'>
									<span>
									<?php 
											echo  __( 'Disable Top Admin Bar for Non-Admin Logged-in Users : ', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
										<form id="tomas_bbpress_form" name="tomas_bbpress_form" action="" method="POST">
										<table id="tomas_bbpress_table" width="100%">
										<tr valign="top">
										<th scope="row"  width="30%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'Disable Top Admin Bar: ', 'tomas-bbpress-custom' );
										?>
										</th>
										
										<td width="70%" style="padding: 20px;">
										<select name = "tomas_bbpress_login_admin_bar" id = "tomas_bbpress_login_admin_bar">
										<?php 
											if ($bbpress_login_admin_bar == 'Yes')
											{
												?>
												<option selected = "selected" value="Yes">Yes</option>
												<?php 
											}
											else 
											{
												
										?>
												<option value="Yes">Yes</option>
										<?php 
											}
											if ($bbpress_login_admin_bar == 'No')
											{
												?>
												<option selected = "selected" value="No">No</option>
												<?php 
											}
											else 
											{											
										?>
												<option value="No">No</option>
										<?php 
											}
										?>
										</select>

										</td>
										</tr>
										
										</table>
										<br />
										<input type="submit" id="tomas_bbpress_submit_admin_bar" name="tomas_bbpress_submit_admin_bar" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
					tomas_bbpress_admin_sidebar_about();
					?>
		    	</div>
			</div> <!--   dashboard-widgets-wrap -->
		</div> <!--  wrap -->
		
		<div style="clear:both"></div>
		<br />		
<?php 	
}


function tomas_bbPressCustomLoginLinks()
{

	
	if ((isset($_POST['tomas_bbpress_custom_links_form_submit'])) && (!(empty($_POST['tomas_bbpress_custom_links_form_submit']))))
	{
		if ((isset($_POST['tomas_bbpress_custom_links_login'])) && (!(empty($_POST['tomas_bbpress_custom_links_login']))))
		{
			$tomas_bbpress_custom_links_login = $_POST['tomas_bbpress_custom_links_login'];
			//if (!(empty(trim($tomas_bbpress_custom_links_login))))
			$tomas_trim_bbpress_custom_links_login = trim($tomas_bbpress_custom_links_login);
			if (!(empty($tomas_trim_bbpress_custom_links_login)))
			{
				update_option('tomas_bbpress_custom_links_login',$tomas_bbpress_custom_links_login);
				add_rewrite_rule( $tomas_bbpress_custom_links_login.'/?$', 'wp-login.php', 'top' );
				flush_rewrite_rules();
			}
			$tomas_bbpress_MessageString =  __( 'Your changes of "Custom Login Links" has been saved.', 'tomas-bbpress-custom' );
			tomas_bbPressCustomMessage($tomas_bbpress_MessageString);
		}
		else
		{
			delete_option('tomas_bbpress_custom_links_login');
			flush_rewrite_rules();
		}
	}

	$tomas_bbpress_custom_links_login = get_option('tomas_bbpress_custom_links_login');
	?>
 
<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/bbpress-login-register-links-on-forum-topic-pages/images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'>bbPress Custom Login Links Settings:</div>
</div>
<div style='clear:both'></div>

		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body" style="width:60%;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:98%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px; !important'>
									<span>
									<?php 
											echo  __( 'bbPress Custom Login Links Settings : ', 'tomas-bbpress-custom' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
										<form id="tomas_bbpress_custom_links_form" name="tomas_bbpress_custom_links_form" action="" method="POST">
										<table id="tomas_bbpress_custom_links_form_table" width="100%">
										<tr>
										<td width="30%" style="padding: 20px;">
										<?php 
											echo  __( 'Login URL:', 'tomas-bbpress-custom' );
										?>
										</td>
										<td width="70%" style="padding: 20px;text-align:left;">
										<span><font color='gray'><?php echo get_option('siteurl').'/'; ?></font></span> <input type="text" id="tomas_bbpress_custom_links_login" name="tomas_bbpress_custom_links_login" size='10' value="<?php  echo $tomas_bbpress_custom_links_login; ?>"> <font color='gray'>/</font>
										</td>
										</tr>
										
					
										
										
										</table>
										<br />

										<input type="submit" id="tomas_bbpress_custom_links_form_submit" name="tomas_bbpress_custom_links_form_submit" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
					tomas_bbpress_admin_sidebar_about();
					?>
		    	</div>
			</div> <!--   dashboard-widgets-wrap -->
		</div> <!--  wrap -->
		
		<div style="clear:both"></div>
		<br />		
<?php 	
}


add_filter('plugin_action_links', 'tomas_bbPress_login_settings_link', 10, 2);
function tomas_bbPress_login_settings_link($links, $file) 
{
	$tomas_bbPress_login_file = plugin_basename(__FILE__);

	if ($file == $tomas_bbPress_login_file) 
	{
		$settings_link = '<i><a href="https://bbp.design/">Features of Pro Version</a></i>';
		array_unshift($links, $settings_link);
		$settings_link = '<a href="' . admin_url( 'admin.php?page=bbPressCustom' ) . '">' .__( 'Settings', 'tomas-bbpress-custom' ) . '</a>';
		array_unshift( $links, $settings_link );		
	}
	return $links;
}

add_action( 'init', 'tomas_bbPress_custom_Links_rewrite' );
function tomas_bbPress_custom_Links_rewrite() 
{
	$bbpress_login_admin_bar = get_option('bbpress_login_admin_bar');
	$bbpress_trim_login_admin_bar = trim($bbpress_login_admin_bar);
	if (!(empty($bbpress_trim_login_admin_bar)))
	{
		add_rewrite_rule( $bbpress_login_admin_bar.'/?$', 'wp-login.php', 'top' );
	}
}


add_action('wp_head','tomas_bbpress_custom_css');
add_action( 'admin_menu',  'bbpressProMenuPanel');
add_action('bbp_template_after_forums_loop','bbpressLoginRegisterLinksOnForumPage'); 
add_action('bbp_template_before_pagination_loop','bbpressLoginRegisterLinksOnForumPage'); 
add_action('bbp_template_after_single_forum','bbpressLoginRegisterLinksOnForumPage'); 
add_action('bbp_template_before_forums_loop','bbpressLoginRegisterLinksOnForumPage');
