<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

if(!$schedule_edit_id) { ?>
	<h3><?php _e('New Schedule', 'adrotate'); ?></h3>
<?php
	$edit_id = $wpdb->get_var("SELECT `id` FROM `{$wpdb->prefix}adrotate_schedule` WHERE `name` = '' AND `starttime` < $now ORDER BY `id` DESC LIMIT 1;");
	if($edit_id == 0) {
		$wpdb->insert($wpdb->prefix.'adrotate_schedule', array('name' => '', 'starttime' => $now, 'stoptime' => $in84days, 'maxclicks' => 0, 'maximpressions' => 0, 'spread' => 'N', 'dayimpressions' => 0, 'daystarttime' => '0000', 'daystoptime' => '0000', 'day_mon' => 'Y', 'day_tue' => 'Y', 'day_wed' => 'Y', 'day_thu' => 'Y', 'day_fri' => 'Y', 'day_sat' => 'Y', 'day_sun' => 'Y'));
	    $edit_id = $wpdb->insert_id;
	}
	$schedule_edit_id = $edit_id;
} else { ?>
	<h3><?php _e('Edit Schedule', 'adrotate'); ?></h3>
<?php
}

$edit_schedule = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}adrotate_schedule` WHERE `id` = $schedule_edit_id;");
$ads = $wpdb->get_results("SELECT `id`, `title`, `type`, `tracker`, `mobile`, `tablet`, `weight`, `crate`, `budget`, `irate` FROM `{$wpdb->prefix}adrotate` WHERE (`type` != 'empty' AND `type` != 'a_empty') ORDER BY `id` ASC;");
$linkmeta = $wpdb->get_results("SELECT `ad` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `schedule` = '$schedule_edit_id' AND `group` = 0 AND `user` = 0;");

$class = $meta_array = '';
foreach($linkmeta as $meta) {
	$meta_array[] = $meta->ad;
}
if(!is_array($meta_array)) $meta_array = array();

list($sday, $smonth, $syear, $shour, $sminute) = explode(" ", date("d m Y H i", $edit_schedule->starttime));
list($eday, $emonth, $eyear, $ehour, $eminute) = explode(" ", date("d m Y H i", $edit_schedule->stoptime));
$sdayhour = substr($edit_schedule->daystarttime, 0, 2);
$sdayminute = substr($edit_schedule->daystarttime, 2, 2);
$edayhour = substr($edit_schedule->daystoptime, 0, 2);
$edayminute = substr($edit_schedule->daystoptime, 2, 2);
?>

<form method="post" action="admin.php?page=adrotate-schedules">
	<?php wp_nonce_field('adrotate_save_schedule','adrotate_nonce'); ?>
	<input type="hidden" name="adrotate_id" value="<?php echo $edit_schedule->id;?>" />

	<table class="widefat" style="margin-top: .5em">
		<tbody>
      	<tr>
      		<th width="20%"><?php _e('ID', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<?php echo $edit_schedule->id; ?>
			</td>
		</tr>
      	<tr>
      		<th><?php _e('Name', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<label for="adrotate_schedulename"><input tabindex="1" name="adrotate_schedulename" type="text" class="search-input" size="50" value="<?php echo stripslashes(html_entity_decode($edit_schedule->name)); ?>" autocomplete="off" /> <em><?php _e('Visible to Advertisers!', 'adrotate'); ?></em></em></label>
			</td>
		</tr>
      	<tr>
	        <th width="20%"><?php _e('Start date (day/month/year)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_sday">
	        	<input tabindex="2" name="adrotate_sday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $sday;?>" /> /
				<select tabindex="3" name="adrotate_smonth">
					<option value="01" <?php if($smonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
					<option value="02" <?php if($smonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
					<option value="03" <?php if($smonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
					<option value="04" <?php if($smonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
					<option value="05" <?php if($smonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
					<option value="06" <?php if($smonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
					<option value="07" <?php if($smonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
					<option value="08" <?php if($smonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
					<option value="09" <?php if($smonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
					<option value="10" <?php if($smonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
					<option value="11" <?php if($smonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
					<option value="12" <?php if($smonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
				</select> /
				<input tabindex="4" name="adrotate_syear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $syear;?>" />&nbsp;&nbsp;&nbsp; 
				</label>
	        </td>
	        <th width="20%"><?php _e('End date (day/month/year)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_eday">
	        	<input tabindex="5" name="adrotate_eday" class="search-input" type="text" size="4" maxlength="2" value="<?php echo $eday;?>"  /> /
				<select tabindex="6" name="adrotate_emonth">
					<option value="01" <?php if($emonth == "01") { echo 'selected'; } ?>><?php _e('January', 'adrotate'); ?></option>
					<option value="02" <?php if($emonth == "02") { echo 'selected'; } ?>><?php _e('February', 'adrotate'); ?></option>
					<option value="03" <?php if($emonth == "03") { echo 'selected'; } ?>><?php _e('March', 'adrotate'); ?></option>
					<option value="04" <?php if($emonth == "04") { echo 'selected'; } ?>><?php _e('April', 'adrotate'); ?></option>
					<option value="05" <?php if($emonth == "05") { echo 'selected'; } ?>><?php _e('May', 'adrotate'); ?></option>
					<option value="06" <?php if($emonth == "06") { echo 'selected'; } ?>><?php _e('June', 'adrotate'); ?></option>
					<option value="07" <?php if($emonth == "07") { echo 'selected'; } ?>><?php _e('July', 'adrotate'); ?></option>
					<option value="08" <?php if($emonth == "08") { echo 'selected'; } ?>><?php _e('August', 'adrotate'); ?></option>
					<option value="09" <?php if($emonth == "09") { echo 'selected'; } ?>><?php _e('September', 'adrotate'); ?></option>
					<option value="10" <?php if($emonth == "10") { echo 'selected'; } ?>><?php _e('October', 'adrotate'); ?></option>
					<option value="11" <?php if($emonth == "11") { echo 'selected'; } ?>><?php _e('November', 'adrotate'); ?></option>
					<option value="12" <?php if($emonth == "12") { echo 'selected'; } ?>><?php _e('December', 'adrotate'); ?></option>
				</select> /
				<input tabindex="7" name="adrotate_eyear" class="search-input" type="text" size="4" maxlength="4" value="<?php echo $eyear;?>" />&nbsp;&nbsp;&nbsp; 
				</label>
			</td>
      	</tr>	
      	<tr>
	        <th><?php _e('Start time (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_sday">
				<input tabindex="8" name="adrotate_shour" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $shour;?>" /> :
				<input tabindex="9" name="adrotate_sminute" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $sminute;?>" />
				</label>
	        </td>
	        <th><?php _e('End time (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_eday">
				<input tabindex="10" name="adrotate_ehour" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $ehour;?>" /> :
				<input tabindex="11" name="adrotate_eminute" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $eminute;?>" />
				</label>
			</td>
      	</tr>	
		</tbody>
	</table>
	

	<h3><?php _e('Advanced', 'adrotate'); ?></h3>
	<em><?php _e('Everything below is optional.', 'adrotate'); ?> <?php _e('These settings may cause adverts to intermittently not show. Use with care!', 'adrotate'); ?></em>
	<table class="widefat" style="margin-top: .5em">
		<tbody>

      	<tr>
	        <th width="20%"><?php _e('Show only on', 'adrotate'); ?></th>
			<td colspan="3"><label for="adrotate_spread"><input tabindex="12" type="checkbox" name="adrotate_mon" value="1" <?php if($edit_schedule->day_mon == 'Y') { ?>checked="checked"<?php } ?> />Mon&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_tue" value="1" <?php if($edit_schedule->day_tue == 'Y') { ?>checked="checked"<?php } ?> />Tue&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_wed" value="1" <?php if($edit_schedule->day_wed == 'Y') { ?>checked="checked"<?php } ?> />Wed&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_thu" value="1" <?php if($edit_schedule->day_thu == 'Y') { ?>checked="checked"<?php } ?> />Thu&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_fri" value="1" <?php if($edit_schedule->day_fri == 'Y') { ?>checked="checked"<?php } ?> />Fri&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_sat" value="1" <?php if($edit_schedule->day_sat == 'Y') { ?>checked="checked"<?php } ?> />Sat&nbsp;&nbsp;<input tabindex="12" type="checkbox" name="adrotate_sun" value="1" <?php if($edit_schedule->day_sun == 'Y') { ?>checked="checked"<?php } ?> />Sun</label></td>
      	</tr>	
      	<tr>
	        <th><?php _e('Daily start at (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_sday">
				<input tabindex="13" name="adrotate_sdayhour" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $sdayhour;?>" /> :
				<input tabindex="14" name="adrotate_sdayminute" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $sdayminute;?>" />
				</label>
	        </td>
	        <th width="20%"><?php _e('End on (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_eday">
				<input tabindex="15" name="adrotate_edayhour" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $edayhour;?>" /> :
				<input tabindex="16" name="adrotate_edayminute" class="search-input" type="text" size="2" maxlength="2" value="<?php echo $edayminute;?>" />
				</label>
			</td>
      	</tr>	
		<?php if($adrotate_config['stats'] == 1) { ?>
      	<tr>
      		<th><?php _e('Maximum Clicks', 'adrotate'); ?></th>
	        <td><input tabindex="17" name="adrotate_maxclicks" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_schedule->maxclicks; ?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
		    <th><?php _e('Maximum Impressions', 'adrotate'); ?></th>
	        <td><input tabindex="18" name="adrotate_maxshown" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_schedule->maximpressions; ?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Spread Impressions', 'adrotate'); ?></th>
			<td colspan="3"><label for="adrotate_spread"><input tabindex="19" type="checkbox" name="adrotate_spread" value="1" <?php if($edit_schedule->spread == 'Y') { ?>checked="checked"<?php } ?> /> <?php _e('Try to evenly spread impressions for each advert over the duraction of this schedule.', 'adrotate'); ?></label></td>
		</tr>
		<?php } ?>
		</tbody>	

	</table>
	
	<p><em><strong>Caution:</strong> The time restriction is <u>EXPERIMENTAL</u> and must start and end on the same day. The ending hour must be after the starting hour and (for now) must be before midnight!</em></p>

	<?php if($adrotate_config['hide_schedules'] == "Y") { ?>
	<p><em><strong><?php _e('Note:', 'adrotate'); ?></strong> <?php _e("Adverts hide schedules that are not used by that advert.", "adrotate"); ?></em></p>
	<?php } ?>

	<p><em><strong><?php _e('Note:', 'adrotate'); ?></strong> <?php _e('Time uses a 24 hour clock. When you\'re used to AM/PM: If the start or end time is after lunch, add 12 hours. 2PM is 14:00 hours. 6AM is 6:00 hours.', 'adrotate'); ?><br /><?php _e('The maximum clicks and impressions are measured over the set schedule only and applies to all adverts using this schedule combined. Every schedule can have it\'s own limit!', 'adrotate'); ?></em></p>


	<p class="submit">
		<input tabindex="20" type="submit" name="adrotate_schedule_submit" class="button-primary" value="<?php _e('Save Schedule', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-schedules" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>

	<h3><?php _e('Select Ads', 'adrotate'); ?></h3>
   	<table class="widefat" style="margin-top: .5em">
		<thead>
		<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			<th><?php _e('Choose adverts', 'adrotate'); ?></th>
			<th width="5%"><center><?php _e('Device', 'adrotate'); ?></center></th>
	        <?php if($adrotate_config['stats'] == 1) { ?>
				<th width="5%"><center><?php _e('Shown', 'adrotate'); ?></center></th>
				<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
			<?php } ?>
			<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
			<th width="15%"><?php _e('Visible until', 'adrotate'); ?></th>
		</tr>
		</thead>

		<tbody>
		<?php if($ads) {
			$class = '';
			foreach($ads as $ad) {
				$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$ad->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");

				$errorclass = '';
				if($ad->type == 'error' OR $ad->type == 'a_error') $errorclass = ' row_error';
				if($stoptime <= $in2days OR $stoptime <= $in7days) $errorclass = ' row_urgent';
				if($stoptime <= $now OR (($ad->crate > 0 OR $ad->irate > 0) AND $ad->budget == 0)) $errorclass = ' row_inactive';

				if($adrotate_config['stats'] == 1) {
					$stats = adrotate_stats($ad->id);
				}

				$class = ('alternate' != $class) ? 'alternate' : '';
				$class = ($errorclass != '') ? $errorclass : $class;

				$mobile = '';
				if($ad->mobile == 'Y') {
					$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
				}
				if($ad->tablet == 'Y') {
					$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
				}
				if($ad->mobile == 'N' AND $ad->tablet == 'N') {
					$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
					$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
					$mobile .= '<img src="'.plugins_url('../../images/desktop.png', __FILE__).'" width="12" height="12" title="Desktop" />';
				}
				?>
			    <tr class='<?php echo $class; ?>'>
					<th class="check-column" width="2%"><input type="checkbox" name="adselect[]" value="<?php echo $ad->id; ?>" <?php if(in_array($ad->id, $meta_array)) echo "checked"; ?> /></th>
					<td><?php echo $ad->id; ?> - <strong><?php echo stripslashes(html_entity_decode($ad->title)); ?></strong></td>
					<td><center><?php echo $mobile; ?></center></td>
					<?php if($adrotate_config['stats'] == 1) { ?>
						<td><center><?php echo $stats['impressions']; ?></center></td>
						<td><center><?php if($ad->tracker == 'Y') { echo $stats['clicks']; } else { ?>--<?php } ?></center></td>
					<?php } ?>
					<td><center><?php echo $ad->weight; ?></center></td>
					<td><span style="color: <?php echo adrotate_prepare_color($stoptime);?>;"><?php echo date_i18n("F d, Y", $stoptime); ?></span></td>
				</tr>
			<?php unset($stats);?>
 			<?php } ?>
		<?php } else { ?>
		<tr>
			<th class="check-column">&nbsp;</th>
			<td colspan="<?php echo ($adrotate_config['stats'] == 1) ? '6' : '4';?>"><em><?php _e('No ads created!', 'adrotate'); ?></em></td>
		</tr>
		<?php } ?>
		</tbody>					
	</table>

	<p><center>
		<span style="border: 1px solid #e6db55; height: 12px; width: 12px; background-color: #ffffe0">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Configuration errors.", "adrotate"); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #466f82; height: 12px; width: 12px; background-color: #8dcede">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has expired.", "adrotate"); ?>
	</center></p>

	<p class="submit">
		<input tabindex="21" type="submit" name="adrotate_schedule_submit" class="button-primary" value="<?php _e('Save Schedule', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-schedules" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>
</form>