<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_insert_input

 Purpose:   Prepare input form on saving new or updated banners
 Receive:   -None-
 Return:	-None-
 Since:		0.1 
-------------------------------------------------------------*/
function adrotate_insert_input() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_ad')) {
		// Mandatory
		$id = $author = $title = $bannercode = $active = $sortorder = '';
		if(isset($_POST['adrotate_id'])) $id = $_POST['adrotate_id'];
		if(isset($_POST['adrotate_username'])) $author = $_POST['adrotate_username'];
		if(isset($_POST['adrotate_title'])) $title = strip_tags(htmlspecialchars(trim($_POST['adrotate_title'], "\t\n "), ENT_QUOTES));
		if(isset($_POST['adrotate_bannercode'])) $bannercode = htmlspecialchars(trim($_POST['adrotate_bannercode'], "\t\n "), ENT_QUOTES);
		$thetime = adrotate_now();
		if(isset($_POST['adrotate_active'])) $active = strip_tags(htmlspecialchars(trim($_POST['adrotate_active'], "\t\n "), ENT_QUOTES));
		if(isset($_POST['adrotate_sortorder'])) $sortorder = strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

		// Schedule variables
		$sday = $smonth = $syear = $shour = $sminute = '';
		if(isset($_POST['adrotate_sday'])) $sday = strip_tags(trim($_POST['adrotate_sday'], "\t\n "));
		if(isset($_POST['adrotate_smonth'])) $smonth = strip_tags(trim($_POST['adrotate_smonth'], "\t\n "));
		if(isset($_POST['adrotate_syear'])) $syear = strip_tags(trim($_POST['adrotate_syear'], "\t\n "));
		if(isset($_POST['adrotate_shour'])) $shour = strip_tags(trim($_POST['adrotate_shour'], "\t\n "));
		if(isset($_POST['adrotate_sminute'])) $sminute = strip_tags(trim($_POST['adrotate_sminute'], "\t\n "));

		$eday = $emonth = $eyear = $ehour = $eminute = '';
		if(isset($_POST['adrotate_eday'])) $eday = strip_tags(trim($_POST['adrotate_eday'], "\t\n "));
		if(isset($_POST['adrotate_emonth'])) $emonth = strip_tags(trim($_POST['adrotate_emonth'], "\t\n "));
		if(isset($_POST['adrotate_eyear'])) $eyear = strip_tags(trim($_POST['adrotate_eyear'], "\t\n "));
		if(isset($_POST['adrotate_ehour'])) $ehour = strip_tags(trim($_POST['adrotate_ehour'], "\t\n "));
		if(isset($_POST['adrotate_eminute'])) $eminute = strip_tags(trim($_POST['adrotate_eminute'], "\t\n "));
	
		$maxclicks = $maxshown = $spread = $dayimpressions = '';
		if(isset($_POST['adrotate_maxclicks'])) $maxclicks = strip_tags(trim($_POST['adrotate_maxclicks'], "\t\n "));
		if(isset($_POST['adrotate_maxshown'])) $maxshown = strip_tags(trim($_POST['adrotate_maxshown'], "\t\n "));	
		if(isset($_POST['adrotate_spread'])) $spread = strip_tags(trim($_POST['adrotate_spread'], "\t\n "));	

		// Schedules
		$schedules = '';
		if(isset($_POST['scheduleselect'])) $schedules = $_POST['scheduleselect'];

		// Advanced options
		$image_field = $image_dropdown = $link = $tracker = $mobile = $tablet = $responsive = '';
		if(isset($_POST['adrotate_image'])) $image_field = strip_tags(trim($_POST['adrotate_image'], "\t\n "));
		if(isset($_POST['adrotate_image_dropdown'])) $image_dropdown = strip_tags(trim($_POST['adrotate_image_dropdown'], "\t\n "));
		if(isset($_POST['adrotate_link'])) $link = strip_tags(trim($_POST['adrotate_link'], "\t\n "));
		if(isset($_POST['adrotate_tracker'])) $tracker = strip_tags(trim($_POST['adrotate_tracker'], "\t\n "));
		if(isset($_POST['adrotate_mobile'])) $mobile = strip_tags(trim($_POST['adrotate_mobile'], "\t\n "));
		if(isset($_POST['adrotate_tablet'])) $tablet = strip_tags(trim($_POST['adrotate_tablet'], "\t\n "));
		if(isset($_POST['adrotate_responsive'])) $responsive = strip_tags(trim($_POST['adrotate_responsive'], "\t\n "));
	
		// GeoTargeting
		$cities = '';
		$countries = $countries_westeurope = $countries_easteurope = $countries_northamerica = $countries_southamerica = $countries_southeastasia = array();
		if(isset($_POST['adrotate_geo_cities'])) $cities = strip_tags(trim($_POST['adrotate_geo_cities'], "\t\n "));
		if(isset($_POST['adrotate_geo_countries'])) $countries = $_POST['adrotate_geo_countries'];
		if(isset($_POST['adrotate_geo_westeurope'])) $countries_westeurope = array('AD', 'AT', 'BE', 'DK', 'FR', 'DE', 'GR', 'IS', 'IE', 'IT', 'LI', 'LU', 'MT', 'MC', 'NL', 'NO', 'PT', 'SM', 'ES', 'SE', 'CH', 'VA', 'GB');
		if(isset($_POST['adrotate_geo_easteurope'])) $countries_easteurope = array('AL', 'AM', 'AZ', 'BY', 'BA', 'BG', 'HR', 'CY', 'CZ', 'EE', 'FI', 'GE', 'HU', 'LV', 'LT', 'MK', 'MD', 'PL', 'RO', 'RS', 'SK', 'SI', 'TR', 'UA');
		if(isset($_POST['adrotate_geo_northamerica'])) $countries_northamerica = array('AG', 'BS', 'BB', 'BZ', 'CA', 'CR', 'CU', 'DM', 'DO', 'SV', 'GD', 'GT', 'HT', 'HN', 'JM', 'MX', 'NI', 'PA', 'KN', 'LC', 'VC', 'TT', 'US');
		if(isset($_POST['adrotate_geo_southamerica'])) $countries_southamerica = array('AR', 'BO', 'BR', 'CL', 'CO', 'EC', 'GY', 'PY', 'PE', 'SR', 'UY', 'VE');
		if(isset($_POST['adrotate_geo_southeastasia'])) $countries_southeastasia = array('AU', 'BN', 'KH', 'TL', 'ID', 'LA', 'MY', 'MM', 'NZ', 'PH', 'SG', 'TH', 'VN');
	
		// advertiser
		$advertiser = $crate = $irate = $budget = '';
		if(isset($_POST['adrotate_advertiser'])) $advertiser = $_POST['adrotate_advertiser'];
		if(isset($_POST['adrotate_crate'])) $crate = strip_tags(trim($_POST['adrotate_crate'], "\t\n "));
		if(isset($_POST['adrotate_irate'])) $irate = strip_tags(trim($_POST['adrotate_irate'], "\t\n "));
		if(isset($_POST['adrotate_budget'])) $budget = strip_tags(trim($_POST['adrotate_budget'], "\t\n "));
		
		// Misc variables
		$groups = $type = $weight = '';
		if(isset($_POST['groupselect'])) $groups = $_POST['groupselect'];
		if(isset($_POST['adrotate_type'])) $type = strip_tags(trim($_POST['adrotate_type'], "\t\n "));
		if(isset($_POST['adrotate_weight'])) $weight = strip_tags($_POST['adrotate_weight']);
	
	
		if(current_user_can('adrotate_ad_manage')) {
			if(strlen($title) < 1) {
				$title = 'Ad '.$id;
			}
	
			// Clean up bannercode
			if(preg_match("/%ID%/", $bannercode)) $bannercode = str_replace('%ID%', '%id%', $bannercode);
			if(preg_match("/%IMAGE%/", $bannercode)) $bannercode = str_replace('%IMAGE%', '%image%', $bannercode);
			if(preg_match("/%TITLE%/", $bannercode)) $bannercode = str_replace('%TITLE%', '%title%', $bannercode);
			if(preg_match("/%RANDOM%/", $bannercode)) $bannercode = str_replace('%RANDOM%', '%random%', $bannercode);
			// Replace %link% with the actual url (Deprecate $link)
			if(strlen($link) > 0 AND preg_match("/%link%/i", $bannercode)) $bannercode = str_replace('%link%', $link, $bannercode);
	
			// Validate sort order
			if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = 0;
	
			// Sort out start dates
			if(strlen($smonth) > 0 AND !is_numeric($smonth)) 	$smonth 	= date_i18n('m');
			if(strlen($sday) > 0 AND !is_numeric($sday)) 		$sday 		= date_i18n('d');
			if(strlen($syear) > 0 AND !is_numeric($syear)) 		$syear 		= date_i18n('Y');
			if(strlen($shour) > 0 AND !is_numeric($shour)) 		$shour 		= date_i18n('H');
			if(strlen($sminute) > 0 AND !is_numeric($sminute))	$sminute	= date_i18n('i');
			if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($shour) == 0) $shour = '00';
			if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($sminute) == 0) $sminute = '00';
	
			if($smonth > 0 AND $sday > 0 AND $syear > 0) {
				$startdate = mktime($shour, $sminute, 0, $smonth, $sday, $syear);
			} else {
				$startdate = 0;
			}
			
			// Sort out end dates
			if(strlen($emonth) > 0 AND !is_numeric($emonth)) 	$emonth 	= $smonth;
			if(strlen($eday) > 0 AND !is_numeric($eday)) 		$eday 		= $sday;
			if(strlen($eyear) > 0 AND !is_numeric($eyear)) 		$eyear 		= $syear+1;
			if(strlen($ehour) > 0 AND !is_numeric($ehour)) 		$ehour 		= $shour;
			if(strlen($eminute) > 0 AND !is_numeric($eminute)) 	$eminute	= $sminute;
			if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($ehour) == 0) $ehour = '00';
			if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($eminute) == 0) $eminute = '00';
	
			if($emonth > 0 AND $eday > 0 AND $eyear > 0) {
				$enddate = mktime($ehour, $eminute, 0, $emonth, $eday, $eyear);
			} else {
				$enddate = 0;
			}
			
			// Enddate is too early, reset to default
			if($enddate <= $startdate) $enddate = $startdate + 7257600; // 84 days (12 weeks)
	
			// Sort out click and impressions restrictions
			if(strlen($maxclicks) < 1 OR !is_numeric($maxclicks)) $maxclicks = 0;
			if(strlen($maxshown) < 1 OR !is_numeric($maxshown))	$maxshown = 0;
	
			// Impression Spread
			if(isset($spread) AND strlen($spread) != 0 AND $maxshown > 0) {
				$spread = 'Y';
				$dayimpressions = round($maxshown/(($enddate - $startdate)/86400));
			} else {
				$spread = 'N';
				$dayimpressions = 0;
			}

			// Save the schedule to the DB
			if($startdate > 0 AND $enddate > 0) {
				$wpdb->insert($wpdb->prefix.'adrotate_schedule', array('name' => 'Schedule for ad '.$id, 'starttime' => $startdate, 'stoptime' => $enddate, 'maxclicks' => $maxclicks, 'maximpressions' => $maxshown, 'spread' => $spread, 'dayimpressions' => $dayimpressions));
				$schedules[] = $wpdb->insert_id;
			}

			// Set tracker value
			if(isset($tracker) AND strlen($tracker) != 0) $tracker = 'Y';
				else $tracker = 'N';

			// Set mobile value
			if(isset($mobile) AND strlen($mobile) != 0) $mobile = 'Y';
				else $mobile = 'N';
			
			// Set tablet value
			if(isset($tablet) AND strlen($tablet) != 0) $tablet = 'Y';
				else $tablet = 'N';
			
			// Set responsive value
			if(isset($responsive) AND strlen($responsive) != 0) $responsive = 'Y';
				else $responsive = 'N';
			
			// Rate and Budget settings
			if((strlen($crate) == 0 OR $crate == "" OR !is_numeric($crate) OR $crate < 0 OR $crate > 100)) $crate = 0;
			if(strlen($irate) == 0 OR $irate == "" OR !is_numeric($irate) OR $irate < 0 OR $irate > 100) $irate = 0;
			if($crate == 0 AND $irate == 0) $budget = 0;
			
			$budget == number_format($budget, 4, '.', '');
			$irate = number_format($irate, 4, '.', '');
			$crate = number_format($crate, 4, '.', '');
			
			// Determine image settings ($image_field has priority!)
			if(strlen($image_field) > 1) {
				$imagetype = "field";
				$image = $image_field;
			} else if(strlen($image_dropdown) > 1) {
				$imagetype = "dropdown";
				$image = site_url()."/%folder%".$image_dropdown;
			} else {
				$imagetype = "";
				$image = "";
			}
	
			// Geo Targeting
			if(strlen($cities) > 0) {
				$cities = explode(",", strtolower($cities));
				foreach($cities as $key => $value) {
					$cities_clean[] = trim($value);
					unset($value);
				}
				unset($cities);
				$cities = serialize($cities_clean);
			} else {
				$cities = serialize(array());
			}

			$countries = array_merge($countries, $countries_westeurope, $countries_easteurope, $countries_northamerica, $countries_southamerica, $countries_southeastasia);
			$countries = array_unique($countries);
			if(count($countries) == 0) {
				$countries = serialize(array());
			} else {
				foreach($countries as $key => $value) {
					$countries_clean[] = trim($value);
					unset($value);
				}
				unset($countries);
				$countries = serialize($countries_clean);
			}

			// Save the ad to the DB
			$wpdb->update($wpdb->prefix.'adrotate', array('title' => $title, 'bannercode' => $bannercode, 'updated' => $thetime, 'author' => $author, 'imagetype' => $imagetype, 'image' => $image, 'link' => $link, 'tracker' => $tracker, 'mobile' => $mobile, 'tablet' => $tablet, 'responsive' => $responsive, 'type' => $active, 'weight' => $weight, 'sortorder' => $sortorder, 'budget' => $budget, 'crate' => $crate, 'irate' => $irate, 'cities' => $cities, 'countries' => $countries), array('id' => $id));

			// Determine Responsive requirement
			$responsive_count = $wpdb->get_var("SELECT COUNT(*) as `total` FROM `".$wpdb->prefix."adrotate` WHERE `responsive` = 'Y';");
			update_option('adrotate_responsive_required', $responsive_count);
	
			// Fetch group records for the ad
			$groupmeta = $wpdb->get_results($wpdb->prepare("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `user` = 0 AND `schedule` = 0;", $id));
			$group_array = array();
			foreach($groupmeta as $meta) {
				$group_array[] = $meta->group;
				unset($meta);
			}
			
			// Add new groups to this ad
			if(!is_array($groups)) $groups = array();
			$insert = array_diff($groups, $group_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => $value, 'user' => 0, 'schedule' => 0));
			}
			unset($insert, $value);
			
			// Remove groups from this ad
			$delete = array_diff($group_array, $groups);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = %d AND `user` = 0 AND `schedule` = 0;", $id, $value)); 
			}
			unset($delete, $value, $groupmeta, $group_array);
	
			// Fetch schedules for the ad
			$schedulemeta = $wpdb->get_results($wpdb->prepare("SELECT `schedule` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0;", $id));
			$schedule_array = array();
			foreach($schedulemeta as $meta) {
				$schedule_array[] = $meta->schedule;
				unset($meta);
			}
			
			// Add new schedules to this ad
			if(!is_array($schedules)) $schedules = array();
			$insert = array_diff($schedules, $schedule_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => 0, 'user' => 0, 'schedule' => $value));
			}
			unset($insert, $value);
			
			// Remove schedules from this ad
			$delete = array_diff($schedule_array, $schedules);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0 AND `schedule` = %d;", $id, $value)); 
			}
			unset($delete, $value, $schedulemeta, $schedule_array);

			// Fetch records for the ad, see if a publisher is set
			$linkmeta = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` > 0 AND `schedule` = 0;", $id));
	
			// Add/update/remove publisher on this ad
			if($linkmeta == 0 AND $advertiser > 0) $wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => 0, 'user' => $advertiser, 'schedule' => 0));
			if($linkmeta == 1 AND $advertiser > 0) $wpdb->query($wpdb->prepare("UPDATE `".$wpdb->prefix."adrotate_linkmeta` SET `user` = $advertiser WHERE `ad` = %d AND `group` = 0 AND `schedule` = 0;", $id)); 
			if($linkmeta == 1 AND $advertiser == 0) $wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `schedule` = 0;", $id)); 

			// Verify ad
			if($active == "active") {
				adrotate_prepare_evaluate_ads(false, $id);
			}

			adrotate_return('adrotate-ads', 200);
			exit;
		} else {
			adrotate_return('adrotate-ads', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_group

 Purpose:   Save provided data for groups, update linkmeta where required
 Receive:   -None-
 Return:	-None-
 Since:		0.4
-------------------------------------------------------------*/
function adrotate_insert_group() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_group')) {
		$action = $id = $name = $modus = '';
		if(isset($_POST['adrotate_action'])) $action = $_POST['adrotate_action'];
		if(isset($_POST['adrotate_id'])) $id = $_POST['adrotate_id'];
		if(isset($_POST['adrotate_groupname'])) $name = strip_tags(trim($_POST['adrotate_groupname'], "\t\n "));
		if(isset($_POST['adrotate_modus'])) $modus = strip_tags(trim($_POST['adrotate_modus'], "\t\n "));

		$rows = $columns = $adwidth = $adheight = $admargin = $adspeed = '';
		if(isset($_POST['adrotate_gridrows'])) $rows = strip_tags(trim($_POST['adrotate_gridrows'], "\t\n "));
		if(isset($_POST['adrotate_gridcolumns'])) $columns = strip_tags(trim($_POST['adrotate_gridcolumns'], "\t\n "));
		if(isset($_POST['adrotate_adwidth'])) $adwidth = strip_tags(trim($_POST['adrotate_adwidth'], "\t\n "));
		if(isset($_POST['adrotate_adheight'])) $adheight = strip_tags(trim($_POST['adrotate_adheight'], "\t\n "));
		if(isset($_POST['adrotate_admargin_top'])) $admargin_top = strip_tags(trim($_POST['adrotate_admargin_top'], "\t\n "));
		if(isset($_POST['adrotate_admargin_bottom'])) $admargin_bottom = strip_tags(trim($_POST['adrotate_admargin_bottom'], "\t\n "));
		if(isset($_POST['adrotate_admargin_left'])) $admargin_left = strip_tags(trim($_POST['adrotate_admargin_left'], "\t\n "));
		if(isset($_POST['adrotate_admargin_right'])) $admargin_right = strip_tags(trim($_POST['adrotate_admargin_right'], "\t\n "));
		if(isset($_POST['adrotate_adspeed'])) $adspeed = strip_tags(trim($_POST['adrotate_adspeed'], "\t\n "));

		$fallback = $ads = $geo = $sortorder = $mobile = $align = '';
		if(isset($_POST['adrotate_geo'])) $geo = $_POST['adrotate_geo'];
		if(isset($_POST['adrotate_mobile'])) $mobile = $_POST['adrotate_mobile'];
		if(isset($_POST['adrotate_fallback'])) $fallback = $_POST['adrotate_fallback'];
		if(isset($_POST['adselect'])) $ads = $_POST['adselect'];
		if(isset($_POST['adrotate_align'])) $align = strip_tags(trim($_POST['adrotate_align'], "\t\n "));
		if(isset($_POST['adrotate_sortorder'])) $sortorder = strip_tags(htmlspecialchars(trim($_POST['adrotate_sortorder'], "\t\n "), ENT_QUOTES));

		$categories = $category_loc = $category_par = $pages = $page_loc = $page_par = '';
		if(isset($_POST['adrotate_categories'])) $categories = $_POST['adrotate_categories'];
		if(isset($_POST['adrotate_cat_location'])) $category_loc = $_POST['adrotate_cat_location'];
		if(isset($_POST['adrotate_cat_paragraph'])) $category_par = $_POST['adrotate_cat_paragraph'];
		if(isset($_POST['adrotate_pages'])) $pages = $_POST['adrotate_pages'];
		if(isset($_POST['adrotate_page_location'])) $page_loc = $_POST['adrotate_page_location'];
		if(isset($_POST['adrotate_page_paragraph'])) $page_par = $_POST['adrotate_page_paragraph'];

		$wrapper_before = $wrapper_after = '';
		if(isset($_POST['adrotate_wrapper_before'])) $wrapper_before = trim($_POST['adrotate_wrapper_before'], "\t\n ");
		if(isset($_POST['adrotate_wrapper_after'])) $wrapper_after = trim($_POST['adrotate_wrapper_after'], "\t\n ");
	
		if(current_user_can('adrotate_group_manage')) {
			if(strlen($name) < 1) $name = 'Group '.$id;

			if($modus < 0 OR $modus > 2) $modus = 0;
			if($adspeed < 0 OR $adspeed > 99999) $adspeed = 6000;
			if($align < 0 OR $align > 3) $align = 0;

			// Set geo value
			if(is_numeric($geo)) $geo = 1;
				else $geo = 0;
			
			// Set mobile value
			if(is_numeric($mobile)) $mobile = 1;
				else $mobile = 0;
			
			// Set fallback value
			if(!is_numeric($fallback) OR $fallback == $id) $fallback = 0;
			
			// Sort out block shape
			if($rows < 1 OR $rows == '' OR !is_numeric($rows)) $rows = 2;
			if($columns < 1 OR $columns == '' OR !is_numeric($columns)) $columns = 2;
			if((is_numeric($adwidth) AND $adwidth < 1 OR $adwidth > 9999) OR $adwidth == '' OR (!is_numeric($adwidth) AND $adwidth != 'auto')) $adwidth = '125';
			if((is_numeric($adheight) AND $adheight < 1 OR $adheight > 9999) OR $adheight == '' OR (!is_numeric($adheight) AND $adheight != 'auto')) $adheight = '125';
			if($admargin_top < 0 OR $admargin_top > 99 OR $admargin_top == '' OR !is_numeric($admargin_top)) $admargin_top = 0;
			if($admargin_bottom < 0 OR $admargin_bottom > 99 OR $admargin_bottom == '' OR !is_numeric($admargin_bottom)) $admargin_bottom = 0;
			if($admargin_left < 0 OR $admargin_left > 99 OR $admargin_left == '' OR !is_numeric($admargin_left)) $admargin_left = 0;
			if($admargin_right < 0 OR $admargin_right > 99 OR $admargin_right == '' OR !is_numeric($admargin_right)) $admargin_right = 0;

			// Validate sort order
			if(strlen($sortorder) < 1 OR !is_numeric($sortorder) AND ($sortorder < 1 OR $sortorder > 99999)) $sortorder = $id;
	
			// Categories
			if(!is_array($categories)) $categories = array();
			$category = '';
			foreach($categories as $key => $value) {
				$category = $category.','.$value;
			}
			$category = trim($category, ',');
			if(strlen($category) < 1) $category = '';

			
			if($category_par > 0) $category_loc = 4;
			if($category_loc != 4) $category_par = 0;
			
			// Pages
			if(!is_array($pages)) $pages = array();
			$page = '';
			foreach($pages as $key => $value) {
				$page = $page.','.$value;
			}
			$page = trim($page, ',');
			if(strlen($page) < 1) $page = '';
			
			if($page_par > 0) $page_loc = 4;
			if($page_loc != 4) $page_par = 0;

			// Fetch records for the group
			$linkmeta = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = %d AND `user` = 0;", $id));
			foreach($linkmeta as $meta) {
				$meta_array[] = $meta->ad;
			}
			
			if(empty($meta_array)) $meta_array = array();
			if(empty($ads)) $ads = array();
	
			// Add new ads to this group
			$insert = array_diff($ads,$meta_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $value, 'group' => $id, 'user' => 0));
			}
			unset($value);
			
			// Remove ads from this group
			$delete = array_diff($meta_array,$ads);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = %d AND `user` = 0;", $value, $id)); 
			}
			unset($value);
	
			// Update the group itself
			$wpdb->update($wpdb->prefix.'adrotate_groups', array('name' => $name, 'modus' => $modus, 'fallback' => $fallback, 'sortorder' => $sortorder, 'cat' => $category, 'cat_loc' => $category_loc,  'cat_par' => $category_par, 'page' => $page, 'page_loc' => $page_loc, 'page_par' => $page_par, 'mobile' => $mobile, 'geo' => $geo, 'wrapper_before' => $wrapper_before, 'wrapper_after' => $wrapper_after, 'align' => $align, 'gridrows' => $rows, 'gridcolumns' => $columns, 'admargin' => $admargin_top, 'admargin_bottom' => $admargin_bottom, 'admargin_left' => $admargin_left, 'admargin_right' => $admargin_right, 'adwidth' => $adwidth, 'adheight' => $adheight, 'adspeed' => $adspeed), array('id' => $id));

			// Determine GeoLocation Library requirement
			$geo_count = $wpdb->get_var("SELECT COUNT(*) as `total` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' AND `geo` = 1;");
			update_option('adrotate_geo_required', $geo_count);

			// Determine Dynamic Library requirement
			$dynamic_count = $wpdb->get_var("SELECT COUNT(*) as `total` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' AND `modus` = 1;");
			update_option('adrotate_dynamic_required', $dynamic_count);
	

			adrotate_return('adrotate-groups', 201);
			exit;
		} else {
			adrotate_return('adrotate-groups', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_schedule

 Purpose:   Prepare input form on saving new or updated schedules
 Receive:   -None-
 Return:	-None-
 Since:		3.8.9 
-------------------------------------------------------------*/
function adrotate_insert_schedule() {
	global $wpdb;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_schedule')) {
		// Mandatory
		$id = $ad = '';
		if(isset($_POST['adrotate_id'])) $id = esc_attr($_POST['adrotate_id']);
		if(isset($_POST['adrotate_schedulename'])) $name = esc_attr($_POST['adrotate_schedulename']);

		// Schedules
		$sday = $smonth = $syear = $shour = $sminute = '';
		if(isset($_POST['adrotate_sday'])) $sday = strip_tags(trim($_POST['adrotate_sday'], "\t\n "));
		if(isset($_POST['adrotate_smonth'])) $smonth = strip_tags(trim($_POST['adrotate_smonth'], "\t\n "));
		if(isset($_POST['adrotate_syear'])) $syear = strip_tags(trim($_POST['adrotate_syear'], "\t\n "));
		if(isset($_POST['adrotate_shour'])) $shour = strip_tags(trim($_POST['adrotate_shour'], "\t\n "));
		if(isset($_POST['adrotate_sminute'])) $sminute = strip_tags(trim($_POST['adrotate_sminute'], "\t\n "));

		$eday = $emonth = $eyear = $ehour = $eminute = '';
		if(isset($_POST['adrotate_eday'])) $eday = strip_tags(trim($_POST['adrotate_eday'], "\t\n "));
		if(isset($_POST['adrotate_emonth'])) $emonth = strip_tags(trim($_POST['adrotate_emonth'], "\t\n "));
		if(isset($_POST['adrotate_eyear'])) $eyear = strip_tags(trim($_POST['adrotate_eyear'], "\t\n "));
		if(isset($_POST['adrotate_ehour'])) $ehour = strip_tags(trim($_POST['adrotate_ehour'], "\t\n "));
		if(isset($_POST['adrotate_eminute'])) $eminute = strip_tags(trim($_POST['adrotate_eminute'], "\t\n "));
	
		$maxclicks = $maxshown = $spread = $dayimpressions = '';
		if(isset($_POST['adrotate_maxclicks'])) $maxclicks = strip_tags(trim($_POST['adrotate_maxclicks'], "\t\n "));
		if(isset($_POST['adrotate_maxshown'])) $maxshown = strip_tags(trim($_POST['adrotate_maxshown'], "\t\n "));	
		if(isset($_POST['adrotate_spread'])) $spread = strip_tags(trim($_POST['adrotate_spread'], "\t\n "));	

		$sdayhour = $sdayminute = $edayhour = $edayminute = $day_mon = $day_tue = $day_wed = $day_thu = $day_fri = $day_sat = $day_sun = '';
		if(isset($_POST['adrotate_sdayhour'])) $sdayhour = strip_tags(trim($_POST['adrotate_sdayhour'], "\t\n "));	
		if(isset($_POST['adrotate_sdayminute'])) $sdayminute = strip_tags(trim($_POST['adrotate_sdayminute'], "\t\n "));	
		if(isset($_POST['adrotate_edayhour'])) $edayhour = strip_tags(trim($_POST['adrotate_edayhour'], "\t\n "));	
		if(isset($_POST['adrotate_edayminute'])) $edayminute = strip_tags(trim($_POST['adrotate_edayminute'], "\t\n "));	
		if(isset($_POST['adrotate_mon'])) $day_mon = strip_tags(trim($_POST['adrotate_mon'], "\t\n "));	
		if(isset($_POST['adrotate_tue'])) $day_tue = strip_tags(trim($_POST['adrotate_tue'], "\t\n "));	
		if(isset($_POST['adrotate_wed'])) $day_wed = strip_tags(trim($_POST['adrotate_wed'], "\t\n "));	
		if(isset($_POST['adrotate_thu'])) $day_thu = strip_tags(trim($_POST['adrotate_thu'], "\t\n "));	
		if(isset($_POST['adrotate_fri'])) $day_fri = strip_tags(trim($_POST['adrotate_fri'], "\t\n "));	
		if(isset($_POST['adrotate_sat'])) $day_sat = strip_tags(trim($_POST['adrotate_sat'], "\t\n "));	
		if(isset($_POST['adrotate_sun'])) $day_sun = strip_tags(trim($_POST['adrotate_sun'], "\t\n "));	

		$ads = '';
		if(isset($_POST['adselect'])) $ads = $_POST['adselect'];
	
		if(current_user_can('adrotate_schedule_manage')) {	
			if(strlen($name) < 1) {
				$name = 'Schedule '.$id;
			}
	
			// Sort out start dates
			if(strlen($smonth) > 0 AND !is_numeric($smonth)) 	$smonth 	= date_i18n('m');
			if(strlen($sday) > 0 AND !is_numeric($sday)) 		$sday 		= date_i18n('d');
			if(strlen($syear) > 0 AND !is_numeric($syear)) 		$syear 		= date_i18n('Y');
			if(strlen($shour) > 0 AND !is_numeric($shour)) 		$shour 		= date_i18n('H');
			if(strlen($sminute) > 0 AND !is_numeric($sminute))	$sminute	= date_i18n('i');
			if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($shour) == 0) $shour = '00';
			if(($smonth > 0 AND $sday > 0 AND $syear > 0) AND strlen($sminute) == 0) $sminute = '00';
	
			if($smonth > 0 AND $sday > 0 AND $syear > 0) {
				$startdate = mktime($shour, $sminute, 0, $smonth, $sday, $syear);
			} else {
				$startdate = 0;
			}
			
			// Sort out end dates
			if(strlen($emonth) > 0 AND !is_numeric($emonth)) 	$emonth 	= $smonth;
			if(strlen($eday) > 0 AND !is_numeric($eday)) 		$eday 		= $sday;
			if(strlen($eyear) > 0 AND !is_numeric($eyear)) 		$eyear 		= $syear+1;
			if(strlen($ehour) > 0 AND !is_numeric($ehour)) 		$ehour 		= $shour;
			if(strlen($eminute) > 0 AND !is_numeric($eminute)) 	$eminute	= $sminute;
			if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($ehour) == 0) $ehour = '00';
			if(($emonth > 0 AND $eday > 0 AND $eyear > 0) AND strlen($eminute) == 0) $eminute = '00';
	
			if($emonth > 0 AND $eday > 0 AND $eyear > 0) {
				$enddate = mktime($ehour, $eminute, 0, $emonth, $eday, $eyear);
			} else {
				$enddate = 0;
			}
			
			// Enddate is too early, reset to default
			if($enddate <= $startdate) $enddate = $startdate + 7257600; // 84 days (12 weeks)
	
			// Sort out click and impressions restrictions
			if(strlen($maxclicks) < 1 OR !is_numeric($maxclicks)) $maxclicks = 0;
			if(strlen($maxshown) < 1 OR !is_numeric($maxshown))	$maxshown = 0;
			
			// Impression Spread
			if(isset($spread) AND strlen($spread) != 0 AND $maxshown > 0) {
				$spread = 'Y';
				$dayimpressions = round($maxshown/(($enddate - $startdate)/86400));
				if($dayimpressions == 0) $dayimpressions = 1;
			} else {
				$spread = 'N';
				$dayimpressions = 0;
			}

			// Day schedules
			if(!isset($sdayhour) AND $sdayhour < 1) $sdayhour = '00';
			if(!isset($sdayminute) AND $sdayminute < 1) $sdayminute = '00';
			$daystart = str_pad($sdayhour, 2, 0, STR_PAD_LEFT).str_pad($sdayminute, 2, 0, STR_PAD_LEFT);

			if(!isset($edayhour) AND $edayhour < 1) $edayhour = '00';
			if(!isset($edayminute) AND $edayminute < 1) $edayminute = '00';
			$daystop = str_pad($edayhour, 2, 0, STR_PAD_LEFT).str_pad($edayminute, 2, 0, STR_PAD_LEFT);

			if(isset($day_mon) AND strlen($day_mon) != 0) $day_mon = 'Y';
				else $day_mon = 'N';
			if(isset($day_tue) AND strlen($day_tue) != 0) $day_tue = 'Y';
				else $day_tue = 'N';
			if(isset($day_wed) AND strlen($day_wed) != 0) $day_wed = 'Y';
				else $day_wed = 'N';
			if(isset($day_thu) AND strlen($day_thu) != 0) $day_thu = 'Y';
				else $day_thu = 'N';
			if(isset($day_fri) AND strlen($day_fri) != 0) $day_fri = 'Y';
				else $day_fri = 'N';
			if(isset($day_sat) AND strlen($day_sat) != 0) $day_sat = 'Y';
				else $day_sat = 'N';
			if(isset($day_sun) AND strlen($day_sun) != 0) $day_sun = 'Y';
				else $day_sun = 'N';

			// Fetch records for the schedule
			$linkmeta = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `schedule` = %d AND `user` = 0;", $id));
			foreach($linkmeta as $meta) {
				$meta_array[] = $meta->ad;
			}
			
			if(empty($meta_array)) $meta_array = array();
			if(empty($ads)) $ads = array();

			// Add new ads to this schedule
			$insert = array_diff($ads, $meta_array);
			foreach($insert as &$value) {
				$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $value, 'group' => 0, 'user' => 0, 'schedule' => $id));
			}
			unset($value);
			
			// Remove ads from this schedule
			$delete = array_diff($meta_array, $ads);
			foreach($delete as &$value) {
				$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0 AND `schedule` = %d;", $value, $id)); 
			}
			unset($value);

			// Save the schedule to the DB
			$wpdb->update($wpdb->prefix.'adrotate_schedule', array('name' => $name, 'starttime' => $startdate, 'stoptime' => $enddate, 'maxclicks' => $maxclicks, 'maximpressions' => $maxshown, 'spread' => $spread, 'dayimpressions' => $dayimpressions, 'daystarttime' => $daystart, 'daystoptime' => $daystop, 'day_mon' => $day_mon, 'day_tue' => $day_tue, 'day_wed' => $day_wed, 'day_thu' => $day_thu, 'day_fri' => $day_fri, 'day_sat' => $day_sat, 'day_sun' => $day_sun), array('id' => $id));

			// Verify all ads
			adrotate_prepare_evaluate_ads(false);

			adrotate_return('adrotate-schedules', 217);
			exit;
		} else {
			adrotate_return('adrotate-schedules', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_insert_media

 Purpose:   Prepare input form on saving new or updated banners
 Receive:   -None-
 Return:	-None-
 Since:		0.1 
-------------------------------------------------------------*/
function adrotate_insert_media() {
	global $wpdb, $adrotate_config;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_save_media')) {
		if(current_user_can('adrotate_ad_manage')) {
			if($_FILES["adrotate_image"]["size"] > 0 AND $_FILES["adrotate_image"]["size"] <= 512000) {

				$allowedExts = array("jpg", "jpeg", "gif", "png", "flv", "swf", "html", "js");
				$filename = adrotate_sanitize_file_name($_FILES["adrotate_image"]["name"]);
				$extension = explode(".", $_FILES["adrotate_image"]["name"]);
				$extension = end($extension);
				$image_path = ABSPATH.$adrotate_config['banner_folder'];

				if(
					(
						//Images
						$_FILES["adrotate_image"]["type"] == "image/gif"
						OR $_FILES["adrotate_image"]["type"] == "image/jpeg" 
						OR $_FILES["adrotate_image"]["type"] == "image/pjpeg"
						OR $_FILES["adrotate_image"]["type"] == "image/jpg" 
						OR $_FILES["adrotate_image"]["type"] == "image/png"
						
						// Flash :(
						OR $_FILES["adrotate_image"]["type"] == "application/x-shockwave-flash"
						
						// HTML5 Assets
						OR $_FILES["adrotate_image"]["type"] == "text/html"
						OR $_FILES["adrotate_image"]["type"] == "application/x-javascript"
						OR $_FILES["adrotate_image"]["type"] == "application/javascript"
						OR $_FILES["adrotate_image"]["type"] == "text/javascript"
					)
					AND in_array($extension, $allowedExts)
				) {
					if ($_FILES["adrotate_image"]["error"] > 0) {
						if($_FILES["adrotate_image"]["error"] == 1 OR $_FILES["adrotate_image"]["error"] == 2) $errorcode = 511;
						else if($_FILES["adrotate_image"]["error"] == 3) $errorcode = 506;
						else if($_FILES["adrotate_image"]["error"] == 4) $errorcode = 506;
						else if($_FILES["adrotate_image"]["error"] == 6 OR $_FILES["adrotate_image"]["error"] == 7) $errorcode = 506;
						else $errorcode = '';
						adrotate_return('adrotate-media', $errorcode);
					} else {
						move_uploaded_file($_FILES["adrotate_image"]["tmp_name"], $image_path . $filename);
					}
				} else {
					adrotate_return('adrotate-media', 510);
				}
			} else {
				adrotate_return('adrotate-media', 511);
			}
	
			adrotate_return('adrotate-media', 202);
		} else {
			adrotate_return('adrotate-media', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_request_action

 Purpose:   Prepare action for banner or group from database
 Receive:   -none-
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_request_action() {
	global $adrotate_config;

	$banner_ids = $group_ids = $schedule_ids = '';

	if(wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_active') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_disable') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_error') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_queue') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_ads_reject') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_groups') 
	OR wp_verify_nonce($_POST['adrotate_nonce'],'adrotate_bulk_schedules')) {
		$banner_ids = $group_ids = $schedule_ids = '';
		if(!empty($_POST['adrotate_id'])) $banner_ids = array($_POST['adrotate_id']);
		if(!empty($_POST['bannercheck'])) $banner_ids = $_POST['bannercheck'];
		if(!empty($_POST['rejectcheck'])) $banner_ids = $_POST['rejectcheck'];
		if(!empty($_POST['queuecheck'])) $banner_ids = $_POST['queuecheck'];
		if(!empty($_POST['disabledbannercheck'])) $banner_ids = $_POST['disabledbannercheck'];
		if(!empty($_POST['errorbannercheck'])) $banner_ids = $_POST['errorbannercheck'];
		if(!empty($_POST['groupcheck'])) $group_ids = $_POST['groupcheck'];
		if(!empty($_POST['schedulecheck'])) $schedule_ids = $_POST['schedulecheck'];
		
		// Determine which kind of action to use
		if(!empty($_POST['adrotate_action'])) {
			// Default action call
			$actions = $_POST['adrotate_action'];
		} else if(!empty($_POST['adrotate_queue_action'])) {
			// Queued ads listing call
			$actions = $_POST['adrotate_queue_action'];
		} else if(!empty($_POST['adrotate_reject_action'])) {
			// Rejected ads listing call
			$actions = $_POST['adrotate_reject_action'];
		} else if(!empty($_POST['adrotate_disabled_action'])) {
			// Disabled ads listing call
			$actions = $_POST['adrotate_disabled_action'];
		} else if(!empty($_POST['adrotate_error_action'])) {
			// Erroneous ads listing call
			$actions = $_POST['adrotate_error_action'];
		}
		if(preg_match("/-/", $actions)) {
			list($action, $specific) = explode("-", $actions);	
		} else {
		   	$action = $actions;
		}

		if($banner_ids != '') {
			$return = 'adrotate-ads';
			if($action == 'export') {
				if(current_user_can('adrotate_moderate')) {
					adrotate_export($banner_ids, $specific);
					$result_id = 215;
				} else {
					adrotate_return($return, 500);
				}
			}
			foreach($banner_ids as $banner_id) {
				if($action == 'duplicate') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_duplicate($banner_id);
						$result_id = 219;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'deactivate') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_active($banner_id, 'deactivate');
						$result_id = 210;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'activate') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_active($banner_id, 'activate');
						$result_id = 211;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'delete') {
					if(current_user_can('adrotate_ad_delete')) {
						adrotate_delete($banner_id, 'banner');
						$result_id = 203;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'reset') {
					if(current_user_can('adrotate_ad_delete')) {
						adrotate_reset($banner_id);
						$result_id = 208;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'renew') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_renew($banner_id, $specific);
						$result_id = 209;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'weight') {
					if(current_user_can('adrotate_ad_manage')) {
						adrotate_weight($banner_id, $specific);
						$result_id = 214;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'approve') {
					if(current_user_can('adrotate_moderate_approve')) {
						adrotate_approve($banner_id);
						$return = 'adrotate-moderate';
						$result_id = 304;
					} else {
						adrotate_return('adrotate-moderate', 500);
					}
				}
				if($action == 'reject') {
					if(current_user_can('adrotate_moderate')) {
						adrotate_reject($banner_id);
						$return = 'adrotate-moderate';
						$result_id = 305;
					} else {
						adrotate_return('adrotate-moderate', 500);
					}
				}
				if($action == 'queue') {
					if(current_user_can('adrotate_moderate')) {
						adrotate_queue($banner_id);
						$return = 'adrotate-moderate';
						$result_id = 306;
					} else {
						adrotate_return('adrotate-moderate', 500);
					}
				}
			}
			adrotate_prepare_evaluate_ads(false);
		}
		
		if($group_ids != '') {
			$return = 'adrotate-groups';
			foreach($group_ids as $group_id) {
				if($action == 'group_delete') {
					if(current_user_can('adrotate_group_delete')) {
						adrotate_delete($group_id, 'group');
						$result_id = 204;
					} else {
						adrotate_return($return, 500);
					}
				}
				if($action == 'group_delete_banners') {
					if(current_user_can('adrotate_group_delete')) {
						adrotate_delete($group_id, 'bannergroup');
						$result_id = 213;
					} else {
						adrotate_return($return, 500);
					}
				}
			}
		}
	
		if($schedule_ids != '') {
			$return = 'adrotate-schedules';
			foreach($schedule_ids as $schedule_id) {
				if($action == 'schedule_delete') {
					if(current_user_can('adrotate_schedule_delete')) {
						adrotate_delete($schedule_id, 'schedule');
						$result_id = 218;
					} else {
						adrotate_return($return, 500);
					}
				}
			}
		}
		
		adrotate_return($return, $result_id);
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_duplicate

 Purpose:   Duplicate a banner
 Receive:   $id
 Return:    -none-
 Since:		3.17
-------------------------------------------------------------*/
function adrotate_duplicate($id) {
	global $wpdb;

	if($id > 0) {
		$thetime = adrotate_now();

		$duplicate_id = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}adrotate` WHERE `id` = {$id};");

		$wpdb->insert($wpdb->prefix.'adrotate', array('title' => $duplicate_id->title.' (Copy of #'.$id.')', 'bannercode' => $duplicate_id->bannercode, 'thetime' => $thetime, 'updated' => $thetime, 'author' => $duplicate_id->author, 'imagetype' => $duplicate_id->imagetype, 'image' => $duplicate_id->image, 'link' => $duplicate_id->link, 'tracker' => $duplicate_id->tracker, 'mobile' => $duplicate_id->mobile, 'tablet' => $duplicate_id->tablet, 'responsive' => $duplicate_id->responsive, 'type' => $duplicate_id->type, 'weight' => $duplicate_id->weight, 'sortorder' => $duplicate_id->sortorder, 'budget' => $duplicate_id->budget, 'crate' => $duplicate_id->crate, 'irate' => $duplicate_id->irate, 'cities' => $duplicate_id->cities, 'countries' => $duplicate_id->countries));

		$new_id = $wpdb->insert_id;

		$duplicate_meta = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = {$id};");
		foreach($duplicate_meta as $meta) {
			$schedule = ($meta->schedule > 0) ? $meta->schedule : 0;
			$group = ($meta->group > 0) ? $meta->group : 0;
			$user = ($meta->user > 0) ? $meta->user : 0;
			$wpdb->insert("{$wpdb->prefix}adrotate_linkmeta", array('ad' => $new_id, 'group' => $group, 'user' => $user, 'schedule' => $schedule));
			unset($schedule, $user, $user);
		}
	}
}


/*-------------------------------------------------------------
 Name:      adrotate_delete

 Purpose:   Remove banner or group from database
 Receive:   $id, $what
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_delete($id, $what) {
	global $wpdb;

	if($id > 0) {
		if($what == 'banner') {
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_stats` WHERE `ad` = %d;", $id));
		} else if ($what == 'group') {
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = %d;", $id));
		} else if ($what == 'schedule') {
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_schedule` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `schedule` = %d;", $id));
		} else if ($what == 'bannergroup') {
			$linkmeta = $wpdb->get_results($wpdb->prepare("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = %d AND `user` = '0' AND `schedule` = '0';", $id));
			foreach($linkmeta as $meta) {
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate` WHERE `id` = ".$meta->ad.";");
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_stats` WHERE `ad` = ".$meta->ad.";");
				$wpdb->query("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = ".$meta->ad.";");
			}
			unset($linkmeta);
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = %d;", $id));
			$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_stats` WHERE `group` = %d;", $id)); // Perhaps unnessesary
		}
		adrotate_prepare_evaluate_ads(false);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_active

 Purpose:   Activate or Deactivate a banner
 Receive:   $id, $what
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_active($id, $what) {
	global $wpdb;

	if($id > 0) {
		if($what == 'deactivate') {
			$wpdb->update($wpdb->prefix.'adrotate', array('type' => 'disabled'), array('id' => $id));
		}
		if ($what == 'activate') {
			// Determine status of ad 
			$adstate = adrotate_evaluate_ad($id);
			if($adstate == 'error' OR $adstate == 'expired') $adtype = 'error';
				else $adtype = 'active';
			$wpdb->update($wpdb->prefix.'adrotate', array('type' => $adtype), array('id' => $id));
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_reset

 Purpose:   Reset statistics for a banner
 Receive:   $id
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_reset($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_stats` WHERE `ad` = %d", $id));
		$wpdb->query($wpdb->prepare("DELETE FROM `".$wpdb->prefix."adrotate_tracker` WHERE `bannerid` = %d", $id));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_renew

 Purpose:   Renew the end date of a banner with a new schedule starting where the last ended
 Receive:   $id, $howlong
 Return:    -none-
 Since:		2.2
-------------------------------------------------------------*/
function adrotate_renew($id, $howlong = 2592000) {
	global $wpdb;

	if($id > 0) {
		$schedule_id = $wpdb->get_var($wpdb->prepare("SELECT `schedule` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = 0 ORDER BY `id` DESC LIMIT 1;", $id)); 
		if($schedule_id > 0) {
			$starttime = $wpdb->get_row($wpdb->prepare("SELECT `id`, `stoptime` FROM `".$wpdb->prefix."adrotate_schedule` WHERE `id` = %d ORDER BY `id` DESC LIMIT 1;", $schedule_id));
			$stoptime = $starttime->stoptime + $howlong;
			$wpdb->insert($wpdb->prefix.'adrotate_schedule', array('name' => 'Schedule for ad '.$id, 'starttime' => $starttime->stoptime, 'stoptime' => $stoptime, 'maxclicks' => 0, 'maximpressions' => 0));
		} else {
			$now = adrotate_now();
			$stoptime = $now + $howlong;
			$wpdb->insert($wpdb->prefix.'adrotate_schedule', array('name' => 'Schedule for ad '.$id, 'starttime' => $now, 'stoptime' => $stoptime, 'maxclicks' => 0, 'maximpressions' => 0));
		}
		$wpdb->insert($wpdb->prefix.'adrotate_linkmeta', array('ad' => $id, 'group' => 0, 'user' => 0, 'schedule' => $wpdb->insert_id));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_weight

 Purpose:   Renew the end date of a banner
 Receive:   $id, $weight
 Return:    -none-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_weight($id, $weight = 6) {
	global $wpdb;

	if($id > 0) {
		$wpdb->update($wpdb->prefix.'adrotate', array('weight' => $weight), array('id' => $id));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_approve

 Purpose:   Approve a queued banner
 Receive:   $id
 Return:    -none-
 Since:		3.8.4
-------------------------------------------------------------*/
function adrotate_approve($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->update($wpdb->prefix.'adrotate', array('type' => 'active'), array('id' => $id));
		adrotate_push_notifications('approved', $id);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_reject

 Purpose:   Reject a queued banner
 Receive:   $id
 Return:    -none-
 Since:		3.8.4
-------------------------------------------------------------*/
function adrotate_reject($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->update($wpdb->prefix.'adrotate', array('type' => 'reject'), array('id' => $id));
		adrotate_push_notifications('rejected', $id);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_queue

 Purpose:   Queue a rejected banner
 Receive:   $id
 Return:    -none-
 Since:		3.8.4
-------------------------------------------------------------*/
function adrotate_queue($id) {
	global $wpdb;

	if($id > 0) {
		$wpdb->update($wpdb->prefix.'adrotate', array('type' => 'queue'), array('id' => $id));
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_export

 Purpose:   Export selected banners
 Receive:   $id
 Return:    -none-
 Since:		3.8.5
-------------------------------------------------------------*/
function adrotate_export($ids, $format) {
	if(is_array($ids)) {
		adrotate_export_ads($ids, $format);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_options_submit

 Purpose:   Save options from dashboard
 Receive:   $_POST
 Return:    -none-
 Since:		0.1
-------------------------------------------------------------*/
function adrotate_options_submit() {
	if(wp_verify_nonce($_POST['adrotate_nonce_settings'],'adrotate_settings')) {

		$settings_tab = esc_attr($_POST['adrotate_settings_tab']);

		if($settings_tab == 'general') {  
			$config = get_option('adrotate_config');

			$config['textwidget_shortcodes'] = (isset($_POST['adrotate_textwidget_shortcodes'])) ? 'Y' : 'N';
			$config['jquery'] = (isset($_POST['adrotate_jquery'])) ? 'Y' : 'N';
			$config['jsfooter'] = (isset($_POST['adrotate_jsfooter'])) ? 'Y' : 'N';
			$adblock_disguise = strtolower(trim($_POST['adrotate_adblock_disguise']));
			$config['adblock_disguise'] = (strlen($adblock_disguise) > 0) ? preg_replace('/[^a-z]/', '', strtolower(substr($adblock_disguise, 0, 6))) : "";
			$banner_folder = strtolower(trim($_POST['adrotate_banner_folder']));
			$config['banner_folder'] = (strlen($banner_folder) > 0) ? preg_replace('/[^a-zA-Z0-9\/\-_]/', '', $banner_folder) : "wp-content/banners/";
			$config['adblock'] = (isset($_POST['adrotate_adblock'])) ? 'Y' : 'N';	
			$config['adblock_loggedin'] = (isset($_POST['adrotate_adblock_loggedin'])) ? 'Y' : 'N';
			$adblock_timer = trim($_POST['adrotate_adblock_timer']);
			$config['adblock_timer'] = (strlen($adblock_timer) > 0 AND (is_numeric($adblock_timer) AND $adblock_timer >= 1 AND $adblock_timer <= 20)) ? $adblock_timer : 5;
			$adblock_message = trim($_POST['adrotate_adblock_message']);
			$config['adblock_message'] = (strlen($adblock_message) > 0) ? strip_tags(htmlspecialchars(trim($adblock_message, "\t\n "), ENT_QUOTES)) : "Ad blocker detected! Please wait %time% seconds or disable your ad blocker!";
			update_option('adrotate_config', $config);

			// Sort out crawlers
			$crawlers = explode(',', trim($_POST['adrotate_crawlers']));
			$new_crawlers = array();
			foreach($crawlers as $crawler) {
				$crawler = preg_replace('/[^a-zA-Z0-9\[\]\-_:; ]/i', '', trim($crawler));
				if(strlen($crawler) > 0) $new_crawlers[] = $crawler;
			}
			update_option('adrotate_crawlers', $new_crawlers);
		}

		if($settings_tab == 'notifications') {  
			$notifications = get_option('adrotate_notifications');

			// Notifications
			$notifications['notification_email'] = (isset($_POST['adrotate_notification_email'])) ? 'Y' : 'N';
			$notifications['notification_push'] = (isset($_POST['adrotate_notification_push'])) ? 'Y' : 'N';
			$notifications['notification_dashboard'] = (isset($_POST['adrotate_notification_dashboard'])) ? 'N' : 'Y';

			// Filter and validate notification addresses, if not set, turn option off.
			$notification_emails = $_POST['adrotate_notification_email_publisher'];
			if(strlen($notification_emails) > 0) {
				$notification_emails = explode(',', trim($notification_emails));
				foreach($notification_emails as $notification_email) {
					$notification_email = trim($notification_email);
					if(strlen($notification_email) > 0) {
		  				if(is_email($notification_email) ) {
							$clean_notification_email[] = $notification_email;
						}
					}
				}
				$notifications['notification_email_publisher'] = array_unique(array_slice($clean_notification_email, 0, 5));
			} else {
				$notifications['notification_email_publisher'] = array();
			}

			// Filter and validate advertiser addresses
			$advertiser_emails = $_POST['adrotate_notification_email_advertiser'];
			if(strlen($advertiser_emails) > 0) {
				$advertiser_emails = explode(',', trim($advertiser_emails));
				foreach($advertiser_emails as $advertiser_email) {
					$advertiser_email = trim($advertiser_email);
					if(strlen($advertiser_email) > 0) {
		  				if(is_email($advertiser_email) ) {
							$clean_advertiser_email[] = $advertiser_email;
						}
					}
				}
				$notifications['notification_email_advertiser'] = array_unique(array_slice($clean_advertiser_email, 0, 2));
			} else {
				$notifications['notification_email_advertiser'] = array(get_option('admin_email'));
			}
	
			// Push Notifications
			$notifications['notification_push_geo'] = (isset($_POST['adrotate_notification_push_geo'])) ? 'Y' : 'N';
			$notifications['notification_push_status'] = (isset($_POST['adrotate_notification_push_status'])) ? 'Y' : 'N';
			$notifications['notification_push_queue'] = (isset($_POST['adrotate_notification_push_queue'])) ? 'Y' : 'N';
			$notifications['notification_push_approved'] = (isset($_POST['adrotate_notification_push_approved'])) ? 'Y' : 'N';
			$notifications['notification_push_rejected'] = (isset($_POST['adrotate_notification_push_rejected'])) ? 'Y' : 'N';
		
			$notifications['notification_push_user'] = (strlen($_POST['adrotate_notification_push_user']) > 0) ? preg_replace('/[^a-z0-9.]+/i', '', trim(esc_attr($_POST['adrotate_notification_push_user']))) : '';		
			$notifications['notification_push_api'] = (strlen($_POST['adrotate_notification_push_api']) > 0) ? preg_replace('/[^a-z0-9.]+/i', '', trim(esc_attr($_POST['adrotate_notification_push_api']))) : '';
		
			update_option('adrotate_notifications', $notifications);
		}

		if($settings_tab == 'stats') {  
			$config = get_option('adrotate_config');

			$stats = trim($_POST['adrotate_stats']);
			$config['stats'] = (is_numeric($stats) AND $stats >= 0 AND $stats <= 3) ? $stats : 1;
			$config['enable_loggedin_impressions'] = (isset($_POST['adrotate_enable_loggedin_impressions'])) ? 'Y' : 'N';
			$config['enable_loggedin_clicks'] = (isset($_POST['adrotate_enable_loggedin_clicks'])) ? 'Y' : 'N';
			$impression_timer = trim($_POST['adrotate_impression_timer']);
			$config['impression_timer'] = (is_numeric($impression_timer) AND $impression_timer >= 10 AND $impression_timer <= 3600) ? $impression_timer : 60;
			$click_timer = trim($_POST['adrotate_click_timer']);
			$config['click_timer'] = (is_numeric($click_timer) AND $click_timer >= 60 AND $click_timer <= 86400) ? $click_timer : 86400;
	
			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'geo') {  
			$config = get_option('adrotate_config');

			$geo = trim($_POST['adrotate_enable_geo']);
			$config['enable_geo'] = (is_numeric($geo) AND $geo >= 0 AND $geo <= 5) ? $geo : 0;
			$geo_cookie = trim($_POST['adrotate_geo_cookie_life']);
			$config['geo_cookie_life'] = (is_numeric($geo_cookie)) ? $geo_cookie : 86400;
			$geo_email = trim($_POST['adrotate_geo_email']);
			$config['geo_email'] = (strlen($geo_email) > 0) ? $geo_email : '';
			$geo_pass = trim($_POST['adrotate_geo_pass']);
			$config['geo_pass'] = (strlen($geo_pass) > 0) ? $geo_pass : '';

			// Try to update the Geo Cookie for Admin
			if($config['enable_geo'] > 0) adrotate_geolocation();

			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'advertisers') {  
			$config = get_option('adrotate_config');

			$config['enable_advertisers'] = (isset($_POST['adrotate_enable_advertisers'])) ? 'Y' : 'N';
			$config['enable_editing'] = (isset($_POST['adrotate_enable_editing'])) ? 'Y' : 'N';
			$config['enable_geo_advertisers'] = (isset($_POST['adrotate_enable_geo_advertisers'])) ? 1 : 0;
			if(isset($_POST['adrotate_role'])) { adrotate_prepare_roles('add'); } else { adrotate_prepare_roles('remove'); }

			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'roles') {
			$config = get_option('adrotate_config');

			adrotate_set_capability($_POST['adrotate_advertiser'], "adrotate_advertiser");
			adrotate_set_capability($_POST['adrotate_global_report'], "adrotate_global_report");
			adrotate_set_capability($_POST['adrotate_ad_manage'], "adrotate_ad_manage");
			adrotate_set_capability($_POST['adrotate_ad_delete'], "adrotate_ad_delete");
			adrotate_set_capability($_POST['adrotate_group_manage'], "adrotate_group_manage");
			adrotate_set_capability($_POST['adrotate_group_delete'], "adrotate_group_delete");
			adrotate_set_capability($_POST['adrotate_schedule_manage'], "adrotate_schedule_manage");
			adrotate_set_capability($_POST['adrotate_schedule_delete'], "adrotate_schedule_delete");
			adrotate_set_capability($_POST['adrotate_moderate'], "adrotate_moderate");
			adrotate_set_capability($_POST['adrotate_moderate_approve'], "adrotate_moderate_approve");
			$config['advertiser'] = $_POST['adrotate_advertiser'];
			$config['global_report'] = $_POST['adrotate_global_report'];
			$config['ad_manage'] = $_POST['adrotate_ad_manage'];
			$config['ad_delete'] = $_POST['adrotate_ad_delete'];
			$config['group_manage'] = $_POST['adrotate_group_manage'];
			$config['group_delete'] = $_POST['adrotate_group_delete'];
			$config['schedule_manage'] = $_POST['adrotate_schedule_manage'];
			$config['schedule_delete'] = $_POST['adrotate_schedule_delete'];
			$config['moderate'] = $_POST['adrotate_moderate'];
			$config['moderate_approve']	= $_POST['adrotate_moderate_approve'];

			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'misc') {  
			$config = get_option('adrotate_config');

			$config['widgetalign'] = (isset($_POST['adrotate_widgetalign'])) ? 'Y' : 'N';
			$config['widgetpadding'] = (isset($_POST['adrotate_widgetpadding'])) ? 'Y' : 'N';
			$config['adminbar'] = (isset($_POST['adrotate_adminbar'])) ? 'Y' : 'N';
			$config['hide_schedules'] = (isset($_POST['adrotate_hide_schedules'])) ? 'Y' : 'N';
			$config['w3caching'] = (isset($_POST['adrotate_w3caching'])) ? 'Y' : 'N';
	
			update_option('adrotate_config', $config);
		}

		if($settings_tab == 'maintenance') {  
			$debug = get_option('adrotate_debug');

			$debug['general'] = (isset($_POST['adrotate_debug'])) ? true : false;
			$debug['publisher'] = (isset($_POST['adrotate_debug_publisher'])) ? true : false;
			$debug['advertiser'] = (isset($_POST['adrotate_debug_advertiser'])) ? true : false;
			$debug['geo'] = (isset($_POST['adrotate_debug_geo'])) ? true : false;
			$debug['timers'] = (isset($_POST['adrotate_debug_timers'])) ? true : false;
			$debug['track'] = (isset($_POST['adrotate_debug_track'])) ? true : false;

			update_option('adrotate_debug', $debug);
		}
	
		// Return to dashboard
		adrotate_return('adrotate-settings', 400, array('tab' => $settings_tab));
	} else {
		adrotate_nonce_error();
		exit;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_roles

 Purpose:   Prepare user roles for WordPress
 Receive:   $action
 Return:    -None-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_prepare_roles($action) {
	
	if($action == 'add') {
		add_role('adrotate_advertiser', __('AdRotate Advertiser', 'adrotate'), array('read' => 1));		
	} 
	if($action == 'remove') {
		remove_role('adrotate_advertiser');
	} 
}
?>