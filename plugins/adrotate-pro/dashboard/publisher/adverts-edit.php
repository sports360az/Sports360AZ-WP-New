<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

if(!$ad_edit_id) { 
	$edit_id = $wpdb->get_var("SELECT `id` FROM `{$wpdb->prefix}adrotate` WHERE `type` = 'empty' ORDER BY `id` DESC LIMIT 1;");
	if($edit_id == 0) {
	    $wpdb->insert($wpdb->prefix."adrotate", array('title' => '', 'bannercode' => '', 'thetime' => $now, 'updated' => $now, 'author' => $current_user->user_login, 'imagetype' => 'dropdown', 'image' => '', 'link' => '', 'tracker' => 'N', 'mobile' => 'N', 'tablet' => 'N', 'responsive' => 'N', 'type' => 'empty', 'weight' => 6, 'sortorder' => 0, 'budget' => 0, 'crate' => 0, 'irate' => 0, 'cities' => serialize(array()), 'countries' => serialize(array())));
	    $edit_id = $wpdb->insert_id;
	}
	$ad_edit_id = $edit_id;
}

$edit_banner = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}adrotate` WHERE `id` = '$ad_edit_id';");
$groups	= $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' ORDER BY `sortorder` ASC, `id` ASC;"); 
$schedules = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}adrotate_schedule` WHERE `name` != '' AND `stoptime` > $now ORDER BY `id` ASC;");
if($adrotate_config['enable_advertisers'] == 'Y') {
	$user_list = $wpdb->get_results("SELECT `ID`, `display_name` FROM `$wpdb->users` ORDER BY `user_nicename` ASC;");
	$saved_user = $wpdb->get_var("SELECT `user` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `group` = 0 AND `schedule` = 0;");
} else {
	$user_list = $saved_user = 0;
}
$linkmeta = $wpdb->get_results("SELECT `group` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `user` = 0 AND `schedule` = 0;");
$schedulemeta = $wpdb->get_results("SELECT `schedule` FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '$edit_banner->id' AND `group` = 0 AND `user` = 0;");

wp_enqueue_media();
wp_enqueue_script('uploader-hook', plugins_url().'/adrotate-pro/library/uploader-hook.js', array('jquery'));

$meta_array = $schedule_array = '';
foreach($linkmeta as $meta) {
	$meta_array[] = $meta->group;
	unset($meta);
}

foreach($schedulemeta as $meta) {
	$schedule_array[] = $meta->schedule;
	unset($meta);
}

if(!is_array($meta_array)) $meta_array = array();
if(!is_array($schedule_array)) $schedule_array = array();

$smonth = date("m", $now);
$emonth = date("m", $in84days);

if($ad_edit_id) {
	if($edit_banner->type != 'empty') {
		// Errors
		if(strlen($edit_banner->bannercode) < 1 AND $edit_banner->type != 'empty') 
			echo '<div class="error"><p>'. __('The AdCode cannot be empty!', 'adrotate').'</p></div>';

		if($edit_banner->tracker == 'N' AND $saved_user > 0) 
			echo '<div class="error"><p>'. __('You have set an advertiser but didn\'t enable tracking!', 'adrotate').'</p></div>';

		if(!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image != '') 
			echo '<div class="error"><p>'. __('You did not use %image% in your AdCode but did select a file to use!', 'adrotate').'</p></div>';

		if(preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->image == '') 
			echo '<div class="error"><p>'. __('You did use %image% in your AdCode but did not select a file to use!', 'adrotate').'</p></div>';
		
		if(!preg_match("/%image%/i", $edit_banner->bannercode) AND $edit_banner->responsive == 'Y') 
			echo '<div class="error"><p>'. __('You did not use %image% in your AdCode. The responsive feature will be ineffective.', 'adrotate').'</p></div>';
		
		if((($edit_banner->imagetype != '' AND $edit_banner->image == '') OR ($edit_banner->imagetype == '' AND $edit_banner->image != ''))) 
			echo '<div class="error"><p>'. __('There is a problem saving the asset. Please try again with the media picker and re-save the advert!', 'adrotate').'</p></div>';
		
		if(strlen($edit_banner->image) > 0 AND !preg_match("/full/", $edit_banner->image) AND $edit_banner->responsive == 'Y') 
			echo '<div class="error"><p>'. __('Responsive is enabled but your banner image has the wrong name.', 'adrotate').'</p></div>';

		if(($edit_banner->crate > 0 OR $edit_banner->irate > 0) AND $edit_banner->budget < 1) 
			echo '<div class="error"><p>'. __('This advert has run out of budget. Add more budget to the advert or reset the rate to zero!', 'adrotate').'</p></div>';
		
		if(count($schedule_array) == 0) 
			echo '<div class="error"><p>'. __('This ad has no schedules!', 'adrotate').'</p></div>';
		
		if(!preg_match_all('/<(a|script|embed|iframe)[^>](.*?)>/i', stripslashes(htmlspecialchars_decode($edit_banner->bannercode, ENT_QUOTES)), $things) AND $edit_banner->tracker == 'Y')
			echo '<div class="error"><p>'. __("Tracking is enabled but no valid link/tag was found in the adcode!", 'adrotate').'</p></div>';

		if($edit_banner->tracker == 'N' AND $edit_banner->crate > 0)
			echo '<div class="error"><p>'. __("A Click rate was set but Tracking is not active!", 'adrotate').'</p></div>';

		// Ad Notices
		$adstate = adrotate_evaluate_ad($edit_banner->id);
		if($edit_banner->type == 'error' AND $adstate == 'active')
			echo '<div class="error"><p>'. __('AdRotate cannot find an error but the ad is marked erroneous, try re-saving the ad!', 'adrotate').'</p></div>';

		if($edit_banner->type == 'reject')
			echo '<div class="error"><p>'. __('This advert has been rejected by staff Please adjust the ad to conform with the requirements!', 'adrotate').'</p></div>';

		if($edit_banner->type == 'queue')
			echo '<div class="error"><p>'. __('This advert is queued and awaiting review!', 'adrotate').'</p></div>';

		if($adstate == 'expired')
			echo '<div class="error"><p>'. __('This ad is expired and currently not shown on your website!', 'adrotate').'</p></div>';

		if($adstate == '2days')
			echo '<div class="updated"><p>'. __('The ad will expire in less than 2 days!', 'adrotate').'</p></div>';

		if($adstate == '7days')
			echo '<div class="updated"><p>'. __('This ad will expire in less than 7 days!', 'adrotate').'</p></div>';

		if($edit_banner->type == 'disabled') 
			echo '<div class="updated"><p>'. __('This ad has been disabled and does not rotate on your site!', 'adrotate').'</p></div>';
	}
}

// Determine image field
if($edit_banner->imagetype == "field") {
	$image_field = $edit_banner->image;
	$image_dropdown = '';
} else if($edit_banner->imagetype == "dropdown") {
	$image_field = '';
	$image_dropdown = $edit_banner->image;
} else {
	$image_field = '';
	$image_dropdown = '';
}
?>

	<!-- AdRotate JS -->
	<script type="text/javascript">
	jQuery(document).ready(function(){
	    function livePreview(){
	        var input = jQuery("#adrotate_bannercode").val();
	        if(jQuery("#adrotate_title").val().length > 0) var ad_title = jQuery("#adrotate_title").val();
	        var ad_image = '';
	        if(jQuery("#adrotate_image_dropdown").val().length > 0) var ad_image = '<?php echo site_url('/').$adrotate_config['banner_folder']; ?>'+jQuery("#adrotate_image_dropdown").val();
	        if(jQuery("#adrotate_image").val().length > 0) var ad_image = jQuery("#adrotate_image").val();
	
	        var input = input.replace(/%id%/g, <?php echo $edit_banner->id;?>);
	        var input = input.replace(/%title%/g, ad_title);
	        var input = input.replace(/%image%/g, ad_image);
	        var input = input.replace(/%random%/g, <?php echo rand(100000,999999); ?>);
	        jQuery("#adrotate_preview").html(input);
	    }       
	    livePreview();
	
	    jQuery('#adrotate_bannercode').on("paste change focus focusout input", function(){ livePreview(); });
	    jQuery('#adrotate_image').on("paste change focusout input", function(){ livePreview(); });
	    jQuery('#adrotate_image_dropdown').on("change", function(){ livePreview(); });
	});
	</script>
	<!-- /AdRotate JS -->

	<form method="post" action="admin.php?page=adrotate-ads">
	<?php wp_nonce_field('adrotate_save_ad','adrotate_nonce'); ?>
	<input type="hidden" name="adrotate_username" value="<?php echo $userdata->user_login;?>" />
	<input type="hidden" name="adrotate_id" value="<?php echo $edit_banner->id;?>" />
	<input type="hidden" name="adrotate_type" value="<?php echo $edit_banner->type;?>" />
	<input type="hidden" name="adrotate_link" value="<?php echo $edit_banner->link;?>" />

<?php if($edit_banner->type == 'empty') { ?>
	<h3><?php _e('New Advert', 'adrotate'); ?></h3>
<?php } else { ?> 
	<h3><?php _e('Edit Advert', 'adrotate'); ?></h3>
<?php } ?>

	<table class="widefat" style="margin-top: .5em">

		<tbody>
      	<tr>
	        <th width="15%"><?php _e('Title', 'adrotate'); ?></th>
	        <td colspan="2">
	        	<label for="adrotate_title"><input tabindex="1" id="adrotate_title" name="adrotate_title" type="text" size="50" class="search-input" value="<?php echo stripslashes($edit_banner->title);?>" autocomplete="off" /> <em><?php _e('Visible to Advertisers!', 'adrotate'); ?></em></label>
	        </td>
      	</tr>
      	<tr>
	        <th valign="top"><?php _e('AdCode', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_bannercode"><textarea tabindex="2" id="adrotate_bannercode" name="adrotate_bannercode" cols="65" rows="10"><?php echo stripslashes($edit_banner->bannercode); ?></textarea></label>
	        </td>
	        <td width="30%">
		        <p><strong><?php _e('Basic Examples:', 'adrotate'); ?></strong></p>
				<p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;a href=&quot;http://www.adrotateforwordpress.com&quot;&gt;&lt;img src=&quot;%image%&quot; /&gt;&lt;/a&gt;');return false;">&lt;a href="http://www.adrotateforwordpress.com"&gt;&lt;img src="%image%" /&gt;&lt;/a&gt;</a></em></p>
		        <p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;span class=&quot;ad-%id%&quot;&gt;&lt;a href=&quot;http://www.adrotateforwordpress.com&quot;&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;');return false;">&lt;span class="ad-%id%"&gt;&lt;a href="http://www.adrotateforwordpress.com"&gt;Text Link Ad!&lt;/a&gt;&lt;/span&gt;</a></em></p>
		        <p><em><a href="#" onclick="textatcursor('adrotate_bannercode','&lt;iframe src=&quot;%image%&quot; height=&quot;250&quot; frameborder=&quot;0&quot; style=&quot;border:none;&quot;&gt;&lt;/iframe&gt;');return false;">&lt;iframe src=&quot;%image%&quot; height=&quot;250&quot; frameborder=&quot;0&quot; style=&quot;border:none;&quot;&gt;&lt;/iframe&gt;</a></em></p>
	        </td>
		</tr>
		<tr>
	        <th valign="top"><?php _e('Useful tags', 'adrotate'); ?></th>
	        <td colspan="2">
		        <p><em><a href="#" title="<?php _e('Insert the advert ID Number.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%id%');return false;">%id%</a>, <a href="#" title="<?php _e('Required when selecting a image/asset below.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%image%');return false;">%image%</a>, <a href="#" title="<?php _e('Insert the advert name.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%title%');return false;">%title%</a>, <a href="#" title="<?php _e('Insert a random seed. Useful for DFP/DoubleClick type adverts.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','%random%');return false;">%random%</a>, <a href="#" title="<?php _e('Add inside the <a> tag to open advert in a new window.', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','target=&quot;_blank&quot;');return false;">target="_blank"</a>, <a href="#" title="<?php _e('Add inside the <a> tag to tell crawlers to ignore this link', 'adrotate'); ?>" onclick="textatcursor('adrotate_bannercode','rel=&quot;nofollow&quot;');return false;">rel="nofollow"</a></em><br /><?php _e('Place the cursor in your AdCode where you want to add any of these tags and click to add it.', 'adrotate'); ?></p>
	        </td>
      	</tr>
      	<tr>
	        <th valign="top"><?php _e('Live Preview', 'adrotate'); ?></th>
	        <td colspan="2">
	        	<div id="adrotate_preview"></div>
		        <br /><em><?php _e('Note: While this preview is an accurate one, it might look different then it does on the website.', 'adrotate'); ?>
				<br /><?php _e('This is because of CSS differences. Your themes CSS file is not active here!', 'adrotate'); ?></em>
			</td>
      	</tr>
		<tr>
	        <th valign="top"><?php _e('Banner asset', 'adrotate'); ?></th>
			<td colspan="2">
				<label for="adrotate_image">
					<?php _e('WordPress media', 'adrotate'); ?> <input tabindex="3" id="adrotate_image" type="text" size="50" name="adrotate_image" value="<?php echo $image_field; ?>" /> <input tabindex="4" id="adrotate_image_button" class="button" type="button" value="<?php _e('Select Banner', 'adrotate'); ?>" />
				</label><br />
				<?php _e('- OR -', 'adrotate'); ?><br />
				<label for="adrotate_image_dropdown">
					<?php _e('Banner folder', 'adrotate'); ?> <select tabindex="5" id="adrotate_image_dropdown" name="adrotate_image_dropdown" style="min-width: 200px;">
   						<option value=""><?php _e('No file selected', 'adrotate'); ?></option>
						<?php echo adrotate_folder_contents($image_dropdown); ?>
					</select><br />
				</label>
				<em><?php _e('Use %image% in the adcode instead of the file path.', 'adrotate'); ?> <?php _e('Use either the text field or the dropdown. If the textfield has content that field has priority.', 'adrotate'); ?></em>
			</td>
		</tr>
		<?php if($adrotate_config['stats'] > 0) { ?>
      	<tr>
	        <th valign="top"><?php _e('Statistics', 'adrotate'); ?></th>
	        <td colspan="2">
	        	<label for="adrotate_tracker"><input tabindex="6" type="checkbox" name="adrotate_tracker" <?php if($edit_banner->tracker == 'Y') { ?>checked="checked" <?php } ?> /> <?php _e('Enable click and impression tracking for this advert.', 'adrotate'); ?> <br />
	        	<em><?php _e('Note: Clicktracking does not work for Javascript adverts such as those provided by Google AdSense/DFP/DoubleClick. HTML5/Flash adverts are not always supported.', 'adrotate'); ?></em>
		        </label>
	        </td>
      	</tr>
		<?php } ?>
      	<tr>
	        <th><?php _e('Status', 'adrotate'); ?></th>
	        <td colspan="3">
		        <label for="adrotate_active">
			        <select tabindex="7" name="adrotate_active">
						<option value="active" <?php if($edit_banner->type == "active") { echo 'selected'; } ?>><?php _e('Yes, this ad will be visible', 'adrotate'); ?></option>
						<option value="disabled" <?php if($edit_banner->type == "disabled") { echo 'selected'; } ?>><?php _e('Disabled, do not show this ad anywhere', 'adrotate'); ?></option>
						<?php if($adrotate_config['enable_advertisers'] == 'Y' AND $adrotate_config['enable_editing'] == 'Y' AND $saved_user > 0) { ?>
						<option value="queue" <?php if($edit_banner->type == "queue") { echo 'selected'; } ?>><?php _e('Maybe, this ad is queued for review', 'adrotate'); ?></option>
						<option value="reject" <?php if($edit_banner->type == "reject") { echo 'selected'; } ?>><?php _e('No, this ad is rejected', 'adrotate'); ?></option>
						<?php } ?>
					</select>
				</label>
			</td>
      	</tr>
		</tbody>

	</table>

	<p class="submit">
		<input tabindex="8" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save Advert', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-ads&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>
		
	<h3><?php _e('Usage', 'adrotate'); ?></h3>
	<table class="widefat" style="margin-top: .5em">
		<tbody>
      	<tr>
	        <th width="20%"><?php _e('Widget', 'adrotate'); ?></th>
	        <td colspan="3"><?php _e('Drag the AdRotate widget to the sidebar you want it in, select "Single Ad" and enter ID', 'adrotate'); ?> "<?php echo $edit_banner->id; ?>".</td>
      	</tr>
      	<tr>
	        <th><?php _e('In a post or page', 'adrotate'); ?></th>
	        <td>[adrotate banner="<?php echo $edit_banner->id; ?>"]</td>
	        <th width="20%"><?php _e('Directly in a theme', 'adrotate'); ?></th>
	        <td>&lt;?php echo adrotate_ad(<?php echo $edit_banner->id; ?>); ?&gt;</td>
      	</tr>
      	</tbody>
	</table>

	<h3><?php _e('Create a schedule', 'adrotate'); ?></h3>
	<table class="widefat" style="margin-top: .5em">
     	<tr>
	        <th width="20%"><?php _e('Start date (day/month/year)', 'adrotate'); ?></th>
	        <td width="30%">
	        	<label for="adrotate_sday">
	        	<input tabindex="9" name="adrotate_sday" class="search-input" type="text" size="4" maxlength="2" value="" /> /
				<select tabindex="10" name="adrotate_smonth">
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
				<input tabindex="11" name="adrotate_syear" class="search-input" type="text" size="4" maxlength="4" value="" />&nbsp;&nbsp;&nbsp; 
				</label>
	        </td>
	        <th width="20%"><?php _e('End date (day/month/year)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_eday">
	        	<input tabindex="12" name="adrotate_eday" class="search-input" type="text" size="4" maxlength="2" value=""  /> /
				<select tabindex="13" name="adrotate_emonth">
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
				<input tabindex="14" name="adrotate_eyear" class="search-input" type="text" size="4" maxlength="4" value="" />&nbsp;&nbsp;&nbsp; 
				</label>
			</td>
      	</tr>	
      	<tr>
	        <th><?php _e('Start time (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_sday">
				<input tabindex="15" name="adrotate_shour" class="search-input" type="text" size="2" maxlength="4" value="" /> :
				<input tabindex="16" name="adrotate_sminute" class="search-input" type="text" size="2" maxlength="4" value="" />
				</label>
	        </td>
	        <th><?php _e('End time (hh:mm)', 'adrotate'); ?></th>
	        <td>
	        	<label for="adrotate_eday">
				<input tabindex="17" name="adrotate_ehour" class="search-input" type="text" size="2" maxlength="4" value="" /> :
				<input tabindex="18" name="adrotate_eminute" class="search-input" type="text" size="2" maxlength="4" value="" />
				</label>
			</td>
      	</tr>	
		<?php if($adrotate_config['stats'] == 1) { ?>
      	<tr>
      		<th><?php _e('Maximum Clicks', 'adrotate'); ?></th>
	        <td><input tabindex="19" name="adrotate_maxclicks" type="text" size="5" class="search-input" autocomplete="off" value="" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
		    <th><?php _e('Maximum Impressions', 'adrotate'); ?></th>
	        <td><input tabindex="20" name="adrotate_maxshown" type="text" size="5" class="search-input" autocomplete="off" value="" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Spread Impressions', 'adrotate'); ?></th>
			<td colspan="3"><label for="adrotate_spread"><input tabindex="21" type="checkbox" name="adrotate_spread" value="1" /> <?php _e('Try to evenly spread impressions for each advert over the duraction of this schedule. This may cause adverts to intermittently not show.', 'adrotate'); ?></label></td>
		</tr>
		<?php } ?>
	</table>
	<p><em><strong><?php _e('Note:', 'adrotate'); ?></strong> <?php _e('Time uses a 24 hour clock. When you\'re used to the AM/PM system keep this in mind: If the start or end time is after lunch, add 12 hours. 2PM is 14:00 hours. 6AM is 6:00 hours.', 'adrotate'); ?></em></p>

	<h3><?php _e('Choose Schedules', 'adrotate'); ?></h3>
	<p><em><?php _e('You can add, edit or delete schedules from the', 'adrotate'); ?>  '<a href="admin.php?page=adrotate-schedules"><?php _e('Manage Schedules', 'adrotate'); ?></a>' <?php _e('dashboard. Save your advert first!', 'adrotate'); ?></em></p>
	<table class="widefat" style="margin-top: .5em">

		<thead>
		<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
	        <th width="4%"><?php _e('ID', 'adrotate'); ?></th>
	        <th width="20%"><?php _e('Start / End', 'adrotate'); ?></th>
	        <th>&nbsp;</th>
	        <?php if($adrotate_config['stats'] == 1) { ?>
		        <th width="10%"><center><?php _e('Max Shown', 'adrotate'); ?></center></th>
		        <th width="10%"><center><?php _e('Max Clicks', 'adrotate'); ?></center></th>
			<?php } ?>
		</tr>
		</thead>

		<tbody>
		<?php
		$class = '';
		foreach($schedules as $schedule) {
			if(!in_array($schedule->id, $schedule_array) AND $adrotate_config['hide_schedules'] == "Y") continue;
			if($adrotate_config['stats'] == 1) {
				if($schedule->maxclicks == 0) $schedule->maxclicks = '&infin;';
				if($schedule->maximpressions == 0) $schedule->maximpressions = '&infin;';
			}

			$class = ('alternate' != $class) ? 'alternate' : '';
			if(in_array($schedule->id, $schedule_array)) $class = 'row_active'; 
			if($schedule->stoptime < $in2days) $class = 'row_urgent';

			$sdayhour = substr($schedule->daystarttime, 0, 2);
			$sdayminute = substr($schedule->daystarttime, 2, 2);
			$edayhour = substr($schedule->daystoptime, 0, 2);
			$edayminute = substr($schedule->daystoptime, 2, 2);
			$tick = '<img src="'.plugins_url('../../images/tick.png', __FILE__).'" width="10" height"10" />';
			$cross = '<img src="'.plugins_url('../../images/cross.png', __FILE__).'" width="10" height"10" />';
		?>
      	<tr id='schedule-<?php echo $schedule->id; ?>' class='<?php echo $class; ?>'>
			<th class="check-column"><input type="checkbox" name="scheduleselect[]" value="<?php echo $schedule->id; ?>" <?php if(in_array($schedule->id, $schedule_array)) echo "checked"; ?> /></th>
			<td><?php echo $schedule->id; ?></td>
			<td><?php echo date_i18n("F d, Y H:i", $schedule->starttime);?><br /><span style="color: <?php echo adrotate_prepare_color($schedule->stoptime);?>;"><?php echo date_i18n("F d, Y H:i", $schedule->stoptime);?></span></td>
			<td><a href="<?php echo admin_url('/admin.php?page=adrotate-schedules&view=edit&schedule='.$schedule->id);?>"><?php echo stripslashes(html_entity_decode($schedule->name)); ?></a><span style="color:#999;"><br /><?php _e('Mon:', 'adrotate'); ?> <?php echo ($schedule->day_mon == 'Y') ? $tick : $cross; ?> <?php _e('Tue:', 'adrotate'); ?> <?php echo ($schedule->day_tue == 'Y') ? $tick : $cross; ?> <?php _e('Wed:', 'adrotate'); ?> <?php echo ($schedule->day_wed == 'Y') ? $tick : $cross; ?> <?php _e('Thu:', 'adrotate'); ?> <?php echo ($schedule->day_thu == 'Y') ? $tick : $cross; ?> <?php _e('Fri:', 'adrotate'); ?> <?php echo ($schedule->day_fri == 'Y') ? $tick : $cross; ?> <?php _e('Sat:', 'adrotate'); ?> <?php echo ($schedule->day_sat == 'Y') ? $tick : $cross; ?> <?php _e('Sun:', 'adrotate'); ?> <?php echo ($schedule->day_sun == 'Y') ? $tick : $cross; ?> <?php if($schedule->daystarttime  > 0) { ?><?php _e('Between:', 'adrotate'); ?> <?php echo $sdayhour; ?>:<?php echo $sdayminute; ?> - <?php echo $edayhour; ?>:<?php echo $edayminute; ?> <?php } ?><?php if($schedule->spread == 'Y') { ?><br />Max. <?php echo $schedule->dayimpressions; ?> <?php _e('impressions per day', 'adrotate'); ?></span><?php } ?></span></td>
	        <?php if($adrotate_config['stats'] == 1) { ?>
		        <td><center><?php echo $schedule->maximpressions; ?></center></td>
		        <td><center><?php echo $schedule->maxclicks; ?></center></td>
			<?php } ?>
      	</tr>
      	<?php } ?>
		</tbody>

	</table>
	<p><center>
		<?php if($adrotate_config['hide_schedules'] == "Y") { ?><?php _e("Schedules not in use by this advert are hidden.", "adrotate"); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
		<span style="border: 1px solid #518257; height: 12px; width: 12px; background-color: #e5faee">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("In use by this advert.", "adrotate"); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
	</center></p>

	<p class="submit">
		<input tabindex="22" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save Advert', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-ads&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>

	<h3><?php _e('Advanced', 'adrotate'); ?></h3>
	<p><em><?php _e('Everything below is optional.', 'adrotate'); ?></em></p>
	<table class="widefat" style="margin-top: .5em">

		<tbody>
      	<tr>
	        <th width="15%" valign="top"><?php _e('Mobile', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<?php _e('Show only on;', 'adrotate'); ?>&nbsp;&nbsp;<label for="adrotate_mobile"><input tabindex="23" type="checkbox" name="adrotate_mobile" <?php if($edit_banner->mobile == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Smartphones', 'adrotate'); ?></label>&nbsp;&nbsp;<label for="adrotate_tablet"><input tabindex="23" type="checkbox" name="adrotate_tablet" <?php if($edit_banner->tablet == 'Y') { ?>checked="checked" <?php } ?> /><?php _e('Tablets.', 'adrotate'); ?></label> <br />
	        	<em><?php _e('Also enable mobile support in the group this advert goes in.', 'adrotate'); ?></em>
	        </td>
      	</tr>
      	<tr>
	        <th width="15%" valign="top"><?php _e('Responsive', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<label for="adrotate_responsive"><input tabindex="24" type="checkbox" name="adrotate_responsive" <?php if($edit_banner->responsive == 'Y') { ?>checked="checked" <?php } ?> /> <?php _e('Enable responsive support for this advert.', 'adrotate'); ?></label><br />
		        <em><?php _e('Upload your images to the banner folder and make sure the filename is in the following format; "imagename.full.ext". A full set of sized images is strongly recommended.', 'adrotate'); ?></em><br />
		        <em><?php _e('For smaller size images use ".320", ".480", ".768" or ".1024" in the filename instead of ".full" for the various viewports.', 'adrotate'); ?></em><br />
		        <em><strong><?php _e('Example:', 'adrotate'); ?></strong> <?php _e('image.full.jpg, image.320.jpg and image.768.jpg will serve the same advert for different viewports.', 'adrotate'); ?></em></label>
	        </td>
      	</tr>
      	<tr>
		    <th valign="top"><?php _e('Weight', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<label for="adrotate_weight">
	        	&nbsp;<input type="radio" tabindex="24" name="adrotate_weight" value="2" <?php if($edit_banner->weight == "2") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Barely visible', 'adrotate'); ?><br />
	        	&nbsp;<input type="radio" tabindex="25" name="adrotate_weight" value="4" <?php if($edit_banner->weight == "4") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Less than average', 'adrotate'); ?><br />
	        	&nbsp;<input type="radio" tabindex="26" name="adrotate_weight" value="6" <?php if($edit_banner->weight == "6") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Normal visibility', 'adrotate'); ?><br />
	        	&nbsp;<input type="radio" tabindex="27" name="adrotate_weight" value="8" <?php if($edit_banner->weight == "8") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('More than average', 'adrotate'); ?><br />
	        	&nbsp;<input type="radio" tabindex="28" name="adrotate_weight" value="10" <?php if($edit_banner->weight == "10") { echo 'checked'; } ?> />&nbsp;&nbsp;&nbsp;<?php _e('Best visibility', 'adrotate'); ?>
	        	</label>
			</td>
		</tr>
      	<tr>
	        <th><?php _e('Sortorder', 'adrotate'); ?></th>
	        <td colspan="3">
		        <label for="adrotate_sortorder"><input tabindex="29" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_banner->sortorder;?>" /> <em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to ad id.', 'adrotate'); ?></em></label>
			</td>
      	</tr>
		</tbody>

	</table>

	<?php if($adrotate_config['enable_geo'] > 0) { ?>
	<?php $cities = unserialize(stripslashes($edit_banner->cities)); ?>
	<?php $countries = unserialize(stripslashes($edit_banner->countries)); ?>
	<h3><?php _e('Geo Targeting', 'adrotate'); ?></h3>
	<p><em><?php _e('Assign the advert to a group and enable that group to use Geo Targeting.', 'adrotate'); ?></em></p>
	<table class="widefat" style="margin-top: .5em">			

		<tbody>
	    <tr>
			<th width="15%" valign="top"><?php _e('Cities/States', 'adrotate'); ?></strong></th>
			<td colspan="3">
				<textarea tabindex="31" name="adrotate_geo_cities" cols="85" rows="5"><?php echo (is_array($cities)) ? implode(', ', $cities) : ''; ?></textarea><br />
		        <p><em><?php _e('A comma separated list of cities (or the Metro ID) and/or states (Also the states ISO codes are supported)', 'adrotate'); ?> (Alkmaar, Philadelphia, Melbourne, ...)<br /><?php _e('AdRotate does not check the validity of names so make sure you spell them correctly!', 'adrotate'); ?></em></p>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Countries', 'adrotate'); ?></strong></th>
	        <td colspan="2">
		        <label for="adrotate_geo_countries">
			        <div class="adrotate-select">
			        <?php echo adrotate_select_countries($countries); ?>
					</div>
		        </label>
		        <p><em><?php _e('Select the countries you want the adverts to show in.', 'adrotate'); ?> <?php _e('Cities take priority and will be filtered first.', 'adrotate'); ?></em></p>
			</td>
		</tr>
		</tbody>

	</table>
  	<?php } ?>

	<?php if($adrotate_config['enable_advertisers'] == 'Y') { ?>
	<h3><?php _e('Advertiser', 'adrotate'); ?></h3>
	<table class="widefat" style="margin-top: .5em">	

		<tbody>
      	<tr>
	        <th width="15%" valign="top"><?php _e('Advertiser', 'adrotate'); ?></th>
	        <td colspan="3">
	        	<label for="adrotate_tracker">
	        	<select tabindex="32" name="adrotate_advertiser" style="min-width: 200px;">
					<option value="0" <?php if($saved_user == '0') { echo 'selected'; } ?>><?php _e('Not specified', 'adrotate'); ?></option>
				<?php 
				foreach($user_list as $user) {
					if($user->ID == $userdata->ID) $you = ' (You)';
						else $you = '';
				?>
					<option value="<?php echo $user->ID; ?>"<?php if($saved_user == $user->ID) { echo ' selected'; } ?>><?php echo $user->display_name; ?><?php echo $you; ?></option>
				<?php } ?>
				</select>
		        <em><?php _e('Must be a registered user on your site with appropriate access roles.', 'adrotate'); ?></em>
		        </label>
			</td>
      	</tr>
        <?php if($adrotate_config['stats'] == 1) { ?>
     	<tr>
	        <th><?php _e('Advert Budget', 'adrotate'); ?></th>
	        <td colspan="3"><label for="adrotate_budget"><input tabindex="33" name="adrotate_budget" type="text" size="10" class="search-input" autocomplete="off" value="<?php echo number_format($edit_banner->budget, 4, '.', '');?>" /> <em><?php _e('When this reaches 0, the advert will expire.', 'adrotate'); ?></em></label></td>
      	</tr>
      	<tr>
	        <th width="15%"><?php _e('Cost-per-Click', 'adrotate'); ?></th>
	        <td><label for="adrotate_crate"><input tabindex="34" name="adrotate_crate" type="text" size="10" class="search-input" autocomplete="off" value="<?php echo number_format($edit_banner->crate, 4, '.', '');?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></label></td>
	        <th width="15%"><?php _e('Cost-per-Mille', 'adrotate'); ?></th>
	        <td><label for="adrotate_irate"><input tabindex="35" name="adrotate_irate" type="text" size="10" class="search-input" autocomplete="off" value="<?php echo number_format($edit_banner->irate, 4, '.', '');?>" /> <em><?php _e('Leave empty or 0 to skip this.', 'adrotate'); ?></em></label></td>
      	</tr>
	  	<?php } ?>
      	</tbody>

	</table>
  	<?php } ?>

	<h3><?php _e('Usage', 'adrotate'); ?></h3>
	<table class="widefat" style="margin-top: .5em">

		<tbody>
      	<tr>
	        <th width="15%"><?php _e('Widget', 'adrotate'); ?></th>
	        <td colspan="3"><?php _e('Drag the AdRotate widget to the sidebar you want it in, select "Single Ad" and enter ID', 'adrotate'); ?> "<?php echo $edit_banner->id; ?>".</td>
      	</tr>
      	<tr>
	        <th width="15%"><?php _e('In a post or page', 'adrotate'); ?></th>
	        <td width="30%">[adrotate banner="<?php echo $edit_banner->id; ?>"]</td>
	        <th width="15%"><?php _e('Directly in a theme', 'adrotate'); ?></th>
	        <td>&lt;?php echo adrotate_ad(<?php echo $edit_banner->id; ?>); ?&gt;</td>
      	</tr>
      	</tbody>
	
	</table>

	<p class="submit">
		<input tabindex="36" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save Advert', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-ads&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>

	<?php if($groups) { ?>
	<h3><?php _e('Select Groups', 'adrotate'); ?></h3>
	<table class="widefat" style="margin-top: .5em">
		<thead>
		<tr>
			<td scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></td>
			<th><?php _e('Choose groups', 'adrotate'); ?></th>
			<th width="5%"><center><?php _e('Ads', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Active', 'adrotate'); ?></center></th>
		</tr>
		</thead>

		<tbody>
		<?php 
		$class = '';
		foreach($groups as $group) {
			if($group->adspeed > 0) $adspeed = $group->adspeed / 1000;
	        if($group->modus == 0) $modus[] = __('Default rotation', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
	        if($group->modus == 1) $modus[] = $adspeed.' '. __('second rotation', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
	        if($group->modus == 2) $modus[] = $group->gridrows.'x'.$group->gridcolumns.' '. __('grid', 'adrotate').' ('.$group->adwidth.'x'.$group->adheight.'px)';
	        if($group->cat_loc > 0 OR $group->page_loc > 0) $modus[] = __('Post Injection', 'adrotate');
	        if($group->geo == 1 AND $adrotate_config['enable_geo'] > 0) $modus[] = __('Geolocation', 'adrotate');

			$ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}adrotate_linkmeta` WHERE `group` = ".$group->id." AND `user` = 0 AND `schedule` = 0;");
			$active_ads_in_group = $wpdb->get_var("SELECT COUNT(*) FROM  `{$wpdb->prefix}adrotate`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad` AND `type` = 'active' AND `group` = ".$group->id.";");
			$class = ('alternate' != $class) ? 'alternate' : ''; ?>
		    <tr id='group-<?php echo $group->id; ?>' class='<?php echo $class; ?>'>
				<th class="check-column" width="2%"><input type="checkbox" name="groupselect[]" value="<?php echo $group->id; ?>" <?php if(in_array($group->id, $meta_array)) echo "checked"; ?> /></th>
				<td><?php echo $group->id; ?> - <strong><?php echo $group->name; ?></strong><span style="color:#999;"><?php echo '<br /><span style="font-weight:bold;">'.__('Mode', 'adrotate').':</span> '.implode(', ', $modus); ?></span></td>
				<td><center><?php echo $ads_in_group; ?></center></td>
				<td><center><?php echo $active_ads_in_group; ?></center></td>
			</tr>
		<?php 
			unset($modus);
		} 
		?>
		</tbody>					
	</table>

	<p class="submit">
		<input tabindex="37" type="submit" name="adrotate_ad_submit" class="button-primary" value="<?php _e('Save Advert', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-ads&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>
	<?php } ?>
</form>