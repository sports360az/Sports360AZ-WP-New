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

<h3><?php _e('Queued and Erroneous Ads', 'adrotate'); ?></h3>
<p><em><?php _e('Ads listed here are queued for review, rejected by a reviewer or have a configuration error.', 'adrotate'); ?></em></p>

<table class="widefat" style="margin-top: .5em">
	<thead>
	<tr>
		<th width="2%"><center><?php _e('ID', 'adrotate'); ?></center></th>
		<th><?php _e('Title', 'adrotate'); ?></th>
		<th width="5%"><center><?php _e('Device', 'adrotate'); ?></center></th>
		<th width="17%"><?php _e('Contact publisher', 'adrotate'); ?></th>
	</tr>
	</thead>
	
	<tbody>
<?php
	foreach($queuebanners as $ad) {
		$wpnonceaction = 'adrotate_email_advertiser_'.$ad['id'];
		$nonce = wp_create_nonce($wpnonceaction);
		
		$class = $errorclass = '';
		if('alternate' == $class) $class = 'alternate'; else $class = '';
		if($ad['type'] == 'error' OR $ad['type'] == 'a_error') $errorclass = ' row_error';
		if($ad['type'] == 'reject') $errorclass = ' row_urgent';

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
	    <tr id='banner-<?php echo $ad['id']; ?>' class='<?php echo $class.$errorclass; ?>'>
			<td><center><?php echo $ad['id'];?></center></td>
			<td>
				<strong><a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-advertiser&view=edit&ad='.$ad['id']);?>" title="<?php _e('Edit', 'adrotate'); ?>"><?php echo stripslashes(html_entity_decode($ad['title']));?></a></strong>
				<span style="color:#999;">
					<?php if($ad['crate'] > 0 OR $ad['irate'] > 0) {
						echo '<br /><span style="font-weight:bold;">'.__('Budget:', 'adrotate').'</span> '.number_format($ad['budget'], 2, '.', '').' - '; 
						echo __('CPC:', 'adrotate').' '.number_format($ad['crate'], 2, '.', '').' - ';
						echo __('CPM:', 'adrotate').' '.number_format($ad['irate'], 2, '.', '');
					} ?>
				</span>
			</td>
			<td><center><?php echo $mobile;?></center></td>
			<td><a href="admin.php?page=adrotate-advertiser&view=message&request=renew&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Renew', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=remove&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Remove', 'adrotate'); ?></a> - <a href="admin.php?page=adrotate-advertiser&view=message&request=other&id=<?php echo $ad['id']; ?>&_wpnonce=<?php echo $nonce; ?>"><?php _e('Other', 'adrotate'); ?></a></td>
		</tr>
		<?php } ?>
	</tbody>

</table>
<p><center>
	<span style="border: 1px solid #e6db55; height: 12px; width: 12px; background-color: #ffffe0">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has configuration errors.", "adrotate"); ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has been rejected.", "adrotate"); ?>
</center></p>