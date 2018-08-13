<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */
?>

<?php if(!$ad_edit_id) {
	// Initial date for new entries
	list($sday, $smonth, $syear) = explode(" ", date("d m Y", $now));
	list($eday, $emonth, $eyear) = explode(" ", date("d m Y", $in84days));
	$shour = $ehour = $sminute = $eminute = '00';

	$edit_id = $wpdb->get_var("SELECT `id` FROM `{$wpdb->prefix}adrotate` WHERE `type` = 'a_empty' AND 'author' = '{$current_user->user_login}' ORDER BY `id` DESC LIMIT 1;");
	if($edit_id == 0) {
	    $wpdb->insert("{$wpdb->prefix}adrotate", array('title' => '', 'bannercode' => htmlspecialchars('<a href="http://www.example.com"><img src="%image%" /></a>', ENT_QUOTES), 'thetime' => $now, 'updated' => $now, 'author' => $current_user->user_login, 'imagetype' => 'dropdown', 'image' => '', 'link' => '', 'tracker' => 'Y', 'mobile' => 'N', 'tablet' => 'N', 'responsive' => 'N', 'type' => 'a_empty', 'weight' => 6, 'sortorder' => 0, 'budget' => 0, 'crate' => 0, 'irate' => 0, 'cities' => serialize(array()), 'countries' => serialize(array())));
	    $edit_id = $wpdb->insert_id;
	    $wpdb->insert("{$wpdb->prefix}adrotate_linkmeta", array('ad' => $edit_id, 'group' => 0, 'user' => $current_user->ID, 'schedule' => 0));
	}
	$ad_edit_id = $edit_id;
}

if($adrotate_config['enable_editing'] == 'Y') {
	$edit_banner = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}adrotate` WHERE `id` = '$ad_edit_id';");
	$advertiser	= $wpdb->get_var($wpdb->prepare("SELECT `ad` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = %d AND `group` = 0 AND `user` = %d AND `schedule` = 0 ORDER BY `ad` ASC;", $ad_edit_id, $current_user->ID));
	$groups	= $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' ORDER BY `sortorder` ASC, `id` ASC;"); 
	$schedules = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}adrotate_schedule` WHERE `name` != '' AND `stoptime` > $now ORDER BY `id` ASC;");
	$linkmeta = $wpdb->get_results("SELECT `group` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '$ad_edit_id' AND `user` = 0 AND `schedule` = 0;");
	$schedulemeta = $wpdb->get_results("SELECT `schedule` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '$ad_edit_id' AND `group` = 0 AND `user` = 0;");
	$class = '';

	$wpnonceaction = 'adrotate_email_advertiser_'.$edit_banner->id;
	$nonce = wp_create_nonce($wpnonceaction);

	$meta_array = $schedule_array = '';
	foreach($linkmeta as $meta) {
		$meta_array[] = $meta->group;
		unset($meta);
	}

	foreach($schedulemeta as $meta) {
		$schedule_array[] = $meta->schedule;
		unset($meta);	
	}
	
	if(!is_array($meta_array)) $meta_array = array();
	if(!is_array($schedule_array)) $schedule_array = array();

	if($edit_banner->id == $advertiser) {
		if($ad_edit_id AND $edit_banner->type != 'a_empty') {
			// Errors
			if($edit_banner->tracker == 'N') 
				echo '<div class="error"><p>'. __("Please contact staff, click tracking is not active!", 'adrotate').'</p></div>';

			if(!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image != '') 
				echo '<div class="error"><p>'. __('You didn\'t use %image% in your AdCode but did select an image!', 'adrotate') .' '. __("Please contact staff if you don't know what this means.", 'adrotate').'</p></div>';
	
			if(preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image == '') 
				echo '<div class="error"><p>'. __('You did use %image% in your AdCode but did not select an image!', 'adrotate') .' '. __("Please contact staff if you don't know what this means.", 'adrotate').'</p></div>';
			
			if(count($schedule_array) == 0) 
				echo '<div class="error"><p>'. __("Please contact staff, your advert is not being displayed!", 'adrotate').'</p></div>';
			
			if(!preg_match_all('/<(a|script|embed|iframe)[^>](.*?)>/i', stripslashes(htmlspecialchars_decode($edit_banner->bannercode, ENT_QUOTES)), $things) AND $edit_banner->tracker == 'Y')
				echo '<div class="error"><p>'. __("Clicktracking is enabled but no valid link/tag was found in the adcode!", 'adrotate') .' '. __("Correct your adcode or contact staff if you don't know what this means.", 'adrotate').'</p></div>';

			if($edit_banner->tracker == 'N' AND $edit_banner->crate > 0)
				echo '<div class="error"><p>'. __("Please contact staff, a Click rate was set but clicktracking is not active!", 'adrotate').'</p></div>';

			// Ad Notices
			$adstate = adrotate_evaluate_ad($edit_banner->id);
			if($edit_banner->type == 'reject')
				echo '<div class="error"><p>'. __('This advert has been rejected by staff Please adjust the ad to conform with the requirements!', 'adrotate').'</p></div>';
	
			if($edit_banner->type == 'queue')
				echo '<div class="error"><p>'. __('This advert is queued and awaiting review!', 'adrotate').'</p></div>';
	
			if($edit_banner->type == 'error' AND $adstate == 'normal')
				echo '<div class="error"><p>'. __('AdRotate can not find an error but the ad is marked erroneous, try re-saving the ad!', 'adrotate').'</p></div>';
	
			if($adstate == 'expires7days')
				echo '<div class="updated"><p>'. __('This ad will expire in less than 7 days!', 'adrotate').'</p></div>';
	
			if($adstate == 'expires2days')
				echo '<div class="updated"><p>'. __('The ad will expire in less than 2 days!', 'adrotate').'</p></div>';
	
			if($adstate == 'expired')
				echo '<div class="error"><p>'. __('This ad is expired and currently not rotating!', 'adrotate').'</p></div>';
	
			if($edit_banner->type == 'disabled') 
				echo '<div class="updated"><p>'. __('This ad has been disabled and is not rotating!', 'adrotate').'</p></div>';

			if($edit_banner->type == 'active') 
				echo '<div class="updated"><p>'. __('This advert is approved and currently showing on the site! Saving the advert now will put it in the moderation queue for review!', 'adrotate').'</p></div>';
		}
		
		$image = str_replace('%folder%', $adrotate_config['banner_folder'], $edit_banner->image); 
		$image = basename($image);
		?>

		<!-- AdRotate JS -->
	<script type="text/javascript">
	jQuery(document).ready(function(){
	    function livePreview(){
	        var input = jQuery("#adrotate_bannercode").val();
	        if(jQuery("#adrotate_title").val().length > 0) var ad_title = jQuery("#adrotate_title").val();
	        var ad_image = '';
	        if(jQuery("#adrotate_image_current").val().length > 0) var ad_image = '<?php echo site_url('/').$adrotate_config['banner_folder']; ?>'+jQuery("#adrotate_image_current").val();
	
	        var input = input.replace(/%id%/g, <?php echo $edit_banner->id;?>);
	        var input = input.replace(/%title%/g, ad_title);
	        var input = input.replace(/%image%/g, ad_image);
	        var input = input.replace(/%random%/g, <?php echo rand(100000,999999); ?>);
	        jQuery("#adrotate_preview").html(input);
	    }       
	    livePreview();
	
	    jQuery('#adrotate_bannercode').on("paste change focus focusout input", function(){ livePreview(); });
	});
	</script>
		<!-- /AdRotate JS -->
		
		<form method="post" action="admin.php?page=adrotate-advertiser" enctype="multipart/form-data">
			<?php wp_nonce_field('adrotate_save_ad','adrotate_nonce'); ?>
			<input type="hidden" name="adrotate_username" value="<?php echo $current_user->user_login;?>" />
			<input type="hidden" name="adrotate_id" value="<?php echo $edit_banner->id;?>" />
			<input type="hidden" name="adrotate_type" value="<?php echo $edit_banner->type;?>" />
			<input type="hidden" name="adrotate_image_current" value="<?php echo $image;?>" />
			<input type="hidden" name="MAX_FILE_SIZE" value="512000" />
		
		<?php if($edit_banner->type == 'a_empty') { ?>
			<h3><?php _e('New Advert', 'adrotate'); ?></h3>
		<?php } else { ?> 
			<h3><?php _e('Edit Advert', 'adrotate'); ?></h3>
		<?php } ?>

			<table class="widefat" style="margin-top: .5em">
		
				<tbody>
		      	<tr>
			        <th width="20%"><?php _e('Title', 'adrotate'); ?></th>
			        <td colspan="2">
			        	<label for="adrotate_title"><input tabindex="1" name="adrotate_title" id="adrotate_title" type="text" size="50" class="search-input" value="<?php echo $edit_banner->title;?>" autocomplete="off" /> <em><?php _e('For your and the staffs reference.', 'adrotate'); ?></em></label>
			        </td>
		      	</tr>
		      	<tr>
			        <th valign="top"><?php _e('AdCode', 'adrotate'); ?></th>
			        <td>
						<label for="adrotate_bannercode"><textarea tabindex="2" id="adrotate_bannercode" name="adrotate_bannercode" cols="65" rows="10"><?php echo stripslashes($edit_banner->bannercode); ?></textarea></label>
			        </td>
			        <td width="40%">
				        <p><strong><?php _e('Basic Examples:', 'adrotate'); ?></strong></p>
						<p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;a href=&quot;http://www.adrotateforwordpress.com&quot;&gt;&lt;img src=&quot;%image%&quot; /&gt;&lt;/a&gt;');return false;">&lt;a href="http://www.adrotateforwordpress.com"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</a></em></p>
				        <p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;span class=&quot;ad-%id%&quot;&gt;&lt;a href=&quot;http://www.adrotateforwordpress.com&quot;&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;');return false;">&lt;span class="ad-%id%"&gt;&lt;a href="http://www.adrotateforwordpress.com"&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;</a></em></p>
				        <p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;iframe src=&quot;%image%&quot; height=&quot;250&quot; frameborder=&quot;0&quot; style=&quot;border:none;&quot;&gt;&lt;/iframe&gt;');return false;">&lt;iframe src=&quot;%image%&quot; height=&quot;250&quot; frameborder=&quot;0&quot; style=&quot;border:none;&quot;&gt;&lt;/iframe&gt;</a></em></p>
			        </td>
		      	</tr>
		      	<tr>
			        <th valign="top"><?php _e('Useful tags', 'adrotate'); ?></th>
			        <td colspan="2">
				        <p><em><a href="#" title="<?php _e('Insert the advert ID Number.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%id%');return false;">%id%</a>, <a href="#" title="<?php _e('Insert the %image% tag. Required when selecting a image below.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%image%');return false;">%image%</a>, <a href="#" title="<?php _e('Insert the advert name.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%title%');return false;">%title%</a>, <a href="#" title="<?php _e('Insert a random seed. Useful for DFP/DoubleClick type adverts.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%random%');return false;">%random%</a>, <a href="#" title="<?php _e('Add inside the <a> tag to open advert in a new window.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','target=&quot;_blank&quot;');return false;">target="_blank"</a>, <a href="#" title="<?php _e('Add inside the <a> tag to tell crawlers to ignore this link', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','rel=&quot;nofollow&quot;');return false;">rel="nofollow"</a></em><br /><?php _e('Place the cursor where you want to add a tag and click to add it to your AdCode.', 'adrotate'); ?></p>
			        </td>
		      	</tr>
			  	<?php if($edit_banner->type != 'a_empty' AND $edit_banner->type != 'empty') { ?>
		      	<tr>
			  		<th valign="top"><?php _e('Live Preview', 'adrotate'); ?></th>
			        <td colspan="2">
			        	<div id="adrotate_preview"></div>
				        <br /><em><?php _e('Note: While this preview is an accurate one, it might look different then it does on the website.', 'adrotate'); ?>
						<br /><?php _e('This is because of CSS differences. Your themes CSS file is not active here!', 'adrotate'); ?></em>
					</td>
		      	</tr>
			  	<?php } ?>
				<tr>
			        <th valign="top"><?php _e('Banner asset', 'adrotate'); ?></th>
					<td colspan="2">
						<label for="adrotate_image"><input tabindex="3" type="file" name="adrotate_image" /><br /><em><?php _e('Use %image% in the code. Accepted files are:', 'adrotate'); ?> jpg, jpeg, gif, png, swf <?php _e('and', 'adrotate'); ?> flv.</em></label>
					</td>
				</tr>
				<?php if($edit_banner->type != 'a_empty') { ?>
					<tr>
				        <th valign="top"><?php _e('Current asset', 'adrotate'); ?></th>
						<td colspan="2">
							<label for="adrotate_image_current"><input disabled tabindex="4" name="adrotate_image_current" id="adrotate_image_current" type="text" size="80" class="search-input" value="<?php echo $image; ?>" /></label>
						</td>
					</tr>
				<?php } ?>
		      	<tr>
				    <th valign="top"><?php _e('Weight', 'adrotate'); ?></th>
			        <td colspan="2">
			        	<label for="adrotate_weight">
			        	&nbsp;<input type="radio" tabindex="5" name="adrotate_weight" value="2" <?php if($edit_banner->weight == "2") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Barely visible', 'adrotate'); ?><br />
			        	&nbsp;<input type="radio" tabindex="6" name="adrotate_weight" value="4" <?php if($edit_banner->weight == "4") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Less than average', 'adrotate'); ?><br />
			        	&nbsp;<input type="radio" tabindex="7" name="adrotate_weight" value="6" <?php if($edit_banner->weight == "6") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Normal visibility', 'adrotate'); ?><br />
			        	&nbsp;<input type="radio" tabindex="8" name="adrotate_weight" value="8" <?php if($edit_banner->weight == "8") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('More than average', 'adrotate'); ?><br />
			        	&nbsp;<input type="radio" tabindex="9" name="adrotate_weight" value="10" <?php if($edit_banner->weight == "10") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Best visibility', 'adrotate'); ?>
			        	</label>
			        	<p><em><?php _e('Weight decides the visibility. Better visibility means more impressions.', 'adrotate'); ?><br /><?php _e('The staff usually is free to change this value depending on their agreement with you.', 'adrotate'); ?></em></p>
					</td>
				</tr>
		      	</tbody>
			</table>

			<?php if($adrotate_config['enable_geo'] > 0 AND $adrotate_config['enable_geo_advertisers'] == 1) { ?>
				<?php $cities = unserialize(stripslashes($edit_banner->cities)); ?>
				<?php $countries = unserialize(stripslashes($edit_banner->countries)); ?>
				<h3><?php _e('Geo Targeting', 'adrotate'); ?></h3>
				<p><em><?php _e('Cities or countries configured do only/apply to groups with geo targeting enabled and is ignored everywhere else.', 'adrotate'); ?><br /><?php _e('Check with your publisher if localized adverts are possible for you.', 'adrotate'); ?></em></p>
					
				<table class="widefat" style="margin-top: .5em">
			
					<tbody>
				    <tr>
						<th valign="top"><?php _e('Cities', 'adrotate'); ?></th>
						<td><textarea tabindex="8" name="adrotate_geo_cities" cols="65" rows="3"><?php echo (is_array($cities)) ? implode(', ', $cities) : ''; ?></textarea></td>
						<td>
					        <p><strong><?php _e('Usage:', 'adrotate'); ?></strong></p>
							<p><em><?php _e('A comma separated list of cities (or the Metro ID) and/or states (Also the states ISO codes are supported)', 'adrotate'); ?> (Alkmaar, Philadelphia, Melbourne, ...)<br /><?php _e('AdRotate does not check the validity of names so make sure you spell them correctly!', 'adrotate'); ?></em></p>
						</td>
					</tr>
				    <tr>
						<th valign="top"><?php _e('Countries', 'adrotate'); ?></strong></th>
				        <td colspan="2">
				        <label for="adrotate_geo_countries">
					        <div class="adrotate-select">
					        <?php echo adrotate_select_countries($countries); ?>
							</div><em><?php _e('Select the countries you want the adverts to show in.', 'adrotate'); ?></em>
				        </label>
				        </td>
					</tr>
					</tbody>
	
				</table>
	      	<?php } ?>
		
			<?php if($groups) { ?>
			<h3><?php _e('Select Groups', 'adrotate'); ?></h3>
			<p><em><?php _e('Select where your ad should be visible. If your desired group/location is not listed contact your publisher.', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $edit_banner->id; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Request group information', 'adrotate'); ?></a>.</em></p>
			<table class="widefat" style="margin-top: .5em">
				<thead>
				<tr>
					<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
					<th><?php _e('Name', 'adrotate'); ?></th>
					<th width="40%"><?php _e('Mode', 'adrotate'); ?></th>
				</tr>
				</thead>
		
				<tbody>
				<?php 
				$class = '';
				foreach($groups as $group) {
					if($group->adspeed > 0) $adspeed = $group->adspeed / 1000;
			        if($group->modus == 0) $modus[] = __('Default rotation', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
			        if($group->modus == 1) $modus[] = $adspeed.' '. __('second rotation', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
			        if($group->modus == 2) $modus[] = $group->gridrows.'x'.$group->gridcolumns.' '. __('grid', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
			        if($group->geo == 1 AND $adrotate_config['enable_geo'] > 0) $modus[] = __('Geolocation', 'adrotate');
					$class = ('alternate' != $class) ? 'alternate' : ''; ?>
				    <tr id='group-<?php echo $group->id; ?>' class='<?php echo $class; ?>'>
						<th class="check-column" width="2%"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
						<td><strong><?php echo $group->name; ?></strong></td>
						<td><?php echo implode(', ', $modus); ?></td>
					</tr>
				<?php 
					unset($modus);
				} 
				?>
				</tbody>					
			</table>
			<?php } ?>

			<?php if($schedules) { ?>
			<h3><?php _e('Choose Schedules', 'adrotate'); ?></h3>
			<p><em><?php _e('Select when your ad should be visible. If your desired timeframe is not listed contact your publisher.', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $edit_banner->id; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Request new schedule', 'adrotate'); ?></a>.</em></p>
			<table class="widefat" style="margin-top: .5em">
		
				<thead>
				<tr>
					<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			        <th width="4%"><?php _e('ID', 'adrotate'); ?></th>
			        <th width="20%"><?php _e('Start / End', 'adrotate'); ?></th>
			        <th>&nbsp;</th>
        	        <?php if($adrotate_config['stats'] == 1) { ?>
				        <th width="10%"><center><?php _e('Max Shown', 'adrotate'); ?></center></th>
				        <th width="10%"><center><?php _e('Max Clicks', 'adrotate'); ?></center></th>
					<?php } ?>
				</tr>
				</thead>
		
				<tbody>
				<?php
				$class = '';
				foreach($schedules as $schedule) { 
					if($adrotate_config['stats'] == 1) {
						if($schedule->maxclicks == 0) $schedule->maxclicks = '&infin;';
						if($schedule->maximpressions == 0) $schedule->maximpressions = '&infin;';
					}

					$class = ('alternate' != $class) ? 'alternate' : '';
					if(in_array($schedule->id, $schedule_array)) $class = 'row_active'; 
					if($schedule->stoptime < $in2days) $class = 'row_urgent'; 

					$sdayhour = substr($schedule->daystarttime, 0, 2);
					$sdayminute = substr($schedule->daystarttime, 2, 2);
					$edayhour = substr($schedule->daystoptime, 0, 2);
					$edayminute = substr($schedule->daystoptime, 2, 2);
					$tick = '<img src="'.plugins_url('../../images/tick.png', __FILE__).'" width="10" height"10" />';
					$cross = '<img src="'.plugins_url('../../images/cross.png', __FILE__).'" width="10" height"10" />';
				?>
		      	<tr id='schedule-<?php echo $schedule->id; ?>' class='<?php echo $class; ?>'>
					<th class="check-column"><input type="checkbox" name="scheduleselect[]" value="<?php echo $schedule->id; ?>" <?php if(in_array($schedule->id, $schedule_array)) echo "checked"; ?> /></th>
					<td><?php echo $schedule->id; ?></td>
					<td><?php echo date_i18n("F d, Y H:i", $schedule->starttime);?><br /><span style="color: <?php echo adrotate_prepare_color($schedule->stoptime);?>;"><?php echo date_i18n("F d, Y H:i", $schedule->stoptime);?></span></td>
					<td><a href="<?php echo admin_url('/admin.php?page=adrotate-schedules&view=edit&schedule='.$schedule->id);?>"><?php echo stripslashes(html_entity_decode($schedule->name)); ?></a><span style="color:#999;"><br /><?php _e('Mon:', 'adrotate'); ?> <?php echo ($schedule->day_mon == 'Y') ? $tick : $cross; ?> <?php _e('Tue:', 'adrotate'); ?> <?php echo ($schedule->day_tue == 'Y') ? $tick : $cross; ?> <?php _e('Wed:', 'adrotate'); ?> <?php echo ($schedule->day_wed == 'Y') ? $tick : $cross; ?> <?php _e('Thu:', 'adrotate'); ?> <?php echo ($schedule->day_thu == 'Y') ? $tick : $cross; ?> <?php _e('Fri:', 'adrotate'); ?> <?php echo ($schedule->day_fri == 'Y') ? $tick : $cross; ?> <?php _e('Sat:', 'adrotate'); ?> <?php echo ($schedule->day_sat == 'Y') ? $tick : $cross; ?> <?php _e('Sun:', 'adrotate'); ?> <?php echo ($schedule->day_sun == 'Y') ? $tick : $cross; ?> <?php if($schedule->daystarttime  > 0) { ?><?php _e('Between:', 'adrotate'); ?> <?php echo $sdayhour; ?>:<?php echo $sdayminute; ?> - <?php echo $edayhour; ?>:<?php echo $edayminute; ?> <?php } ?><?php if($schedule->spread == 'Y') { ?><br />Max. <?php echo $schedule->dayimpressions; ?> <?php _e('impressions per day', 'adrotate'); ?></span><?php } ?></span></td>
					<?php if($adrotate_config['stats'] == 1) { ?>
				        <td><center><?php echo $schedule->maximpressions; ?></center></td>
				        <td><center><?php echo $schedule->maxclicks; ?></center></td>
					<?php } ?>
		      	</tr>
		      	<?php } ?>
				</tbody>
		
			</table>
			<p><center>
				<span style="border: 1px solid #518257; height: 12px; width: 12px; background-color: #e5faee">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("In use by this advert.", "adrotate"); ?>
				&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
			</center></p>
		  	<?php } ?>
		
			<p class="submit">
				<input tabindex="20" type="submit" name="adrotate_advertiser_ad_submit" class="button-primary" value="<?php _e('Submit ad for review', 'adrotate'); ?>" />
				<a href="admin.php?page=adrotate&view=adrotate-advertiser" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
			</p>
		
		</form>
	<?php } else { ?>
		<table class="widefat" style="margin-top: .5em">
			<thead>
				<tr>
					<th><?php _e('Notice', 'adrotate'); ?></th>
				</tr>
			</thead>
			<tbody>
			    <tr>
					<td><?php _e('Invalid ad ID.', 'adrotate'); ?></td>
				</tr>
			</tbody>
		</table>
	<?php
	}
} else {
	$wpnonceaction = 'adrotate_email_advertiser_'.$edit_banner->id;
	$nonce = wp_create_nonce($wpnonceaction);
	?>

	<h3><?php _e('Editing and creating adverts is not available right now', 'adrotate'); ?></h3>
	<p><?php _e('The administrator has disabled editing of adverts.', 'adrotate'); ?> <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $edit_banner->id; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Contact sales', 'adrotate'); ?></a>.</p>

<?php } ?>