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
<h3><?php _e('Advertisers', 'adrotate'); ?></h3>
<span class="description"><?php _e('Enable advertisers so they can review and manage their own ads.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Enable Advertisers', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_enable_advertisers"><input type="checkbox" name="adrotate_enable_advertisers" <?php if($adrotate_config['enable_advertisers'] == 'Y') { ?>checked="checked" <?php } ?> /> <?php _e('Allow adverts to be coupled to users (Advertisers).', 'adrotate'); ?></label>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Edit/update adverts', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_enable_editing"><input type="checkbox" name="adrotate_enable_editing" <?php if($adrotate_config['enable_editing'] == 'Y') { ?>checked="checked" <?php } ?> /> <?php _e('Allow advertisers to add new or edit their adverts.', 'adrotate'); ?></label>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Geo Targeting', 'adrotate'); ?></th>
		<td>
			<input type="checkbox" name="adrotate_enable_geo_advertisers" <?php if($adrotate_config['enable_geo_advertisers'] == 1) { ?>checked="checked" <?php } ?> /> <?php _e('Allow advertisers to specify where their ads will show. Geo Targeting has to be enabled, too.', 'adrotate'); ?>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Advertiser role', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_role"><input type="checkbox" name="adrotate_role" <?php if(is_object(get_role('adrotate_advertiser'))) { ?>checked="checked" <?php } ?> /> <?php _e('Create a seperate user role for your advertisers.', 'adrotate'); ?></label><br />
			<span class="description"><?php _e('Don\'t forget to give these users access to their advertiser dashboard via the Roles tab.', 'adrotate'); ?></span>
		</td>
	</tr>
</table>