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
<h3><?php _e('AdRotate Pro License', 'adrotate'); ?></h3>
<span class="description"><?php _e('Activate your AdRotate Pro License to receive automatic updates and be eligble for premium support.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('License Type', 'adrotate'); ?></th>
		<td>
			<?php echo ($adrotate_activate['type'] != '') ? $subscription.$adrotate_activate['type'] : __('Not activated - Not eligible for support and updates.', 'adrotate'); ?>
		</td>
	</tr>
	<?php if($adrotate_hide_license == 0 AND !$adrotate_is_networked) { ?>
	<tr>
		<th valign="top"><?php _e('License Key', 'adrotate'); ?></th>
		<td>
			<input name="adrotate_license_key" type="text" class="search-input" size="50" value="<?php echo $adrotate_activate['key']; ?>" autocomplete="off" <?php echo ($adrotate_activate['status'] == 1) ? 'disabled' : ''; ?> /> <span class="description"><?php _e('You can find the license key in your order email.', 'adrotate'); ?></span>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('License Email', 'adrotate'); ?></th>
		<td>
			<input name="adrotate_license_email" type="text" class="search-input" size="50" value="<?php echo $adrotate_activate['email']; ?>" autocomplete="off" <?php echo ($adrotate_activate['status'] == 1) ? 'disabled' : ''; ?> /> <span class="description"><?php _e('The email address you used in your purchase of AdRotate Pro.', 'adrotate'); ?></span>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Hide License Details', 'adrotate'); ?></th>
		<td>
			<input type="checkbox" name="adrotate_license_hide" <?php echo ($adrotate_activate['status'] == 1) ? 'disabled' : ''; ?> /> <span class="description"><?php _e('If you have installed AdRotate Pro for a client or in a multisite setup and want to hide the License Key, Email and Mass-deactivation button (Duo, Multi and Developer License) from them.', 'adrotate'); ?></span>
		</td>
	</tr>
	<?php if($adrotate_activate['status'] == 1) { ?>
	<tr>
		<th valign="top"><?php _e('Force de-activate', 'adrotate'); ?></th>
		<td>
			<input type="checkbox" name="adrotate_license_force" /> <span class="description"><?php _e('If your yearly subscription has expired you may need to force de-activate the license before you can activate again after renewing your subscription.', 'adrotate'); ?></span>
		</td>
	</tr>
	<?php } ?>
	<?php } ?>
</table>

<?php if(!$adrotate_is_networked) { ?>
	<p class="submit">
		<?php if($adrotate_activate['status'] == 0) { ?>
		<input type="submit" id="post-role-submit" name="adrotate_license_activate" value="<?php _e('Activate license', 'adrotate'); ?>" class="button-primary" />
		<?php } else { ?>
		<input type="submit" id="post-role-submit" name="adrotate_license_deactivate" value="<?php _e('De-activate license', 'adrotate'); ?>" class="button-primary" />
		<?php } ?>
		&nbsp;&nbsp;<em><?php _e('Click only once! this may take a few seconds.', 'adrotate'); ?></em>
	</p>
<?php } ?>