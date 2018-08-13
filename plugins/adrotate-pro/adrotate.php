<?php
/*
Plugin Name: AdRotate Professional
Plugin URI: https://ajdg.solutions/products/adrotate-for-wordpress/?pk_campaign=adrotatepro-pluginpage
Author: Arnan de Gans of AJdG Solutions
Author URI: http://ajdg.solutions/?pk_campaign=adrotatepro-pluginpage
Description: Used on thousands of websites! AdRotate Pro is the popular choice for monetizing your website with adverts while keeping things simple.
Version: 3.17
License: Limited License (See the readme.html in your account on https://ajdg.solutions/)
*/

/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

/*--- AdRotate values ---------------------------------------*/
define("ADROTATE_DISPLAY", '3.17 Professional');
define("ADROTATE_VERSION", 380);
define("ADROTATE_DB_VERSION", 53);
/*-----------------------------------------------------------*/

/*--- Load Files --------------------------------------------*/
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-setup.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-manage-publisher.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-manage-advertiser.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-functions.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-advertiser-functions.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-statistics.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-import.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-export.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-output.php');
require_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/adrotate-widget.php');
/*-----------------------------------------------------------*/

/*--- Check and Load config ---------------------------------*/
load_plugin_textdomain('adrotate', false, basename( dirname( __FILE__ ) ) . '/language' );
$adrotate_config = get_option('adrotate_config');
$adrotate_crawlers = get_option('adrotate_crawlers');
$adrotate_version = get_option("adrotate_version");
$adrotate_db_version = get_option("adrotate_db_version");
$adrotate_debug = get_option("adrotate_debug");
$adrotate_advert_status	= get_option("adrotate_advert_status");
$ajdg_solutions_domain = 'https://ajdg.solutions/';
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'adrotate_activate');
register_deactivation_hook(__FILE__, 'adrotate_deactivate');
register_uninstall_hook(__FILE__, 'adrotate_uninstall');
add_action('adrotate_notification', 'adrotate_notifications');
add_action('adrotate_clean_trackerdata', 'adrotate_clean_trackerdata');
add_action('adrotate_evaluate_ads', 'adrotate_evaluate_ads');
add_action('widgets_init', create_function('', 'return register_widget("adrotate_widgets");'));
/*-----------------------------------------------------------*/

/*--- Front end ---------------------------------------------*/
if(!is_admin()) {
	if($adrotate_config['adminbar'] == 'Y') {
		add_action('admin_bar_menu', 'adrotate_adminmenu', 100);
	}
	if($adrotate_config['enable_geo'] > 0 AND get_option('adrotate_geo_required') > 0) {
		add_action('init', 'adrotate_geolocation');
	}
	if($adrotate_config['textwidget_shortcodes'] == 'Y') {
		add_filter('widget_text', 'do_shortcode');
	}
	add_shortcode('adrotate', 'adrotate_shortcode');
	add_shortcode('adrotate_advertiser_dashboard', 'adrotate_front_end');
	add_action('wp_enqueue_scripts', 'adrotate_custom_scripts');
	add_action('wp_head', 'adrotate_custom_css');
	add_filter('the_content', 'adrotate_inject_posts', 12);
}

// AJAX Callbacks
if($adrotate_config['stats'] == 1){
	add_action('wp_ajax_adrotate_impression', 'adrotate_impression_callback');
	add_action('wp_ajax_nopriv_adrotate_impression', 'adrotate_impression_callback');
	add_action('wp_ajax_adrotate_click', 'adrotate_click_callback');
	add_action('wp_ajax_nopriv_adrotate_click', 'adrotate_click_callback');
}
/*-----------------------------------------------------------*/

if(is_admin()) {
	/*--- Back end ----------------------------------------------*/
	adrotate_check_config();
	add_action('admin_menu', 'adrotate_dashboard');
	add_action("admin_enqueue_scripts", 'adrotate_dashboard_scripts');
	add_action("admin_print_styles", 'adrotate_dashboard_styles');
	add_action('admin_notices', 'adrotate_notifications_dashboard');
	if(adrotate_is_networked()) {
		add_action('network_admin_menu', 'adrotate_network_dashboard');
	}
	/*--- Update API --------------------------------------------*/
	include_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/library/license-api.php');
	include_once(WP_CONTENT_DIR.'/plugins/adrotate-pro/library/update-api.php');
	add_action('admin_init', 'adrotate_licensed_update');

	if(isset($_POST['adrotate_license_activate'])) add_action('init', 'adrotate_license_activate');
	if(isset($_POST['adrotate_license_deactivate'])) add_action('init', 'adrotate_license_deactivate');
	if(isset($_POST['adrotate_support_submit'])) add_action('init', 'adrotate_support_request');
	/*--- Internal redirects ------------------------------------*/
	if(isset($_POST['adrotate_ad_submit'])) add_action('init', 'adrotate_insert_input');
	if(isset($_POST['adrotate_group_submit'])) add_action('init', 'adrotate_insert_group');
	if(isset($_POST['adrotate_schedule_submit'])) add_action('init', 'adrotate_insert_schedule');
	if(isset($_POST['adrotate_media_submit'])) add_action('init', 'adrotate_insert_media');
	if(isset($_POST['adrotate_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_disabled_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_error_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_notification_test_submit'])) add_action('init', 'adrotate_notifications');
	if(isset($_POST['adrotate_options_submit'])) add_action('init', 'adrotate_options_submit');
	if(isset($_POST['adrotate_request_submit'])) add_action('init', 'adrotate_mail_message');
	if(isset($_POST['adrotate_db_optimize_submit'])) add_action('init', 'adrotate_optimize_database');
	if(isset($_POST['adrotate_db_cleanup_submit'])) add_action('init', 'adrotate_cleanup_database');
	if(isset($_POST['adrotate_evaluate_submit'])) add_action('init', 'adrotate_prepare_evaluate_ads');
	if(isset($_POST['adrotate_import'])) add_action('init', 'adrotate_import_ads');
	if(isset($_POST['adrotate_export_submit'])) add_action('init', 'adrotate_export_stats');
	/*--- Advertiser redirects ----------------------------------*/
	if(isset($_POST['adrotate_advertiser_ad_submit'])) add_action('init', 'adrotate_advertiser_insert_input');
	/*-----------------------------------------------------------*/
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard

 Purpose:   Add pages to admin menus
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_dashboard() {
	global $adrotate_config;

	$adrotate_page = $adrotate_adverts = $adrotate_groups = $adrotate_schedules = $adrotate_media = $adrotate_queue = $adrotate_settings =  '';
	$adrotate_page = add_menu_page('AdRotate Pro', 'AdRotate Pro', 'adrotate_ad_manage', 'adrotate', 'adrotate_info', plugins_url('/images/icon-menu.png', __FILE__), '25.8');
	$adrotate_page = add_submenu_page('adrotate', 'AdRotate Pro > '.__('General Info', 'adrotate'), __('General Info', 'adrotate'), 'adrotate_ad_manage', 'adrotate', 'adrotate_info');
	$adrotate_adverts = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Manage Ads', 'adrotate'), __('Manage Ads', 'adrotate'), 'adrotate_ad_manage', 'adrotate-ads', 'adrotate_manage');
	$adrotate_groups = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Manage Groups', 'adrotate'), __('Manage Groups', 'adrotate'), 'adrotate_group_manage', 'adrotate-groups', 'adrotate_manage_group');
	$adrotate_schedules = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Manage Schedules', 'adrotate'), __('Manage Schedules', 'adrotate'), 'adrotate_schedule_manage', 'adrotate-schedules', 'adrotate_manage_schedules');
	$adrotate_media = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Manage Media', 'adrotate'), __('Manage Media', 'adrotate'), 'adrotate_ad_manage', 'adrotate-media', 'adrotate_manage_media');
	if($adrotate_config['enable_advertisers'] == 'Y' AND $adrotate_config['enable_editing'] == 'Y') {
		$adrotate_queue = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Moderate', 'adrotate'), __('Moderate Adverts', 'adrotate'), 'adrotate_moderate', 'adrotate-moderate', 'adrotate_moderate');
	}
	$adrotate_settings = add_submenu_page('adrotate', 'AdRotate Pro > '.__('Settings', 'adrotate'), __('Settings', 'adrotate'), 'manage_options', 'adrotate-settings', 'adrotate_options');
	
	if($adrotate_config['enable_advertisers'] == 'Y') {
		add_menu_page(__('Advertiser', 'adrotate'), __('Advertiser', 'adrotate'), 'adrotate_advertiser', 'adrotate-advertiser', 'adrotate_advertiser', plugins_url('/images/icon-menu.png', __FILE__), '25.9');
		add_submenu_page('adrotate-advertiser', 'AdRotate Pro > '.__('Advertiser', 'adrotate'), __('Advertiser', 'adrotate'), 'adrotate_advertiser', 'adrotate-advertiser', 'adrotate_advertiser');
	}
	
	// Add help tabs
	add_action('load-'.$adrotate_page, 'adrotate_help_info');
	add_action('load-'.$adrotate_adverts, 'adrotate_help_info');
	add_action('load-'.$adrotate_groups, 'adrotate_help_info');
	add_action('load-'.$adrotate_schedules, 'adrotate_help_info');
	add_action('load-'.$adrotate_media, 'adrotate_help_info');
	add_action('load-'.$adrotate_queue, 'adrotate_help_info');
	add_action('load-'.$adrotate_settings, 'adrotate_help_info');
}

/*-------------------------------------------------------------
 Name:      adrotate_adminmenu

 Purpose:   Add things to the admin bar
 Receive:   -None-
 Return:    -None-
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_adminmenu() {
    global $wp_admin_bar, $adrotate_config;

	if(!is_super_admin() OR !is_admin_bar_showing())
		return;

    $wp_admin_bar->add_node(array( 'id' => 'adrotate', 'title' => __('AdRotate', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate')));
    $wp_admin_bar->add_node(array( 'id' => 'adrotate-ads-new','parent' => 'adrotate', 'title' => __('Add new Advert', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-ads&view=addnew')));
    $wp_admin_bar->add_node(array( 'id' => 'adrotate-ads','parent' => 'adrotate', 'title' => __('Manage Adverts', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-ads')));
    $wp_admin_bar->add_node(array( 'id' => 'adrotate-groups','parent' => 'adrotate', 'title' => __('Manage Groups', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-groups')));
    $wp_admin_bar->add_node(array( 'id' => 'adrotate-schedules','parent' => 'adrotate', 'title' => __('Manage Schedules', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-schedules')));
	if($adrotate_config['enable_advertisers'] == 'Y' AND $adrotate_config['enable_editing'] == 'Y') {
   		$wp_admin_bar->add_node(array( 'id' => 'adrotate-moderate','parent' => 'adrotate', 'title' => __('Moderate Adverts', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-moderate')));
	}
    $wp_admin_bar->add_node(array( 'id' => 'adrotate-report','parent' => 'adrotate', 'title' => __('Full Report', 'adrotate'), 'href' => admin_url('/admin.php?page=adrotate-ads&view=fullreport')));
}

/*-------------------------------------------------------------
 Name:      adrotate_network_dashboard

 Purpose:   Add pages to admin menus if AdRotate is network activated
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_network_dashboard() {
	add_menu_page('AdRotate', 'AdRotate', 'manage_network', 'adrotate', 'adrotate_network_license');
	add_submenu_page('adrotate', 'AdRotate > '.__('License', 'adrotate'), 'AdRotate '.__('License', 'adrotate'), 'manage_network', 'adrotate', 'adrotate_network_license');
}

/*-------------------------------------------------------------
 Name:      adrotate_info

 Purpose:   Admin general info page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_info() {
	global $wpdb, $current_user, $adrotate_advert_status;

	if(adrotate_is_networked()) {
		$a = get_site_option('adrotate_activate');
	} else {
		$a = get_option('adrotate_activate');
	}
	
	$status = $ticketid = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['ticket'])) $ticketid = esc_attr($_GET['ticket']);

	$user = get_userdata($current_user->ID); 
	if(strlen($user->first_name) < 1) $firstname = $user->user_login;
		else $firstname = $user->first_name;
	if(strlen($user->last_name) < 1) $lastname = ''; 
		else $lastname = ' '.$user->last_name;
	?>

	<div class="wrap">
		<h1><?php _e('AdRotate Info', 'adrotate'); ?></h1>

		<br class="clear" />

		<?php include("dashboard/info.php"); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage

 Purpose:   Admin management page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage() {
	global $wpdb, $current_user, $userdata, $blog_id, $adrotate_config, $adrotate_debug;

	$status = $file = $view = $ad_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['file'])) $file = esc_attr($_GET['file']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['ad'])) $ad_edit_id = esc_attr($_GET['ad']);
	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	$in84days 		= $now + 7257600;

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	
	?>
	<div class="wrap">
		<h1><?php _e('Ad Management', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status, array('file' => $file)); ?>

		<?php
		$allbanners = $wpdb->get_results("SELECT `id`, `title`, `type`, `tracker`, `weight`, `mobile`, `tablet`, `budget`, `crate`, `irate` FROM `{$wpdb->prefix}adrotate` WHERE `type` = 'active' OR `type` = 'error' OR `type` = 'a_error' OR `type` = 'expired' OR `type` = '2days' OR `type` = '7days' OR `type` = 'disabled' ORDER BY `sortorder` ASC, `id` ASC;");
		
		$activebanners = $errorbanners = $disabledbanners = false;
		foreach($allbanners as $singlebanner) {
			$advertiser = '';
			$starttime = $stoptime = 0;
			$starttime = $wpdb->get_var("SELECT `starttime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `starttime` ASC LIMIT 1;");
			$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND  `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");
			if($adrotate_config['enable_advertisers'] == 'Y') {
				$advertiser = $wpdb->get_var("SELECT `user_login` FROM `{$wpdb->prefix}adrotate_linkmeta`, `$wpdb->users` WHERE `$wpdb->users`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`user` AND `ad` = '".$singlebanner->id."' AND `group` = '0' AND `schedule` = '0' LIMIT 1;");
			}

			$type = $singlebanner->type;
			if($type == 'active' AND $stoptime <= $now) $type = 'expired'; 
			if($type == 'active' AND $stoptime <= $in2days) $type = '2days';
			if($type == 'active' AND $stoptime <= $in7days) $type = '7days';
			if(($singlebanner->crate > 0 OR $singlebanner->irate > 0) AND $singlebanner->budget < 1) $type = 'expired';

			if($type == 'active' OR $type == '7days') {
				$activebanners[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'advertiser' => $advertiser,
					'type' => $type,
					'mobile' => $singlebanner->mobile,
					'tablet' => $singlebanner->tablet,
					'budget' => $singlebanner->budget,
					'crate' => $singlebanner->crate,
					'irate' => $singlebanner->irate,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
			
			if($type == 'error' OR $type == 'a_error' OR $type == 'expired' OR $type == '2days') {
				$errorbanners[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'advertiser' => $advertiser,
					'type' => $type,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
			
			if($type == 'disabled') {
				$disabledbanners[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'advertiser' => $advertiser,
					'type' => $type,
					'mobile' => $singlebanner->mobile,
					'tablet' => $singlebanner->tablet,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
		}
		?>
		
		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=import');?>"><?php _e('Import', 'adrotate'); ?></a> 
				<?php if($adrotate_config['stats'] == 1) { ?>
				| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=fullreport');?>"><?php _e('Full Report', 'adrotate'); ?></a>
				<?php } ?>
			</div>
		</div>

    	<?php 
    	if ($view == "" OR $view == "manage") {
			// Show list of errorous ads if any			
			if ($errorbanners) {
				include("dashboard/publisher/adverts-error.php");
			}
	
			include("dashboard/publisher/adverts-main.php");

			// Show disabled ads, if any
			if ($disabledbanners) {
				include("dashboard/publisher/adverts-disabled.php");
			}
	   	} else if($view == "addnew" OR $view == "edit") { 
			include("dashboard/publisher/adverts-edit.php");
		} else if($view == "report") {
			include("dashboard/publisher/adverts-report.php");
		} else if($view == "import") {			
			include("dashboard/publisher/adverts-import.php");
		} else if($view == "fullreport") {
			$adrotate_stats = adrotate_prepare_fullreport();
			
			if($adrotate_stats['tracker'] > 0 AND $adrotate_stats['clicks'] > 0) {
				$clicks = round($adrotate_stats['clicks'] / $adrotate_stats['tracker'], 2);
			} else { 
				$clicks = 0; 
			}

			$ctr = adrotate_ctr($adrotate_stats['clicks'], $adrotate_stats['impressions']);						

			include("dashboard/publisher/fullreport.php");
		}
		?>
		<br class="clear" />

		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_group

 Purpose:   Manage groups
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage_group() {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$status = $view = $group_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['group'])) $group_edit_id = esc_attr($_GET['group']);

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	

	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	?>
	<div class="wrap">
		<h1><?php _e('Group Management', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>

		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
				<?php if($group_edit_id AND $adrotate_config['stats'] == 1) { ?>
				| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=report&group='.$group_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
				<?php } ?>
			</div>
		</div>

    	<?php
	    if ($view == "" OR $view == "manage") {
			include("dashboard/publisher/groups-main.php");
	   	} else if($view == "addnew" OR $view == "edit") {
			include("dashboard/publisher/groups-edit.php");
	   	} else if($view == "report") {
			include("dashboard/publisher/groups-report.php");
	   	}
	   	?>
		<br class="clear" />

		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_schedules

 Purpose:   Manage schedules for ads
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage_schedules() {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$status = $view = $schedule_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['schedule'])) $schedule_edit_id = esc_attr($_GET['schedule']);

	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	$in84days 		= $now + 7257600;
	?>
	<div class="wrap">
		<h1><?php _e('Schedule Management', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>

		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-schedules&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-schedules&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
			</div>
		</div>

    	<?php 
	    if ($view == "" OR $view == "manage") {
			include("dashboard/publisher/schedules-main.php");
		} else if($view == "addnew" OR $view == "edit") {
			include("dashboard/publisher/schedules-edit.php");
		}
		?>

		<br class="clear" />

		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_images

 Purpose:   Manage banner images for ads
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_manage_media() {
	global $wpdb, $adrotate_config;

	$status = $file = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['file'])) $file = esc_attr($_GET['file']);

	if(strlen($file) > 0 AND wp_verify_nonce($_REQUEST['_wpnonce'], 'adrotate_delete_media_'.$file)) {
		if(adrotate_unlink($file)) {
			$status = 206;
		} else {
			$status = 207;
		}
	}
	?>

	<div class="wrap">
		<h1><?php _e('Media Management', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>

		<p><?php _e('Upload images to the AdRotate Pro banners folder from here. This is useful if you use responsive adverts with multiple images or have HTML5 adverts containing multiple files.', 'adrotate'); ?></p>

		<?php
		include("dashboard/publisher/media.php");
		?>

		<br class="clear" />

		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_moderate

 Purpose:   Moderation queue
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_moderate() {
	global $wpdb, $current_user, $userdata, $adrotate_config, $adrotate_debug;

	$status = $view = $ad_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['ad'])) $ad_edit_id = esc_attr($_GET['ad']);
	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	$in84days 		= $now + 7257600;
	?>
	<div class="wrap">
		<h1><?php _e('Moderation queue', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>

		<?php
		$allbanners = $wpdb->get_results("SELECT `id`, `title`, `type`, `tracker`, `mobile`, `tablet`, `crate`, `budget`, `irate`, `weight` FROM `{$wpdb->prefix}adrotate` WHERE `type` = 'queue' OR `type` = 'reject' ORDER BY `id` ASC;");
		
		$queued = $rejected = false;
		foreach($allbanners as $singlebanner) {
			
			$starttime = $stoptime = 0;
			$starttime = $wpdb->get_var("SELECT `starttime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `starttime` ASC LIMIT 1;");
			$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");
			
			if($singlebanner->type == 'queue') {
				$queued[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'type' => $singlebanner->type,
					'tracker' => $singlebanner->tracker,
					'mobile' => $singlebanner->mobile,
					'tablet' => $singlebanner->tablet,
					'weight' => $singlebanner->weight,
					'budget' => $singlebanner->budget,
					'crate' => $singlebanner->crate,
					'irate' => $singlebanner->irate,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
			
			if($singlebanner->type == 'reject') {
				$rejected[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'type' => $singlebanner->type,
					'tracker' => $singlebanner->tracker,
					'mobile' => $singlebanner->mobile,
					'tablet' => $singlebanner->tablet,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
		}
		?>

    	<?php
    	if ($view == "" OR $view == "manage") {
			// Show list of queued ads			
			include("dashboard/publisher/moderation-queue.php");

			// Show rejected ads, if any
			if($rejected) {
				include("dashboard/publisher/moderation-rejected.php");
			}
		} else if($view == "message") {
			$wpnonceaction = 'adrotate_moderate_'.$request_id;
			if(wp_verify_nonce($_REQUEST['_wpnonce'], $wpnonceaction)) {
				include("dashboard/publisher/moderation-message.php");
			} else {
				adrotate_nonce_error();
				exit;
			}
		}
		?>
		<br class="clear" />

		<?php adrotate_credits(); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_advertiser

 Purpose:   Advertiser page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_advertiser() {
	global $wpdb, $current_user, $adrotate_config, $adrotate_debug;
		
	get_currentuserinfo();
	
	$status = $view = $ad_edit_id = $request = $request_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['ad'])) $ad_edit_id = esc_attr($_GET['ad']);
	if(isset($_GET['file'])) $filename = esc_attr($_GET['file']);
	if(isset($_GET['request'])) $request = esc_attr($_GET['request']);
	if(isset($_GET['id'])) $request_id = esc_attr($_GET['id']);
	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	$in84days 		= $now + 7257600;

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	
	?>
	<div class="wrap">
	  	<h1><?php _e('Advertiser', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status, array('file' => $filename)); ?>

		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-advertiser&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a>
				<?php if($adrotate_config['enable_editing'] == 'Y') { ?>
				 | <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-advertiser&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a> 
				<?php  } ?>
			</div>
		</div>

		<?php 
		$wpnonceaction = 'adrotate_email_advertiser_'.$request_id;
		if($view == "" OR $view == "manage") {
			
			$ads = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `group` = 0 AND `user` = %d ORDER BY `ad` ASC;", $current_user->ID));

			if($ads) {
				$activebanners = $queuebanners = $disabledbanners = false;
				foreach($ads as $ad) {
					$banner = $wpdb->get_row("SELECT `id`, `title`, `type`, `mobile`, `tablet`, `budget`, `crate`, `irate` FROM `{$wpdb->prefix}adrotate` WHERE (`type` = 'active' OR `type` = '2days' OR `type` = '7days' OR `type` = 'disabled' OR `type` = 'error' OR `type` = 'a_error' OR `type` = 'expired' OR `type` = 'queue' OR `type` = 'reject') AND `id` = '".$ad->ad."';");

					// Skip if no ad
					if(!$banner) continue;
					
					$starttime = $stoptime = 0;
					$starttime = $wpdb->get_var("SELECT `starttime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$banner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `starttime` ASC LIMIT 1;");
					$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$banner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");
	
					$type = $banner->type;
					if($type == 'active' AND $stoptime <= $in7days) $type = '7days';
					if($type == 'active' AND $stoptime <= $in2days) $type = '2days';
					if($type == 'active' AND $stoptime <= $now) $type = 'expired'; 

					if($type == 'active' OR $type == '2days' OR $type == '7days' OR $type == 'expired') {
						$activebanners[$banner->id] = array(
							'id' => $banner->id,
							'title' => $banner->title,
							'type' => $type,
							'mobile' => $banner->mobile,
							'tablet' => $banner->tablet,
							'firstactive' => $starttime,
							'lastactive' => $stoptime,
							'budget' => $banner->budget,
							'crate' => $banner->crate,
							'irate' => $banner->irate
						);
					}
	
					if($type == 'disabled') {
						$disabledbanners[$banner->id] = array(
							'id' => $banner->id,
							'title' => $banner->title,
							'type' => $type
						);
					}

					if($type == 'queue' OR $type == 'reject' OR $type == 'error' OR $type == 'a_error') {
						$queuebanners[$banner->id] = array(
							'id' => $banner->id,
							'title' => $banner->title,
							'type' => $type,
							'mobile' => $banner->mobile,
							'tablet' => $banner->tablet,
							'budget' => $banner->budget,
							'crate' => $banner->crate,
							'irate' => $banner->irate
						);
					}
				}
				
				// Show active ads, if any
				if($activebanners) {
					include("dashboard/advertiser/main.php");
				}

				// Show disabled ads, if any
				if($disabledbanners) {
					include("dashboard/advertiser/main-disabled.php");
				}

				// Show queued ads, if any
				if($queuebanners) {
					include("dashboard/advertiser/main-queue.php");
				}

				if($adrotate_config['stats'] == 1) {
					// Gather data for summary report
					$summary = adrotate_prepare_advertiser_report($current_user->ID, $activebanners);
					include("dashboard/advertiser/main-summary.php");
				}

			} else {
				?>
				<table class="widefat" style="margin-top: .5em">
					<thead>
						<tr>
							<th><?php _e('Notice', 'adrotate'); ?></th>
						</tr>
					</thead>
					<tbody>
					    <tr>
							<td><?php _e('No ads for user.', 'adrotate'); ?></td>
						</tr>
					</tbody>
				</table>
				<?php
			}
		} else if($view == "addnew" OR $view == "edit") { 

			include("dashboard/advertiser/edit.php");

		} else if($view == "report") { 

			include("dashboard/advertiser/report.php");

		} else if($view == "message") {

			if(wp_verify_nonce($_REQUEST['_wpnonce'], $wpnonceaction)) {
				include("dashboard/advertiser/message.php");
			} else {
				adrotate_nonce_error();
				exit;
			}

		}
		?>
		<br class="clear" />

		<?php adrotate_user_notice(); ?>

		<br class="clear" />
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_options

 Purpose:   Admin options page
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_options() {
	global $wpdb, $wp_roles;

    $active_tab = (isset($_GET['tab'])) ? esc_attr($_GET['tab']) : 'general';
	$status = (isset($_GET['status'])) ? esc_attr($_GET['status']) : '';
	$error = (isset($_GET['error'])) ? esc_attr($_GET['error']) : '';
	?>

	<div class="wrap">
	  	<h1><?php _e('AdRotate Settings', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status, array('error' => $error)); ?>

		<h2 class="nav-tab-wrapper">  
            <a href="?page=adrotate-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>  
            <a href="?page=adrotate-settings&tab=notifications" class="nav-tab <?php echo $active_tab == 'notifications' ? 'nav-tab-active' : ''; ?>">Notifications</a>  
            <a href="?page=adrotate-settings&tab=stats" class="nav-tab <?php echo $active_tab == 'stats' ? 'nav-tab-active' : ''; ?>">Stats</a>  
            <a href="?page=adrotate-settings&tab=geo" class="nav-tab <?php echo $active_tab == 'geo' ? 'nav-tab-active' : ''; ?>">Geo Targeting</a>  
            <a href="?page=adrotate-settings&tab=advertisers" class="nav-tab <?php echo $active_tab == 'advertisers' ? 'nav-tab-active' : ''; ?>">Advertisers</a>  
            <a href="?page=adrotate-settings&tab=roles" class="nav-tab <?php echo $active_tab == 'roles' ? 'nav-tab-active' : ''; ?>">Roles</a>  
            <a href="?page=adrotate-settings&tab=misc" class="nav-tab <?php echo $active_tab == 'misc' ? 'nav-tab-active' : ''; ?>">Misc</a>  
            <a href="?page=adrotate-settings&tab=maintenance" class="nav-tab <?php echo $active_tab == 'maintenance' ? 'nav-tab-active' : ''; ?>">Maintenance</a>  
            <a href="?page=adrotate-settings&tab=license" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>">License</a>  
        </h2>		

	  	<form name="settings" id="post" method="post" action="admin.php?page=adrotate-settings">
	    	<input type="hidden" name="adrotate_settings_tab" value="<?php echo $active_tab; ?>" />

			<?php wp_nonce_field('adrotate_email_test','adrotate_nonce'); ?>
			<?php wp_nonce_field('adrotate_settings','adrotate_nonce_settings'); ?>
			<?php wp_nonce_field('adrotate_license','adrotate_nonce_license'); ?>

			<?php
			$adrotate_config = get_option('adrotate_config');
			$adrotate_debug = get_option('adrotate_debug');

			if($active_tab == 'general') {  
				$adrotate_crawlers = get_option('adrotate_crawlers');

				$crawlers = '';
				if(is_array($adrotate_crawlers)) {
					$crawlers = implode(', ', $adrotate_crawlers);
				}

				include("dashboard/settings/general.php");						
			} elseif($active_tab == 'notifications') {
				$adrotate_notifications	= get_option("adrotate_notifications");

				$notification_mails = $advertiser_mails = '';
				if(is_array($adrotate_notifications['notification_email_publisher'])) {
					$notification_mails	= implode(', ', $adrotate_notifications['notification_email_publisher']);
				}
				if(is_array($adrotate_notifications['notification_email_advertiser'])) {
					$advertiser_mails = implode(', ', $adrotate_notifications['notification_email_advertiser']);
				}

				include("dashboard/settings/notifications.php");						
			} elseif($active_tab == 'stats') {
				include("dashboard/settings/statistics.php");						
			} elseif($active_tab == 'geo') {
				$adrotate_geo_requests = get_option("adrotate_geo_requests");
				$adrotate_geo = adrotate_get_cookie('geo');

				include("dashboard/settings/geotargeting.php");						
			} elseif($active_tab == 'advertisers') {
				include("dashboard/settings/advertisers.php");						
			} elseif($active_tab == 'roles') {
				include("dashboard/settings/roles.php");						
			} elseif($active_tab == 'misc') {
				include("dashboard/settings/misc.php");						
			} elseif($active_tab == 'maintenance') {
				$adrotate_version = get_option('adrotate_version');
				$adrotate_db_version = get_option('adrotate_db_version');
				$adrotate_advert_status	= get_option("adrotate_advert_status");

				$adevaluate = wp_next_scheduled('adrotate_evaluate_ads');
				$adschedule = wp_next_scheduled('adrotate_notification');
				$adtracker = wp_next_scheduled('adrotate_clean_trackerdata');

				include("dashboard/settings/maintenance.php");						
			} elseif($active_tab == 'license') {
				$adrotate_is_networked = adrotate_is_networked();
				$adrotate_hide_license = get_option('adrotate_hide_license');
				if($adrotate_is_networked) {
					$adrotate_activate = get_site_option('adrotate_activate');
				} else {
					$adrotate_activate = get_option('adrotate_activate');
				}

				$subscription = '';
				if($adrotate_activate['version'] == 104) {
					$subscription = ($adrotate_activate['type'] == 'Single') ? 'Lifetime ' : 'Subscription ';
				}

				include("dashboard/settings/license.php");						
			}
			?>

			<?php if($active_tab != 'license') { ?>
		    <p class="submit">
		      	<input type="submit" name="adrotate_options_submit" class="button-primary" value="<?php _e('Update Options', 'adrotate'); ?>" />
		    </p>
		    <?php } ?>
		</form>
	</div>
<?php 
}

/*-------------------------------------------------------------
 Name:      adrotate_network_license

 Purpose:   Network activated license dashboard
 Receive:   -none-
 Return:    -none-
-------------------------------------------------------------*/
function adrotate_network_license() {
	global $wpdb, $adrotate_advert_status;

	$status = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	$adrotate_activate = get_site_option('adrotate_activate');
	?>

	<div class="wrap">
	  	<h1><?php _e('AdRotate Network License', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>
		
	  	<form name="settings" id="post" method="post" action="admin.php?page=adrotate-network-settings">
			<input type="hidden" name="adrotate_license_network" value="1" />

			<?php wp_nonce_field('adrotate_license','adrotate_nonce_license'); ?>

			<span class="description"><?php _e('Activate your AdRotate License here to receive automated updates and enable support via the fast and personal ticket system.', 'adrotate'); ?><br />
			<?php _e('For network activated setups like this you need a Network or Developer License.', 'adrotate'); ?></span>
			<table class="form-table">
				<tr>
					<th valign="top"><?php _e('License Type', 'adrotate'); ?></th>
					<td>
						<?php echo ($adrotate_activate['type'] != '') ? $adrotate_activate['type'] : __('Not activated - Not eligible for support and updates.', 'adrotate'); ?>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php _e('License Key', 'adrotate'); ?></th>
					<td>
						<input name="adrotate_license_key" type="text" class="search-input" size="50" value="<?php echo $adrotate_activate['key']; ?>" autocomplete="off" <?php echo ($adrotate_activate['status'] == 1) ? 'disabled' : ''; ?> /> <span class="description"><?php _e('You can find the license key in your order email.', 'adrotate'); ?></span>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php _e('License Email', 'adrotate'); ?></th>
					<td>
						<input name="adrotate_license_email" type="text" class="search-input" size="50" value="<?php echo $adrotate_activate['email']; ?>" autocomplete="off" <?php echo ($adrotate_activate['status'] == 1) ? 'disabled' : ''; ?> /> <span class="description"><?php _e('The email address you used in your purchase of AdRotate Pro.', 'adrotate'); ?></span>
					</td>
				</tr>

				<tr>
					<th valign="top">&nbsp;</th>
					<td>
						<?php if($adrotate_activate['status'] == 0) { ?>
						<input type="submit" id="post-role-submit" name="adrotate_license_activate" value="<?php _e('Activate', 'adrotate'); ?>" class="button-primary" />
						<?php } else { ?>
						<input type="submit" id="post-role-submit" name="adrotate_license_deactivate" value="<?php _e('De-activate', 'adrotate'); ?>" class="button-secondary" />
						<?php } ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php
}
?>