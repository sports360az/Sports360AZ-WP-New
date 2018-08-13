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
<h3><?php _e('Geo Targeting', 'adrotate'); ?></h3>
<span class="description"><?php _e('Target certain areas in the world for better advertising oppurtunities.', 'adrotate'); ?></span>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Which Geo Service', 'adrotate'); ?></th>
		<td>
			<select name="adrotate_enable_geo">
				<option value="0" <?php if($adrotate_config['enable_geo'] == 0) { echo 'selected'; } ?>><?php _e('Disabled', 'adrotate'); ?></option>
				<option value="5" <?php if($adrotate_config['enable_geo'] == 5) { echo 'selected'; } ?>>AdRotate Geo</option>
				<option value="4" <?php if($adrotate_config['enable_geo'] == 4) { echo 'selected'; } ?>>MaxMind City (Recommended)</option>
				<option value="3" <?php if($adrotate_config['enable_geo'] == 3) { echo 'selected'; } ?>>MaxMind Country</option>
				<option value="1" <?php if($adrotate_config['enable_geo'] == 1) { echo 'selected'; } ?>>Telize</option>
			</select><br />
			<span class="description">
				<strong>MaxMind</strong> - <a href="https://www.maxmind.com/en/geoip2-precision-services?rId=ajdgnet" target="_blank">GeoIP2 Precision</a> - <?php _e('The most complete and accurate geo targeting you can get for only $20 USD per 50000 lookups.', 'adrotate'); ?> <a href="https://www.maxmind.com/en/geoip2-precision-city?rId=ajdgnet" target="_blank"><?php _e('Buy now', 'adrotate'); ?>.</a><br />
				<em><strong>Supports:</strong> Countries, States, State ISO codes, Cities and DMA codes.</em><br /><br />					
				<strong>AdRotate Geo</strong> - <?php _e('50000 free lookups every day, uses GeoLite2 databases from MaxMind!', 'adrotate'); ?><br />
				<em><strong>Supports:</strong> Countries, Cities, DMA codes, States and State ISO codes.</em><br /><br />
				<strong>Telize</strong> - <?php _e('Free service, uses GeoLite2 databases from MaxMind!', 'adrotate'); ?><br />
				<em><strong>Supports:</strong> Countries, Cities and DMA codes.</em>
			</span>
		</td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Geo Cookie Lifespan', 'adrotate'); ?></th>
		<td>
			<label for="adrotate_geo_cookie_life"><select name="adrotate_geo_cookie_life">
				<option value="86400" <?php if($adrotate_config['geo_cookie_life'] == 86400) { echo 'selected'; } ?>>24 (<?php _e('Default', 'adrotate'); ?>)</option>
				<option value="129600" <?php if($adrotate_config['geo_cookie_life'] == 129600) { echo 'selected'; } ?>>36</option>
				<option value="172800" <?php if($adrotate_config['geo_cookie_life'] == 172800) { echo 'selected'; } ?>>48</option>
				<option value="259200" <?php if($adrotate_config['geo_cookie_life'] == 259200) { echo 'selected'; } ?>>72</option>
				<option value="432000" <?php if($adrotate_config['geo_cookie_life'] == 432000) { echo 'selected'; } ?>>120</option>
				<option value="604800" <?php if($adrotate_config['geo_cookie_life'] == 604800) { echo 'selected'; } ?>>168</option>
			</select> <?php _e('Hours.', 'adrotate'); ?></label><br />
			<span class="description"><?php _e('Geo Data is stored in a cookie to reduce lookups. How long should this cookie last? A longer period is less accurate for mobile users but may reduce the usage of your lookups drastically.', 'adrotate'); ?></span>

		</td>
	</tr>
	<?php if($adrotate_config['enable_geo'] > 1) { ?>
	<tr>
		<th valign="top"><?php _e('Remaining Requests', 'adrotate'); ?></th>
		<td><?php echo $adrotate_geo_requests; ?> <span class="description"><?php _e('This number is provided by the geo service and not checked for accuracy.', 'adrotate'); ?></span></td>
	</tr>
	<?php } ?>
</table>

<h3><?php _e('MaxMind City/Country', 'adrotate'); ?></h3>
<table class="form-table">
	<tr>
		<th valign="top"><?php _e('Username/Email', 'adrotate'); ?></th>
		<td><label for="adrotate_geo_email"><input name="adrotate_geo_email" type="text" class="search-input" size="50" value="<?php echo $adrotate_config['geo_email']; ?>" autocomplete="off" /></label></td>
	</tr>
	<tr>
		<th valign="top"><?php _e('Password/License Key', 'adrotate'); ?></th>
		<td><label for="adrotate_geo_pass"><input name="adrotate_geo_pass" type="text" class="search-input" size="50" value="<?php echo $adrotate_config['geo_pass']; ?>" autocomplete="off" /></label></td>
	</tr>
</table>

<?php if($adrotate_debug['geo'] == true) {
	adrotate_geolocation();

	if(isset($_SESSION['adrotate-geo'])) {
		$geo = $_SESSION['adrotate-geo'];
		$geo_source = 'Session data';
	} else {
		$geo = adrotate_get_cookie('geo');
		$geo_source = 'Cookie';
	}

	$cities = unserialize(stripslashes($banner->cities));
	$countries = unserialize(stripslashes($banner->countries));
	if(!is_array($cities)) $cities = array();
	if(!is_array($countries)) $countries = array();

	echo "<h3>Debug data</h3>";
	echo "<p><strong>Geo Targeting Data in YOUR cookie</strong><br />";
	echo "<strong>CAUTION! When you change Geo Services the cookie needs to refresh. You may have to save the settings twice for that to happen.</strong><pre>";	
	echo "Cookie or _SESSION: ".$geo_source."<br />";
	print_r($geo);
	echo "</pre></p>"; 
} ?>