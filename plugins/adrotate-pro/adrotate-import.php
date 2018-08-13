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
 Name:      adrotate_import_ads

 Purpose:   Import adverts from file
 Receive:   -None-
 Return:	-None-
 Since:		3.11
-------------------------------------------------------------*/
function adrotate_import_ads() {
	global $wpdb, $current_user, $userdata;

	if(wp_verify_nonce($_POST['adrotate_nonce'], 'adrotate_import')) {
		if(current_user_can('adrotate_ad_manage')) {	
			if($_FILES["adrotate_file"]["error"] == 4) {
				adrotate_return('adrotate-ads', 506, array('view' => 'import'));
				exit;
			} else if ($_FILES["adrotate_file"]["error"] > 0) {
				adrotate_return('adrotate-ads', 507, array('view' => 'import'));
				exit;
			} else if($_FILES["adrotate_file"]["size"] > 4096000) {
				adrotate_return('adrotate-ads', 511, array('view' => 'import'));
				exit;
			} else {
				$now = adrotate_now();
				$ad_fields = array('title', 'bannercode', 'thetime', 'updated', 'author', 'imagetype', 'image', 'link', 'tracker', 'responsive', 'type', 'weight', 'sortorder', 'budget', 'crate', 'irate', 'cities', 'countries');
	
				if($_FILES["adrotate_file"]["type"] == "text/xml" OR $_FILES["adrotate_file"]["type"] == "application/xml" OR $_FILES["adrotate_file"]["type"] == "application/x-xml") {
					$xml_name = "adrotate_import_".date_i18n("mdYHi", $now).".xml";
					move_uploaded_file($_FILES["adrotate_file"]["tmp_name"], WP_CONTENT_DIR."/reports/".$xml_name);
					$file = WP_CONTENT_URL."/reports/".$xml_name;
			
					$xml = simplexml_load_file($file);
					foreach($xml->xpath('advert') as $advert) {
						$ad = array(
							'title' => strip_tags(htmlspecialchars(trim($advert->title, "\t\n "), ENT_QUOTES)),
							'bannercode' => htmlspecialchars(trim($advert->bannercode, "\t\n "), ENT_QUOTES),
							'thetime' => $now,
							'updated' => $now,
							'author' => $current_user->user_login,
							'imagetype' => strip_tags(trim($advert->imagetype, "\t\n ")),
							'image' => strip_tags(trim($advert->image, "\t\n ")),
							'link' => strip_tags(trim($advert->link, "\t\n ")),
							'tracker' => strip_tags(trim($advert->tracker, "\t\n ")),
							'mobile' => strip_tags(trim($advert->mobile, "\t\n ")),
							'tablet' => strip_tags(trim($advert->tablet, "\t\n ")),
							'responsive' => strip_tags(trim($advert->responsive, "\t\n ")),
							'type' => 'import',
							'weight' => strip_tags(trim($advert->weight, "\t\n ")),
							'sortorder' => 0,
							'budget' => strip_tags(trim($advert->budget, "\t\n ")),
							'crate' => strip_tags(trim($advert->crate, "\t\n ")),
							'irate' => strip_tags(trim($advert->irate, "\t\n ")),
							'cities' => serialize(explode(',', strip_tags(trim($advert->cities, "\t\n ")))),
							'countries' => serialize(explode(',', strip_tags(trim($advert->countries, "\t\n ")))),
						);
						$wpdb->insert($wpdb->prefix."adrotate", $ad);

						$ad_id = $wpdb->insert_id;
						$schedule = array(
							'name' => 'Schedule for advert '.$ad_id,
							'starttime' => strip_tags(trim($advert->start, "\t\n ")),
							'stoptime' => strip_tags(trim($advert->end, "\t\n ")),
							'maxclicks' => 0,
							'maximpressions' => 0,
							'spread' => 'N',
							'dayimpressions' => 0,
						);
						$wpdb->insert($wpdb->prefix."adrotate_schedule", $schedule);

						$schedule_id = $wpdb->insert_id;
						$linkmeta = array(
							'ad' => $ad_id,
							'group' => 0,
							'user' => 0,
							'schedule' => $schedule_id,
						);
						$wpdb->insert($wpdb->prefix."adrotate_linkmeta", $linkmeta);
						
						unset($advert, $ad, $ad_id, $schedule, $schedule_id, $linkmeta);
					}
				} 
				adrotate_prepare_evaluate_ads(false);
			
				// return to dashboard
				adrotate_return('adrotate-ads', 216);
				exit;
			}
		} else {
			adrotate_return('adrotate-ads', 500);
		}
	} else {
		adrotate_nonce_error();
		exit;
	}
}
?>