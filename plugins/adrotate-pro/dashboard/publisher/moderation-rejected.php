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
<h3><?php _e('Rejected adverts', 'adrotate'); ?></h3>

<form name="banners" id="post" method="post" action="admin.php?page=adrotate-moderate">
	<?php wp_nonce_field('adrotate_bulk_ads_reject','adrotate_nonce'); ?>

	<div class="tablenav">
		<div class="alignleft actions">
			<select name="adrotate_reject_action" id="cat" class="postform">
		        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
		        <option value="queue"><?php _e('Queue', 'adrotate'); ?></option>
		        <option value="approve"><?php _e('Approve', 'adrotate'); ?></option>
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
			<th width="5%"><center><?php _e('Device', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
			<th width="20%"><?php _e('Advertiser', 'adrotate'); ?></th>
		</tr>
		</thead>
		<tbody>
	<?php
	if ($rejected) {
		$class = $errorclass = '';
		foreach($rejected as $reject) {			
			if($adrotate_debug['publisher'] == true) {
				echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='5'><pre>";
				echo "Ad Specs: <pre>";
				print_r($reject); 
				echo "</pre></td></tr>"; 
			}
						
			$groups	= $wpdb->get_results("
				SELECT 
					`".$wpdb->prefix."adrotate_groups`.`name` 
				FROM 
					`".$wpdb->prefix."adrotate_groups`, 
					`".$wpdb->prefix."adrotate_linkmeta` 
				WHERE 
					`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$reject['id']."'
					AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
					AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
				;");
			$grouplist = '';
			foreach($groups as $group) {
				$grouplist .= $group->name.", ";
			}
			$grouplist = rtrim($grouplist, ", ");

			$advertiser_id = $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = ".$reject['id']." AND `group` = 0 AND `schedule` = 0;");
			$advertiser = get_user_by('id', $advertiser_id);
			
			if($class != 'alternate') {
				$class = 'alternate';
			} else {
				$class = '';
			}

			if($reject['lastactive'] <= $in7days) {
				$errorclass = ' row_error';
			} else {
				$errorclass = '';
			}

			$mobile = '';
			if($reject['mobile'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
			}
			if($reject['tablet'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
			}
			if($reject['mobile'] == 'N' AND $reject['tablet'] == 'N') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
				$mobile .= '<img src="'.plugins_url('../../images/desktop.png', __FILE__).'" width="12" height="12" title="Desktop" />';
			}
			?>
		    <tr id='adrotateindex' class='<?php echo $class.$errorclass; ?>'>
				<th class="check-column"><input type="checkbox" name="rejectcheck[]" value="<?php echo $reject['id']; ?>" /></th>
				<td><center><?php echo $reject['id'];?></center></td>
				<td><?php echo date_i18n("F d, Y", $reject['firstactive']);?><br /><span style="color: <?php echo adrotate_prepare_color($reject['lastactive']);?>;"><?php echo date_i18n("F d, Y", $reject['lastactive']);?></span></td>
				<td><strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=edit&ad='.$reject['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($reject['title']));?></a></strong><?php if($groups) echo '<br /><em style="color:#999">'.$grouplist.'</em>'; ?></td>
				<td><center><?php echo $mobile; ?></center></td>
				<td><center><?php echo $reject['weight']; ?></center></td>
				<td><?php echo $advertiser->display_name; ?></td>
			</tr>
			<?php } ?>
		<?php } else { ?>
		<tr id='no-groups'>
			<th class="check-column">&nbsp;</th>
			<td colspan="10"><em><?php _e('No ads in queue yet!', 'adrotate'); ?></em></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

</form>
