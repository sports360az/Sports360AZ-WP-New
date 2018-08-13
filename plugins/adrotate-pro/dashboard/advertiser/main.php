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

<h3><?php _e('Your Active Ads', 'adrotate'); ?></h3>
<p><em><?php _e('These are active and currently in the pool of ads shown on the website.', 'adrotate'); ?></em></p>

<table class="widefat" style="margin-top: .5em">
	<thead>
		<tr>
		<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
		<th width="15%"><?php _e('Start / End', 'adrotate'); ?></th>
		<th><?php _e('Title', 'adrotate'); ?></th>
		<th width="5%"><center><?php _e('Device', 'adrotate'); ?></center></th>
		<?php if($adrotate_config['stats'] == 1) { ?>
			<th width="5%"><center><?php _e('Shown', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Today', 'adrotate'); ?></center></th>
			<th width="7%"><center><?php _e('CTR', 'adrotate'); ?></center></th>
		<?php } ?>
		<th width="17%"><?php _e('Contact publisher', 'adrotate'); ?></th>
	</tr>
	</thead>
	
	<tbody>
<?php
	foreach($activebanners as $ad) {
		if($adrotate_config['stats'] == 1) {
			$stats = adrotate_stats($ad['id']);
			$stats_today = adrotate_stats($ad['id'], $today);
			$ctr = adrotate_ctr($stats['clicks'], $stats['impressions']);
		}

		$wpnonceaction = 'adrotate_email_advertiser_'.$ad['id'];
		$nonce = wp_create_nonce($wpnonceaction);

		$errorclass = $class = '';
		if('alternate' == $class) $class = 'alternate'; else $class = '';
		if($ad['type'] == '2days') $errorclass = ' row_error'; 
		if($ad['type'] == '7days') $errorclass = ' row_error';
		if($ad['type'] == 'expired') $errorclass = ' row_urgent';

		$mobile = '';
		if($ad['mobile'] == 'Y') {
			$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
		}
		if($ad['tablet'] == 'Y') {
			$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
		}
		if($ad['mobile'] == 'N' AND $ad['tablet'] == 'N') {
			$mobile .= '<img src="'.plugins_url('../../images/mobile.png', __FILE__).'" width="12" height="12" title="Mobile" />';
			$mobile .= '<img src="'.plugins_url('../../images/tablet.png', __FILE__).'" width="12" height="12" title="Tablet" />';
			$mobile .= '<img src="'.plugins_url('../../images/desktop.png', __FILE__).'" width="12" height="12" title="Desktop" />';
		}
?>
	    <tr id='banner-<?php echo $ad['id']; ?> <?php echo $ad['type']; ?>' class='<?php echo $class.$errorclass; ?>'>
			<td><center><?php echo $ad['id'];?></center></td>
			<td><?php echo date_i18n("F d, Y", $ad['firstactive']);?><br /><span style="color: <?php echo adrotate_prepare_color($ad['lastactive']);?>;"><?php echo date_i18n("F d, Y", $ad['lastactive']);?></span></td>
			<td>
				<strong><?php if($adrotate_config['enable_editing'] == 'Y') { ?><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-advertiser&view=edit&ad='.$ad['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($ad['title']));?></a><?php } else { echo stripslashes(html_entity_decode($ad['title'])); } ?></strong>
				<?php if($adrotate_config['stats'] == 1) { ?> - <a href="<?php echo admin_url('/admin.php?page=adrotate-advertiser&view=report&ad='.$ad['id']);?>" title="<?php _e('Stats', 'adrotate'); ?>">Stats</a><?php } ?>
				<span style="color:#999;">
					<?php if($ad['crate'] > 0 OR $ad['irate'] > 0) {
						echo '<br /><span style="font-weight:bold;">'.__('Budget:', 'adrotate').'</span> '.number_format($ad['budget'], 2, '.', '').' - '; 
						echo __('CPC:', 'adrotate').' '.number_format($ad['crate'], 2, '.', '').' - ';
						echo __('CPM:', 'adrotate').' '.number_format($ad['irate'], 2, '.', '');
					} ?>
				</span>
			</td>
			<td><center><?php echo $mobile;?></center></td>
			<?php if($adrotate_config['stats'] == 1) { ?>
				<td><center><?php echo $stats['impressions'];?></center></td>
				<td><center><?php echo $stats_today['impressions'];?></center></td>
				<td><center><?php echo $stats['clicks'];?></center></td>
				<td><center><?php echo $stats_today['clicks'];?></center></td>
				<td><center><?php echo $ctr; ?> %</center></td>
			<?php } ?>
			<td><a href="admin.php?page=adrotate-advertiser&view=message&request=renew&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Renew', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=remove&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Remove', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Other', 'adrotate'); ?></a></td>
		</tr>
		<?php } ?>
	</tbody>

</table>
<p><center>
	<span style="border: 1px solid #e6db55; height: 12px; width: 12px; background-color: #ffffe0">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Is almost expired.", "adrotate"); ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has expired.", "adrotate"); ?>
</center></p>