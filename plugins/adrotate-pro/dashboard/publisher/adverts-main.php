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
<h3><?php _e('Active Ads', 'adrotate'); ?></h3>

<form name="banners" id="post" method="post" action="admin.php?page=adrotate-ads">
	<?php wp_nonce_field('adrotate_bulk_ads_active','adrotate_nonce'); ?>

	<div class="tablenav top">
		<div class="alignleft actions">
			<select name="adrotate_action" id="cat" class="postform">
		        <option value=""><?php _e('Bulk Actions', 'adrotate'); ?></option>
		        <option value="duplicate"><?php _e('Duplicate', 'adrotate'); ?></option>
		        <option value="deactivate"><?php _e('Deactivate', 'adrotate'); ?></option>
		        <option value="delete"><?php _e('Delete', 'adrotate'); ?></option>
		        <option value="reset"><?php _e('Reset stats', 'adrotate'); ?></option>
		        <option value="export-xml"><?php _e('Export to XML', 'adrotate'); ?></option>
		        <option value="" disabled><?php _e('-- Renew --', 'adrotate'); ?></option>
		        <option value="renew-31536000"><?php _e('For 1 year', 'adrotate'); ?></option>
		        <option value="renew-5184000"><?php _e('For 180 days', 'adrotate'); ?></option>
		        <option value="renew-2592000"><?php _e('For 30 days', 'adrotate'); ?></option>
		        <option value="renew-604800"><?php _e('For 7 days', 'adrotate'); ?></option>
		        <option value="" disabled><?php _e('-- Weight --', 'adrotate'); ?></option>
		        <option value="weight-2">2 - <?php _e('Barely visible', 'adrotate'); ?></option>
		        <option value="weight-4">4 - <?php _e('Less than average', 'adrotate'); ?></option>
		        <option value="weight-6">6 - <?php _e('Normal coverage', 'adrotate'); ?></option>
		        <option value="weight-8">8 - <?php _e('More than average', 'adrotate'); ?></option>
		        <option value="weight-10">10 - <?php _e('Best visibility', 'adrotate'); ?></option>
			</select> <input type="submit" id="post-action-submit" name="adrotate_action_submit" value="<?php _e('Go', 'adrotate'); ?>" class="button-secondary" />
		</div>	
		<br class="clear" />
	</div>

	<table class="widefat" style="margin-top: .5em">
		<thead>
			<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
			<th width="15%"><?php _e('Start / End', 'adrotate'); ?></th>
			<th><?php _e('Title', 'adrotate'); ?></th>
			<th width="5%"><center><?php _e('Device', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
			<?php if($adrotate_config['stats'] == 1) { ?>
				<th width="5%"><center><?php _e('Shown', 'adrotate'); ?></center></th>
				<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
				<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
				<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
				<th width="7%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
			<?php } ?>
		</tr>
		</thead>
		<tbody>
	<?php
	if ($activebanners) {
		$class = '';
		foreach($activebanners as $banner) {
			if($adrotate_config['stats'] == 1) {
				$stats = adrotate_stats($banner['id']);
				$stats_today = adrotate_stats($banner['id'], $today);
				$ctr = adrotate_ctr($stats['clicks'], $stats['impressions']);						
			}
			$grouplist = adrotate_ad_is_in_groups($banner['id']);
			
			if($adrotate_debug['publisher'] == true) {
				echo "<tr><td>&nbsp;</td><td><strong>[DEBUG]</strong></td><td colspan='9'><pre>";
				echo "Ad Specs: <pre>";
				print_r($banner); 
				echo "</pre>"; 
				if($adrotate_config['stats'] == 1) {
					echo "Stats: <pre>";
					print_r($stats); 
					echo "</pre>"; 
					echo "Stats today: <pre>";
					print_r($stats_today); 
					echo "</pre></td></tr>"; 
				}
			}
						
			if($class != 'alternate') {
				$class = 'alternate';
			} else {
				$class = '';
			}

			$mobile = '';
			if($banner['mobile'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
			}
			if($banner['tablet'] == 'Y') {
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
			}
			if($banner['mobile'] == 'N' AND $banner['tablet'] == 'N') {
				$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
				$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
				$mobile .= '<img src="'.plugins_url('../../images/desktop.png', __FILE__).'" width="12" height="12" title="Desktop" />';
			}
			?>
		    <tr id='adrotateindex' class='<?php echo $class; ?>'>
				<th class="check-column"><input type="checkbox" name="bannercheck[]" value="<?php echo $banner['id']; ?>" /></th>
				<td><center><?php echo $banner['id'];?></center></td>
				<td><?php echo date_i18n("F d, Y", $banner['firstactive']);?><br /><span style="color: <?php echo adrotate_prepare_color($banner['lastactive']);?>;"><?php echo date_i18n("F d, Y", $banner['lastactive']);?></span></td>
				<td>
					<strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=edit&ad='.$banner['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($banner['title']));?></a></strong> <?php if($adrotate_config['stats'] == 1) { ?>- <a href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=report&ad='.$banner['id']);?>" title="<?php _e('Stats', 'adrotate'); ?>"><?php _e('Stats', 'adrotate'); ?></a><?php } ?>
					<span style="color:#999;">
						<?php if(strlen($grouplist) > 0) echo '<br /><span style="font-weight:bold;">'.__('Groups:', 'adrotate').'</span> '.$grouplist; ?>
						<?php if(strlen($banner['advertiser']) > 0 AND $adrotate_config['enable_advertisers'] == 'Y') echo '<br /><span style="font-weight:bold;">'.__('Advertiser:', 'adrotate').'</span> '.$banner['advertiser'];
						if($banner['crate'] > 0 OR $banner['irate'] > 0) { echo ' <span style="font-weight:bold;">'.__('Budget:', 'adrotate').'</span> '.number_format($banner['budget'], 2, '.', '').' - '.__('CPC:', 'adrotate').' '.number_format($banner['crate'], 2, '.', '').' - '.__('CPM:', 'adrotate').' '.number_format($banner['irate'], 2, '.', ''); } ?>
				</td>
				<td><center><?php echo $mobile; ?></center></td>
				<td><center><?php echo $banner['weight']; ?></center></td>
				<?php if($adrotate_config['stats'] == 1) { ?>
					<?php if($banner['tracker'] == "Y") { ?>
					<td><center><?php echo $stats['impressions']; ?></center></td>
					<td><center><?php echo $stats_today['impressions']; ?></center></td>
					<td><center><?php echo $stats['clicks']; ?></center></td>
					<td><center><?php echo $stats_today['clicks']; ?></center></td>
					<td><center><?php echo $ctr; ?> %</center></td>
					<?php } else { ?>
					<td><center>--</center></td>
					<td><center>--</center></td>
					<td><center>--</center></td>
					<td><center>--</center></td>
					<td><center>--</center></td>
					<?php } ?>
				<?php } ?>
			</tr>
			<?php } ?>
		<?php } else { ?>
		<tr id='no-groups'>
			<th class="check-column">&nbsp;</th>
			<td colspan="<?php echo ($adrotate_config['stats'] == 1) ? '10' : '5'; ?>"><em><?php _e('No ads created yet!', 'adrotate'); ?></em></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

</form>
