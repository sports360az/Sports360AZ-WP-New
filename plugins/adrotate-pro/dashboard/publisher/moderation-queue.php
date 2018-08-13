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
<h3><?php _e('Waiting for Review and Approval', 'adrotate'); ?></h3>

<form name="banners" id="post" method="post" action="admin.php?page=adrotate-moderate">
	<?php wp_nonce_field('adrotate_bulk_ads_queue','adrotate_nonce'); ?>

	<div class="tablenav">
		<div class="alignleft actions">
			<select name="adrotate_queue_action" id="cat" class="postform">
		        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
		        <option value="approve"><?php _e('Approve', 'adrotate'); ?></option>
		        <option value="reject"><?php _e('Reject', 'adrotate'); ?></option>
		        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
			</select>
			<input type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
		</div>
	
		<br class="clear" />
	</div>

	<table class="widefat" style="margin-top: .5em">
		<thead>
		<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
			<th width="15%"><?php _e('Start / End', 'adrotate'); ?></th>
			<th>&nbsp;</th>
			<th width="5%"><?php _e('Device', 'adrotate'); ?></th>
			<th width="5%"><?php _e('Weight', 'adrotate'); ?></th>
			<th width="20%"><?php _e('Advertiser', 'adrotate'); ?></th>
		</tr>
		</thead>
		<tbody>
	<?php
	if ($queued) {
		$class = '';
		foreach($queued as $queue) {			
			if($adrotate_debug['publisher'] == true) {
				echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='5'><pre>";
				echo "Ad Specs: <pre>";
				print_r($queue); 
				echo "</pre></td></tr>"; 
			}
			$advertiser = $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '".$queue['id']."' AND `group` = 0;");
			$advertiser_name = $wpdb->get_var("SELECT `display_name` FROM `$wpdb->users` WHERE `ID` = $advertiser;");
			
			$groups	= $wpdb->get_results("
				SELECT 
					`".$wpdb->prefix."adrotate_groups`.`name` 
				FROM 
					`".$wpdb->prefix."adrotate_groups`, 
					`".$wpdb->prefix."adrotate_linkmeta` 
				WHERE 
					`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$queue['id']."'
					AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
					AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
				;");
			$grouplist = '';
			foreach($groups as $group) {
				$grouplist .= $group->name.", ";
			}
			$grouplist = rtrim($grouplist, ", ");
			
			$errorclass = '';
			if($queue['type'] == 'error' OR $queue['type'] == 'a_error') $errorclass = ' row_error';
			if($queue['lastactive'] <= $in2days OR $queue['lastactive'] <= $in7days) $errorclass = ' row_urgent';
			if($queue['lastactive'] <= $now OR (($queue['crate'] > 0 OR $queue['irate'] > 0) AND $queue['budget'] == 0)) $errorclass = ' row_inactive';

			$class = ('alternate' != $class) ? 'alternate' : '';
			$class = ($errorclass != '') ? $errorclass : $class;

			$mobile = '';
			if($queue['mobile'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
			}
			if($queue['tablet'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
			}
			if($queue['mobile'] == 'N' AND $queue['tablet'] == 'N') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
				$mobile .= '<img src="'.plugins_url('../../images/desktop.png', __FILE__).'" width="12" height="12" title="Desktop" />';
			}
			?>
		    <tr id='adrotateindex' class='<?php echo $class; ?>'>
				<th class="check-column"><input type="checkbox" name="queuecheck[]" value="<?php echo $queue['id']; ?>" /></th>
				<td><center><?php echo $queue['id'];?></center></td>
				<td><?php echo date_i18n("F d, Y", $queue['firstactive']);?><br /><span style="color: <?php echo adrotate_prepare_color($queue['lastactive']);?>;"><?php echo date_i18n("F d, Y", $queue['lastactive']);?></span></td>
				<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=edit&ad='.$queue['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($queue['title']));?></a></strong><?php if($groups) { echo '<br /><span style="color:#999"><strong>'.__('Groups:', 'adrotate').'</strong> '.$grouplist.'</span>'; } ?></td>
				<td><center><?php echo $mobile; ?></center></td>
				<td><center><?php echo $queue['weight']; ?></center></td>
				<td><?php echo $advertiser_name; ?></td>
			</tr>
			<?php } ?>
		<?php } else { ?>
		<tr id='no-groups'>
			<th class="check-column">&nbsp;</th>
			<td colspan="6"><em><?php _e('No ads in queue yet!', 'adrotate'); ?></em></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<p><center>
	<span style="border: 1px solid #e6db55; height: 12px; width: 12px; background-color: #ffffe0">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Configuration errors.", "adrotate"); ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #466f82; height: 12px; width: 12px; background-color: #8dcede">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has expired.", "adrotate"); ?>
</center></p>
</form>