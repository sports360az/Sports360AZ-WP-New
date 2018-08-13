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
<h3><?php _e('Manage Schedules', 'adrotate'); ?></h3>

<form name="banners" id="post" method="post" action="admin.php?page=adrotate-schedules">
	<?php wp_nonce_field('adrotate_bulk_schedules','adrotate_nonce'); ?>

	<div class="tablenav top">
		<div class="alignleft actions">
			<select name="adrotate_action" id="cat" class="postform">
		        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
		        <option value="schedule_delete"><?php _e('Delete', 'adrotate'); ?></option>
			</select> <input type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
		</div>	
		<br class="clear" />
	</div>

	<table class="widefat" style="margin-top: .5em">
		<thead>
		<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			<th width="4%"><center><?php _e('ID', 'adrotate'); ?></center></th>
			<th width="20%"><?php _e('Start / End', 'adrotate'); ?></th>
			<th>&nbsp;</th>
	        <th width="4%"><center><?php _e('Ads', 'adrotate'); ?></center></th>
	        <?php if($adrotate_config['stats'] == 1) { ?>
		        <th width="10%"><center><?php _e('Max Shown', 'adrotate'); ?></center></th>
		        <th width="10%"><center><?php _e('Max Clicks', 'adrotate'); ?></center></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
	<?php
	$schedules = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_schedule` WHERE `name` != '' ORDER BY `id` ASC;");
	if($schedules) {
		$class = '';
		foreach($schedules as $schedule) {
			$schedulesmeta = $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = 0 AND `user` = 0 AND `schedule` = ".$schedule->id.";");
			$ads_use_schedule = '';
			if($schedulesmeta) {
				foreach($schedulesmeta as $meta) {
					$ads_use_schedule[] = $meta->ad;
					unset($meta);
				}
			}
			if($adrotate_config['stats'] == 1) {
				if($schedule->maxclicks == 0) $schedule->maxclicks = '&infin;';
				if($schedule->maximpressions == 0) $schedule->maximpressions = '&infin;';
			}

			($class != 'alternate') ? $class = 'alternate' : $class = '';
			if($schedule->stoptime < $in2days) $class = 'row_urgent';
			if($schedule->stoptime < $now) $class = 'row_inactive';

			$sdayhour = substr($schedule->daystarttime, 0, 2);
			$sdayminute = substr($schedule->daystarttime, 2, 2);
			$edayhour = substr($schedule->daystoptime, 0, 2);
			$edayminute = substr($schedule->daystoptime, 2, 2);
			$tick = '<img src="'.plugins_url('../../images/tick.png', __FILE__).'" width="10" height"10" />';
			$cross = '<img src="'.plugins_url('../../images/cross.png', __FILE__).'" width="10" height"10" />';
			?>
		    <tr id='adrotateindex' class='<?php echo $class; ?>'>
				<th class="check-column"><input type="checkbox" name="schedulecheck[]" value="<?php echo $schedule->id; ?>" /></th>
				<td><center><?php echo $schedule->id;?></center></td>
				<td><?php echo date_i18n("F d, Y H:i", $schedule->starttime);?><br /><span style="color: <?php echo adrotate_prepare_color($schedule->stoptime);?>;"><?php echo date_i18n("F d, Y H:i", $schedule->stoptime);?></span></td>
				<td><a href="<?php echo admin_url('/admin.php?page=adrotate-schedules&view=edit&schedule='.$schedule->id);?>"><?php echo stripslashes(html_entity_decode($schedule->name)); ?></a><span style="color:#999;"><br /><?php _e('Mon:', 'adrotate'); ?> <?php echo ($schedule->day_mon == 'Y') ? $tick : $cross; ?> <?php _e('Tue:', 'adrotate'); ?> <?php echo ($schedule->day_tue == 'Y') ? $tick : $cross; ?> <?php _e('Wed:', 'adrotate'); ?> <?php echo ($schedule->day_wed == 'Y') ? $tick : $cross; ?> <?php _e('Thu:', 'adrotate'); ?> <?php echo ($schedule->day_thu == 'Y') ? $tick : $cross; ?> <?php _e('Fri:', 'adrotate'); ?> <?php echo ($schedule->day_fri == 'Y') ? $tick : $cross; ?> <?php _e('Sat:', 'adrotate'); ?> <?php echo ($schedule->day_sat == 'Y') ? $tick : $cross; ?> <?php _e('Sun:', 'adrotate'); ?> <?php echo ($schedule->day_sun == 'Y') ? $tick : $cross; ?> <?php if($schedule->daystarttime  > 0) { ?><?php _e('Between:', 'adrotate'); ?> <?php echo $sdayhour; ?>:<?php echo $sdayminute; ?> - <?php echo $edayhour; ?>:<?php echo $edayminute; ?> <?php } ?><?php if($schedule->spread == 'Y') { ?><br />Max. <?php echo $schedule->dayimpressions; ?> <?php _e('impressions per day', 'adrotate'); ?></span><?php } ?></span></td>
		        <td><center><?php echo count($schedulesmeta); ?></center></td>
		        <?php if($adrotate_config['stats'] == 1) { ?>
			        <td><center><?php echo $schedule->maximpressions; ?></center></td>
			        <td><center><?php echo $schedule->maxclicks; ?></center></td>
				<?php } ?>
			</tr>
			<?php } ?>
		<?php } else { ?>
		<tr id='no-schedules'>
			<th class="check-column">&nbsp;</th>
			<td colspan="<?php echo ($adrotate_config['stats'] == 1) ? '7' : '5'; ?>"><em><?php _e('No schedules created yet!', 'adrotate'); ?></em></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<p><center>
	<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #466f82; height: 12px; width: 12px; background-color: #8dcede">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has expired.", "adrotate"); ?>
</center></p>
</form>
