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
<h3><?php _e('General Settings', 'adrotate'); ?></h3>
<span class="description"><?php _e('General settings for AdRotate.', 'adrotate'); ?></span>
<table class="form-table">			
	<tr>
		<th valign="top"><?php _e('Text widgets', 'adrotate'); ?></th>
		<td><label for="adrotate_textwidget_shortcodes"><input type="checkbox" name="adrotate_textwidget_shortcodes" <?php if($adrotate_config['textwidget_shortcodes'] == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Enable if your theme does not support shortcodes in the WordPress text widget. (This does not always work!)', 'adrotate'); ?></label></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Load jQuery', 'adrotate'); ?></th>
		<td><label for="adrotate_jquery"><input type="checkbox" name="adrotate_jquery" <?php if($adrotate_config['jquery'] == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Enable if your theme does not load jQuery. jQuery is required for dynamic groups, statistics and some other features.', 'adrotate'); ?></label></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Load scripts in footer?', 'adrotate'); ?></th>
		<td><label for="adrotate_jsfooter"><input type="checkbox" name="adrotate_jsfooter" <?php if($adrotate_config['jsfooter'] == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Enable if you want to load all AdRotate Javascripts in the footer of your site.', 'adrotate'); ?></label></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Adblock disguise', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_adblock_disguise"><input name="adrotate_adblock_disguise" type="text" class="search-input" size="5" value="<?php echo $adrotate_config['adblock_disguise']; ?>" autocomplete="off" /> <?php _e('Leave empty to disable. Use only lowercaps letters. For example:', 'adrotate'); ?> <?php echo adrotate_rand(6); ?><br />
			<span class="description"><?php _e('Try and avoid adblock plugins in most modern browsers when using shortcodes.', 'adrotate'); ?><br /><?php _e('To also apply this feature to widgets, use a text widget with a shortcode instead of the AdRotate widget.', 'adrotate'); ?><br /><?php _e('Avoid the use of obvious keywords or filenames in your adverts or this feature will have little effect!', 'adrotate'); ?></span>
		</td>
	</tr>
</table>

<h3><?php _e('Banner Folder', 'adrotate'); ?></h3>
<span class="description"><?php _e('Set a location where your banner images will be stored.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Location', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_banner_folder"><?php echo site_url(); ?>/<input name="adrotate_banner_folder" type="text" class="search-input" size="30" value="<?php echo $adrotate_config['banner_folder']; ?>" autocomplete="off" /> <?php _e('(Default: wp-content/banners/).', 'adrotate'); ?><br />
			<span class="description"><?php _e('To try and trick ad blockers you could set the folder to something crazy like:', 'adrotate'); ?> "/wp-content/<?php echo adrotate_rand(12); ?>/".<br />
			<?php _e("This folder will not be automatically created if it doesn't exist. AdRotate will show errors when the folder is missing.", 'adrotate'); ?></span>
		</td>
	</tr>
</table>

<h3><?php _e('Bot filter', 'adrotate'); ?></h3>
<span class="description"><?php _e('The bot filter is used for Geo Targeting and the AdRotate stats tracker.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('User-Agent Filter', 'adrotate'); ?></th>
		<td>
			<textarea name="adrotate_crawlers" cols="90" rows="15"><?php echo $crawlers; ?></textarea><br />
			<span class="description"><?php _e('A comma separated list of keywords. Filter out bots/crawlers/user-agents.', 'adrotate'); ?><br />
			<?php _e('Keep in mind that this might give false positives. The word \'fire\' also matches \'firefox\', but not vice-versa. So be careful!', 'adrotate'); ?><br />
			<?php _e('Only words with alphanumeric characters and [ - _ ] are allowed. All other characters are stripped out.', 'adrotate'); ?><br />
			<?php _e('Additionally to the list specified here, empty User-Agents are blocked as well.', 'adrotate'); ?> (<?php _e('Learn more about', 'adrotate'); ?> <a href="http://en.wikipedia.org/wiki/User_agent" title="User Agents" target="_blank"><?php _e('user-agents', 'adrotate'); ?></a>.)</span>
		</td>
	</tr>
</table>

<h3><?php _e('Ad Blocker detection', 'adrotate'); ?></h3>
<span class="description"><?php _e('Try and detect ad blockers and show a message to those users. Make sure jQuery is enabled under Javascript.', 'adrotate'); ?><br /><?php _e('Some ad block plugins know of this feature and try to block it. If this is happens to your site consider using the Adblock Disguise feature above.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Enable', 'adrotate'); ?></th>
		<td><label for="adrotate_adblock"><input type="checkbox" name="adrotate_adblock" <?php if($adrotate_config['adblock'] == 'Y') { ?>checked="checked" <?php } ?> /><span class="description"><?php _e('Enable the ad block detection script.', 'adrotate'); ?></span></label>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Logged in users', 'adrotate'); ?></th>
		<td><input type="checkbox" name="adrotate_adblock_loggedin" <?php if($adrotate_config['adblock_loggedin'] == 'Y') { ?>checked="checked" <?php } ?> /><span class="description"><?php _e('Show the message to logged in users?', 'adrotate'); ?></span></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Show message for', 'adrotate'); ?></th>
		<td><select name="adrotate_adblock_timer">
			<option value="1" <?php if($adrotate_config['adblock_timer'] == 1) { echo 'selected'; } ?>>1</option>
			<option value="2" <?php if($adrotate_config['adblock_timer'] == 2) { echo 'selected'; } ?>>2</option>
			<option value="3" <?php if($adrotate_config['adblock_timer'] == 3) { echo 'selected'; } ?>>3</option>
			<option value="4" <?php if($adrotate_config['adblock_timer'] == 4) { echo 'selected'; } ?>>4</option>
			<option value="5" <?php if($adrotate_config['adblock_timer'] == 5) { echo 'selected'; } ?>>5</option>
			<option value="6" <?php if($adrotate_config['adblock_timer'] == 6) { echo 'selected'; } ?>>6</option>
			<option value="7" <?php if($adrotate_config['adblock_timer'] == 7) { echo 'selected'; } ?>>7</option>
			<option value="8" <?php if($adrotate_config['adblock_timer'] == 8) { echo 'selected'; } ?>>8</option>
			<option value="9" <?php if($adrotate_config['adblock_timer'] == 9) { echo 'selected'; } ?>>9</option>
			<option value="10" <?php if($adrotate_config['adblock_timer'] == 10) { echo 'selected'; } ?>>10</option>
			<option value="15" <?php if($adrotate_config['adblock_timer'] == 15) { echo 'selected'; } ?>>15</option>
			<option value="20" <?php if($adrotate_config['adblock_timer'] == 20) { echo 'selected'; } ?>>20</option>
		</select> <?php _e('Seconds.', 'adrotate'); ?><br /><span class="description"><?php _e('More seconds means you hinder your visitors more, which may drive them away. Use with caution!', 'adrotate'); ?></span>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Message to show', 'adrotate'); ?></th>
		<td><input name="adrotate_adblock_message" type="text" class="search-input" size="50" value="<?php echo $adrotate_config['adblock_message']; ?>" autocomplete="off" /><br />
		<span class="description"><?php _e('Default: "Ad blocker detected! Please wait %time% seconds or disable your ad blocker!"', 'adrotate'); ?><br />
		<?php _e('No HTML/Javascript allowed. %time% will be replaced with a countdown in seconds.', 'adrotate'); ?></span>
		</td>
	</tr>
</table>